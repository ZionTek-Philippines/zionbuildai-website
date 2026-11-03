import { getLang, translate } from './demo-lang';

const STICKY_OFFSET = 108; // demo frame (44px) + app top bar (64px)
const AUTOPLAY_DELAY = 900;
const MIN_STEP_MS = 6000;
const MS_PER_WORD = 330;

const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Intros show once per visit (browser session), not on every module page. Storage can be blocked, so fail open.
const SEEN_KEY = 'zionbuild-demo-seen';

function hasSeen(intro) {
    try {
        return JSON.parse(sessionStorage.getItem(SEEN_KEY) ?? '[]').includes(intro);
    } catch {
        return false;
    }
}

function markSeen(intro) {
    try {
        const seen = JSON.parse(sessionStorage.getItem(SEEN_KEY) ?? '[]');
        sessionStorage.setItem(SEEN_KEY, JSON.stringify([...new Set([...seen, intro])]));
    } catch {
        // Private mode or blocked storage: the intro may show again, which is harmless.
    }
}

// The mode the visitor chose (explore | guide | none), carried from one module page to the next.
const MODE_KEY = 'zionbuild-demo-mode';

function rememberMode(mode) {
    try {
        sessionStorage.setItem(MODE_KEY, mode);
    } catch {
        // Blocked storage: the next module page simply opens without a mode.
    }
}

function rememberedMode() {
    try {
        return sessionStorage.getItem(MODE_KEY);
    } catch {
        return null;
    }
}

// True when arriving from another demo page (module tabs, "Next: …" links), false when entering the demo fresh.
function cameFromDemoPage() {
    try {
        const referrer = new URL(document.referrer);

        return referrer.origin === window.location.origin && referrer.pathname.startsWith('/demo');
    } catch {
        return false;
    }
}

export function initDemoGuide() {
    const root = document.querySelector('[data-demo-guide]');

    if (!root) {
        return;
    }

    const steps = JSON.parse(root.dataset.steps);
    const el = (name) => root.querySelector(`[data-guide-${name}]`);
    const ui = {
        backdrop: el('backdrop'),
        highlight: el('highlight'),
        card: el('card'),
        counter: el('counter'),
        status: el('status'),
        title: el('title'),
        body: el('body'),
        progress: el('progress'),
        final: el('final'),
        nextModule: el('next-module'),
        back: el('back'),
        next: el('next'),
        toggle: el('toggle'),
        toggleIcon: el('toggle-icon'),
        tip: el('tip'),
        tipTitle: el('tip-title'),
        tipBody: el('tip-body'),
        tipStart: el('tip-start'),
        tipActions: el('tip-actions'),
        launcher: el('launcher'),
        launchers: el('launchers'),
        explore: el('explore'),
        exploreIntro: el('explore-intro'),
        coach: el('coach'),
        dotTemplate: el('dot-template'),
        example: el('example'),
        exampleText: el('example-text'),
        tipExample: el('tip-example'),
        tipExampleText: el('tip-example-text'),
        dots: el('dots'),
    };

    const state = {
        active: false,
        index: 0,
        target: null,
        playing: true,
        hovering: false,
        elapsed: 0,
        lastTick: 0,
        frame: null,
        tipTarget: null,
        tipHideTimer: null,
        exploring: false,
        hotspots: [],
        dotsFrame: null,
        hasSeenExploreIntro: hasSeen('explore-intro'),
    };

    const targetFor = (step) => (step?.target ? document.querySelector(`[data-tour="${step.target}"]`) : null);
    // Sections inside the sticky bars never scroll away, so they are not clamped below them.
    const isPinned = (target) => Boolean(target.closest('.sticky'));
    // A step's copy in the visitor's language: {title, body, example?}
    const copyOf = (step) => step[getLang()] ?? step.en;
    const stepDuration = (step) => {
        const { body, example = '' } = copyOf(step);

        return Math.max(MIN_STEP_MS, `${body} ${example}`.split(/\s+/).length * MS_PER_WORD);
    };

    function renderExample(box, text, example) {
        box.classList.toggle('hidden', !example);
        text.textContent = example ?? '';
    }

    // ---- Tour ----------------------------------------------------------

    function start(index = 0) {
        setExploring(false);
        rememberMode('guide');
        state.active = true;
        state.playing = true;
        ui.launchers.classList.replace('flex', 'hidden');
        ui.backdrop.classList.remove('hidden');
        ui.card.classList.remove('hidden');
        show(index, 1);
        startTimer();
    }

    function stop() {
        rememberMode('none');
        state.active = false;
        state.target = null;
        cancelAnimationFrame(state.frame);
        ui.backdrop.classList.add('hidden');
        ui.highlight.classList.add('hidden');
        ui.card.classList.add('hidden');
        ui.launchers.classList.replace('hidden', 'flex');
    }

    // Skip steps whose section is missing on this page, in the direction of travel.
    function show(index, direction) {
        while (index >= 0 && index < steps.length && steps[index].target && !targetFor(steps[index])) {
            index += direction;
        }

        if (index < 0 || index >= steps.length) {
            stop();

            return;
        }

        const step = steps[index];
        const isLast = index === steps.length - 1;

        // Steps on another sub-tab (e.g. AI Photo Analysis) open that tab first.
        if (step.tab) {
            document.querySelector(`[data-demo-tab="${step.tab}"]`)?.click();
        }

        state.index = index;
        state.elapsed = 0;
        state.target = targetFor(step);

        // An "overview" first step is step 0, so the page's own steps keep their 1..N numbering.
        const offset = steps[0].overview ? 0 : 1;
        ui.counter.textContent = `Guide · ${index + offset} of ${steps.length - 1 + offset}`;
        renderStepCopy();
        ui.back.disabled = index === 0;
        ui.next.textContent = isLast ? 'Finish' : 'Next';
        ui.final.classList.toggle('hidden', !step.final);
        ui.final.classList.toggle('flex', Boolean(step.final));
        ui.nextModule.style.display = step.next ? '' : 'none';
        ui.backdrop.classList.toggle('bg-white/70', !state.target);
        renderPlayState();
        renderProgress();

        if (state.target && !isPinned(state.target)) {
            scrollToTarget(state.target);
        }

        reposition();
        setTimeout(reposition, prefersReducedMotion() ? 0 : 450);
    }

    function renderStepCopy() {
        const step = steps[state.index];
        const { title, body, example } = copyOf(step);

        ui.title.textContent = title;
        ui.body.textContent = body;
        renderExample(ui.example, ui.exampleText, example);

        if (step.next) {
            ui.nextModule.href = step.next.url;
            ui.nextModule.textContent = `${getLang() === 'tl' ? 'Susunod' : 'Next'}: ${translate(step.next.label)} →`;
        }
    }

    function next() {
        if (state.index >= steps.length - 1) {
            stop();

            return;
        }

        show(state.index + 1, 1);
    }

    function back() {
        if (state.index > 0) {
            show(state.index - 1, -1);
        }
    }

    function scrollToTarget(target) {
        const rect = target.getBoundingClientRect();
        const room = window.innerHeight - STICKY_OFFSET;
        const centred = rect.height < room ? (room - rect.height) / 2 : 16;

        window.scrollTo({
            top: Math.max(0, window.scrollY + rect.top - STICKY_OFFSET - centred),
            behavior: prefersReducedMotion() ? 'auto' : 'smooth',
        });
    }

    function reposition() {
        if (!state.active) {
            return;
        }

        const margin = 12;
        const card = ui.card.getBoundingClientRect();
        const isMobile = window.innerWidth < 640;

        if (!state.target) {
            ui.highlight.classList.add('hidden');
            placeCard((window.innerWidth - card.width) / 2, (window.innerHeight - card.height) / 2);

            return;
        }

        const r = state.target.getBoundingClientRect();
        const top = Math.max(r.top - 8, isPinned(state.target) ? 4 : STICKY_OFFSET - 8);
        const bottom = Math.min(r.bottom + 8, window.innerHeight - 4);

        ui.highlight.classList.remove('hidden');
        Object.assign(ui.highlight.style, {
            top: `${top}px`,
            left: `${r.left - 8}px`,
            width: `${r.width + 16}px`,
            height: `${Math.max(0, bottom - top)}px`,
        });

        if (isMobile) {
            placeCard((window.innerWidth - card.width) / 2, window.innerHeight - card.height - margin);

            return;
        }

        let cardTop = r.bottom + 20;

        if (cardTop + card.height > window.innerHeight - margin) {
            cardTop = r.top - card.height - 20;
        }

        if (cardTop < STICKY_OFFSET + margin && !isPinned(state.target)) {
            // Section fills the screen: pin the card to the bottom-right corner.
            placeCard(window.innerWidth - card.width - 24, window.innerHeight - card.height - 24);

            return;
        }

        const left = Math.min(Math.max(margin, r.left), window.innerWidth - card.width - margin);
        placeCard(left, cardTop);
    }

    function placeCard(left, top) {
        ui.card.style.left = `${Math.max(12, left)}px`;
        ui.card.style.top = `${Math.max(12, top)}px`;
    }

    // ---- Auto-play -----------------------------------------------------

    function startTimer() {
        cancelAnimationFrame(state.frame);
        state.lastTick = performance.now();
        state.frame = requestAnimationFrame(tick);
    }

    function tick(now) {
        if (!state.active) {
            return;
        }

        const delta = now - state.lastTick;
        state.lastTick = now;

        if (state.playing && !state.hovering && !document.hidden) {
            state.elapsed += delta;

            if (state.elapsed >= stepDuration(steps[state.index])) {
                if (steps[state.index].final) {
                    setPlaying(false);
                } else {
                    next();
                }
            }

            renderProgress();
        }

        state.frame = requestAnimationFrame(tick);
    }

    function setPlaying(playing) {
        state.playing = playing;
        renderPlayState();
    }

    function renderPlayState() {
        ui.status.textContent = state.playing ? '▶ Auto-playing' : 'Paused';
        ui.toggleIcon.textContent = state.playing ? 'pause' : 'play_arrow';
        ui.toggle.setAttribute('aria-label', state.playing ? 'Pause tour' : 'Resume tour');
    }

    function renderProgress() {
        const ratio = Math.min(1, state.elapsed / stepDuration(steps[state.index]));
        ui.progress.style.width = `${ratio * 100}%`;
    }

    // ---- Hover explanations -------------------------------------------

    // The tip sits next to the explore dot when one is given, otherwise above the section's top-right corner.
    function showTip(section, dot = null) {
        const step = steps.find((candidate) => candidate.target === section.dataset.tour);

        if (state.active || !step) {
            return;
        }

        clearTimeout(state.tipHideTimer);
        state.tipTarget?.classList.remove('outline-2', 'outline-demo-blue');
        state.tipTarget = section;
        section.classList.add('outline-2', 'outline-demo-blue');

        const { title, body, example } = copyOf(step);
        ui.tipTitle.textContent = title;
        ui.tipBody.textContent = body;
        renderExample(ui.tipExample, ui.tipExampleText, example);
        ui.tipStart.dataset.index = String(steps.indexOf(step));
        ui.tipActions.classList.toggle('hidden', Boolean(dot));
        ui.tip.classList.remove('hidden');

        const tip = ui.tip.getBoundingClientRect();

        if (dot) {
            const d = dot.getBoundingClientRect();
            const below = d.bottom + 8;
            ui.tip.style.top = `${below + tip.height > window.innerHeight - 12 ? d.top - tip.height - 8 : below}px`;
            ui.tip.style.left = `${Math.min(Math.max(12, d.left - 8), window.innerWidth - tip.width - 12)}px`;

            return;
        }

        const r = section.getBoundingClientRect();
        let top = r.top - tip.height - 10;

        if (top < STICKY_OFFSET + 8) {
            top = Math.min(r.top + 12, window.innerHeight - tip.height - 12);
        }

        const left = Math.min(Math.max(12, r.right - tip.width), window.innerWidth - tip.width - 12);
        ui.tip.style.top = `${Math.max(STICKY_OFFSET + 8, top)}px`;
        ui.tip.style.left = `${left}px`;
    }

    function hideTip() {
        state.tipTarget?.classList.remove('outline-2', 'outline-demo-blue');
        state.tipTarget = null;
        ui.tip.classList.add('hidden');
    }

    function scheduleHideTip() {
        clearTimeout(state.tipHideTimer);
        state.tipHideTimer = setTimeout(hideTip, 250);
    }

    document.querySelectorAll('[data-tour-info]').forEach((badge) => {
        badge.addEventListener('click', (event) => {
            event.stopPropagation();
            showTip(badge.closest('[data-tour]'));
        });
    });

    ui.tip.addEventListener('mouseenter', () => clearTimeout(state.tipHideTimer));
    ui.tip.addEventListener('mouseleave', scheduleHideTip);
    ui.tipStart.addEventListener('click', () => start(Number(ui.tipStart.dataset.index)));
    el('tip-explore').addEventListener('click', () => setExploring(true));
    // Dots and "i" badges stop their own clicks, so any other click closes the tip.
    document.addEventListener('click', (event) => {
        if (!ui.tip.contains(event.target)) {
            hideTip();
        }
    });
    window.addEventListener('scroll', () => (state.active ? reposition() : hideTip()), { passive: true });
    window.addEventListener('resize', reposition);

    // ---- Explore mode --------------------------------------------------
    // A glowing dot on the top-left corner of every walkthrough section, for visitors
    // who would rather look around than sit through the tour.

    function setExploring(exploring) {
        state.exploring = exploring;
        ui.explore.setAttribute('aria-pressed', String(exploring));
        cancelAnimationFrame(state.dotsFrame);
        ui.dots.replaceChildren();
        state.hotspots = [];
        hideTip();

        if (!exploring) {
            return;
        }

        rememberMode('explore');

        steps.forEach((step) => {
            const section = targetFor(step);

            if (!section) {
                return;
            }

            const dot = ui.dotTemplate.content.firstElementChild.cloneNode(true);
            dot.setAttribute('aria-label', copyOf(step).title);
            dot.addEventListener('mouseenter', () => showTip(section, dot));
            dot.addEventListener('mouseleave', scheduleHideTip);
            dot.addEventListener('click', (event) => {
                event.stopPropagation();
                showTip(section, dot);
            });

            ui.dots.append(dot);
            state.hotspots.push({ dot, section });
        });

        placeDots();

        if (!state.hasSeenExploreIntro) {
            state.hasSeenExploreIntro = true;
            markSeen('explore-intro');
            ui.exploreIntro.classList.replace('hidden', 'flex');
        }
    }

    function closeExploreIntro() {
        ui.exploreIntro.classList.replace('flex', 'hidden');
    }

    // ---- First-visit coach mark ---------------------------------------

    function showCoach() {
        ui.coach.classList.replace('hidden', 'flex');
    }

    function closeCoach() {
        ui.coach.classList.replace('flex', 'hidden');
    }

    const isCoachOpen = () => ui.coach.classList.contains('flex');

    // Follow the sections every frame (scrolling, resizing, tab switches); hide dots that slide under the sticky bars.
    function placeDots() {
        state.hotspots.forEach(({ dot, section }) => {
            const r = section.getBoundingClientRect();
            const top = Math.max(r.top, 12);
            const isOnScreen = r.width > 0 && (isPinned(section) || top > STICKY_OFFSET + 4) && top < window.innerHeight - 8;

            dot.style.display = isOnScreen ? '' : 'none';
            dot.style.top = `${top}px`;
            dot.style.left = `${Math.max(r.left, 12)}px`;
        });

        state.dotsFrame = requestAnimationFrame(placeDots);
    }

    // ---- Language ------------------------------------------------------

    document.addEventListener('demo:lang', () => {
        hideTip();
        state.hotspots.forEach(({ dot, section }) => {
            const step = steps.find((candidate) => candidate.target === section.dataset.tour);
            dot.setAttribute('aria-label', copyOf(step).title);
        });

        if (state.active) {
            renderStepCopy();
            reposition();
        }
    });

    // ---- Controls ------------------------------------------------------

    ui.next.addEventListener('click', next);
    ui.back.addEventListener('click', back);
    ui.toggle.addEventListener('click', () => setPlaying(!state.playing));
    el('stop').addEventListener('click', stop);
    el('restart').addEventListener('click', () => start(0));
    ui.launcher.addEventListener('click', () => {
        if (isCoachOpen()) {
            closeCoach();
        }

        start(0);
    });
    ui.explore.addEventListener('click', () => {
        if (isCoachOpen()) {
            closeCoach();
            setExploring(true);

            return;
        }

        if (state.exploring) {
            setExploring(false);
            rememberMode('none');

            return;
        }

        setExploring(true);
    });
    // Closing the chooser without picking falls back to Explore, the default.
    ['coach-explore', 'coach-close'].forEach((name) => el(name).addEventListener('click', () => {
        closeCoach();
        setExploring(true);
    }));
    el('coach-guide').addEventListener('click', () => {
        closeCoach();
        start(0);
    });
    ui.coach.addEventListener('click', (event) => {
        if (event.target === ui.coach) {
            closeCoach();
            setExploring(true);
        }
    });
    el('explore-go').addEventListener('click', closeExploreIntro);
    el('explore-tour').addEventListener('click', () => {
        closeExploreIntro();
        start(0);
    });
    ui.exploreIntro.addEventListener('click', (event) => event.target === ui.exploreIntro && closeExploreIntro());
    // Clicks on the spotlighted element pass through (e.g. "click this button"); anywhere else pauses.
    ui.backdrop.addEventListener('click', (event) => {
        setPlaying(false);

        const r = state.target?.getBoundingClientRect();
        const isOnTarget = r && event.clientX >= r.left && event.clientX <= r.right && event.clientY >= r.top && event.clientY <= r.bottom;

        if (isOnTarget) {
            state.target.click();
        }
    });
    ui.card.addEventListener('mouseenter', () => (state.hovering = true));
    ui.card.addEventListener('mouseleave', () => (state.hovering = false));

    document.addEventListener('keydown', (event) => {
        if (isCoachOpen() && event.key === 'Escape') {
            closeCoach();
            setExploring(true);

            return;
        }

        if (state.exploring && event.key === 'Escape') {
            if (ui.exploreIntro.classList.contains('flex')) {
                closeExploreIntro();
            } else {
                setExploring(false);
                rememberMode('none');
            }
        }

        if (!state.active) {
            return;
        }

        const actions = { Escape: stop, ArrowRight: next, ArrowLeft: back };
        actions[event.key]?.();
    });

    // Entering the demo shows the "Explore or Guide" coach mark; moving between module pages keeps the chosen mode.
    // ?guide=1 (or any step number) auto-plays the tour from that step; ?guide=off opens with neither (handy for ad deep links).
    const guideParam = new URLSearchParams(window.location.search).get('guide');
    const guideStep = Number.parseInt(guideParam, 10);

    if (guideStep > 0) {
        setTimeout(() => start(Math.min(guideStep - 1, steps.length - 1)), AUTOPLAY_DELAY);
    } else if (guideParam !== 'off') {
        const isContinuing = cameFromDemoPage();

        setTimeout(() => {
            if (!isContinuing) {
                showCoach();

                return;
            }

            const mode = rememberedMode();

            if (mode === 'explore') {
                setExploring(true);
            } else if (mode === 'guide') {
                start(0);
            }
        }, AUTOPLAY_DELAY);
    }
}

// On narrow screens the module tabs scroll sideways; bring the current one into view.
export function initDemoNav() {
    const current = document.querySelector('[aria-label="Demo modules"] [aria-current="page"]');
    const nav = current?.parentElement;

    if (!current) {
        return;
    }

    // Center the current tab and the highlighted next one together, so "Tap next" is visible on phones too.
    // Measure after web fonts load, since they change the tab widths.
    document.fonts.ready.then(() => {
        const next = nav.querySelector('[data-demo-next]');
        const navLeft = nav.getBoundingClientRect().left;
        const right = (next ?? current).getBoundingClientRect().right - navLeft;
        // When both don't fit (phones), show the next tab in full rather than the current one.
        const left = next && right - (current.getBoundingClientRect().left - navLeft) > nav.clientWidth
            ? next.getBoundingClientRect().left - navLeft
            : current.getBoundingClientRect().left - navLeft;
        nav.scrollLeft += (left + right) / 2 - nav.clientWidth / 2;
    });
}

// Inside the landing page's "Try it!" modal, let the parent close the modal or jump to the booking section.
export function initDemoFrameLinks() {
    if (window.self === window.top) {
        return;
    }

    const notifyParent = (selector, type) => {
        document.querySelectorAll(selector).forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                window.parent.postMessage({ type }, window.location.origin);
            });
        });
    };

    notifyParent('[data-demo-close]', 'zionbuild-demo:close');
    notifyParent('[data-demo-book]', 'zionbuild-demo:book');
}
