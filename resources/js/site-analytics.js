/*
 * Anonymous, cookie-less site analytics for the admin panel's Site Analytics page.
 *
 * Only runs when the page carries <meta name="site-analytics"> (guests only). It never reads what visitors type,
 * honours Do Not Track / Global Privacy Control, and identifies browsers by a random ID kept in localStorage.
 */

const VISIT_TIMEOUT_MS = 30 * 60 * 1000;
const IDLE_AFTER_MS = 60 * 1000;
const FLUSH_EVERY_MS = 15 * 1000;
const SCROLL_MILESTONES = [25, 50, 75, 100];

function safeStorage() {
    try {
        localStorage.setItem('zb-test', '1');
        localStorage.removeItem('zb-test');

        return localStorage;
    } catch {
        return null;
    }
}

// crypto.randomUUID() needs HTTPS; getRandomValues() works everywhere.
function randomUuid() {
    return '10000000-1000-4000-8000-100000000000'.replace(/[018]/g, (digit) =>
        (digit ^ (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (digit / 4)))).toString(16),
    );
}

function visitorId(storage) {
    let id = storage?.getItem('zb-visitor');

    if (!id) {
        id = randomUuid();
        storage?.setItem('zb-visitor', id);
    }

    return id;
}

function currentVisitId(storage) {
    const saved = JSON.parse(storage?.getItem('zb-visit') ?? 'null');
    const id = saved && Date.now() - saved.lastSeen < VISIT_TIMEOUT_MS ? saved.id : randomUuid();

    storage?.setItem('zb-visit', JSON.stringify({ id, lastSeen: Date.now() }));

    return id;
}

function trafficSource() {
    const params = new URLSearchParams(window.location.search);
    let referrerHost = null;

    try {
        const referrer = new URL(document.referrer);
        referrerHost = referrer.host === window.location.host ? null : referrer.host;
    } catch {
        // No referrer (typed URL, bookmark, app link).
    }

    return {
        referrer_host: referrerHost,
        utm_source: params.get('utm_source')?.slice(0, 100) ?? null,
        utm_medium: params.get('utm_medium')?.slice(0, 100) ?? null,
        utm_campaign: params.get('utm_campaign')?.slice(0, 100) ?? null,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone?.slice(0, 50) ?? null,
    };
}

// Button text without Material Symbols ligatures like "arrow_forward".
export function readableLabel(element) {
    if (element.dataset.track) {
        return element.dataset.track;
    }

    const clone = element.cloneNode(true);
    clone.querySelectorAll('.material-symbols-outlined').forEach((icon) => icon.remove());

    const text = clone.textContent.replace(/\s+/g, ' ').trim();

    return (text || element.getAttribute('aria-label') || element.getAttribute('href') || 'Unlabelled').slice(0, 150);
}

export function initSiteAnalytics() {
    const endpoint = document.querySelector('meta[name="site-analytics"]')?.content;

    if (!endpoint || navigator.globalPrivacyControl || navigator.doNotTrack === '1') {
        return;
    }

    const storage = safeStorage();
    const source = trafficSource();
    const sections = [...document.querySelectorAll('[data-track-section]')];
    const sectionSeconds = {};
    const reachedMilestones = new Set();
    const startedForms = new Set();
    const focusedFields = new Set();
    let queue = [];
    let lastActivity = Date.now();

    const track = (type, fields = {}) => {
        queue.push({ type, path: window.location.pathname, ...fields });

        if (queue.length >= 40) {
            flush();
        }
    };

    function flush() {
        Object.entries(sectionSeconds).forEach(([section, seconds]) => {
            queue.push({ type: 'section_time', path: window.location.pathname, section, label: section, value: seconds });
            delete sectionSeconds[section];
        });

        if (queue.length === 0) {
            return;
        }

        const body = JSON.stringify({
            visitor_id: visitorId(storage),
            visit_id: currentVisitId(storage),
            ...source,
            events: queue.splice(0, 50),
        });
        const blob = new Blob([body], { type: 'application/json' });

        if (!navigator.sendBeacon?.(endpoint, blob)) {
            fetch(endpoint, { method: 'POST', body, keepalive: true, headers: { 'Content-Type': 'application/json' } }).catch(() => {});
        }

        if (queue.length > 0) {
            flush();
        }
    }

    // Sent straight away so the visit exists even if the visitor books within seconds.
    track('page_view', { label: document.title.slice(0, 150) });
    flush();

    document.addEventListener(
        'click',
        (event) => {
            const element = event.target.closest('a, button, [data-track]');

            // Closing an open FAQ answer (or any expanded toggle) is not interest worth counting.
            if (element && element.getAttribute('aria-expanded') !== 'true') {
                track('click', { label: readableLabel(element), section: element.closest('[data-track-section]')?.dataset.trackSection ?? null });
            }
        },
        true,
    );

    document.addEventListener('focusin', (event) => {
        const form = event.target.closest('form[data-track-form]');

        if (!form) {
            return;
        }

        if (!startedForms.has(form)) {
            startedForms.add(form);
            track('form_start', { label: form.dataset.trackForm });
        }

        // Only the field's name is recorded, never its value.
        const field = event.target.closest('[data-track-field]');

        if (field && !focusedFields.has(field)) {
            focusedFields.add(field);
            track('form_field', { label: field.dataset.trackField, section: form.dataset.trackForm });
        }
    });

    document.addEventListener('submit', (event) => {
        if (event.target.matches('form[data-track-form]')) {
            // Lets the admin panel show this lead's journey (pages, sections, clicks) next to their booking.
            event.target.querySelector('[name="analytics_visitor_id"]')?.setAttribute('value', visitorId(storage));
            event.target.querySelector('[name="analytics_visit_id"]')?.setAttribute('value', currentVisitId(storage));

            track('form_submit', { label: event.target.dataset.trackForm });
            flush();
        }
    });

    ['scroll', 'pointermove', 'pointerdown', 'keydown', 'touchstart'].forEach((name) =>
        window.addEventListener(name, () => (lastActivity = Date.now()), { passive: true }),
    );

    window.addEventListener(
        'scroll',
        () => {
            const scrolled = ((window.scrollY + window.innerHeight) / document.documentElement.scrollHeight) * 100;

            SCROLL_MILESTONES.filter((milestone) => scrolled >= milestone - 1 && !reachedMilestones.has(milestone)).forEach((milestone) => {
                reachedMilestones.add(milestone);
                track('scroll', { value: milestone });
            });
        },
        { passive: true },
    );

    // Once a second, credit the section crossing the middle of the screen, unless the visitor is idle,
    // the tab is hidden, or the live demo popup is covering the page.
    if (sections.length > 0) {
        setInterval(() => {
            const isReading =
                document.visibilityState === 'visible' &&
                Date.now() - lastActivity < IDLE_AFTER_MS &&
                !document.querySelector('[data-try-it-modal].flex');

            if (!isReading) {
                return;
            }

            const middle = window.innerHeight / 2;
            const section = sections.find((element) => {
                const rect = element.getBoundingClientRect();

                return rect.top <= middle && rect.bottom >= middle;
            });

            if (section) {
                const name = section.dataset.trackSection;
                sectionSeconds[name] = (sectionSeconds[name] ?? 0) + 1;
            }
        }, 1000);
    }

    setInterval(flush, FLUSH_EVERY_MS);
    document.addEventListener('visibilitychange', () => document.visibilityState === 'hidden' && flush());
    window.addEventListener('pagehide', flush);
}
