/*
 * Visual Analytics (admin panel): draws homepage stats on top of the real homepage loaded in a same-origin iframe.
 * Click hotspots glow and ripple, sections are tinted by reading time, dashed lines mark scroll depth,
 * and booking form fields show where visitors gave up. Data comes from App\Filament\Pages\VisualAnalytics.
 */
import { readableLabel } from './site-analytics';

const OVERLAY_CSS = `
.zbv-layer { position: absolute; top: 0; left: 0; width: 100%; pointer-events: none; z-index: 2147483000; font-family: Inter, system-ui, sans-serif; }
.zbv-fixed { position: fixed; top: 0; left: 0; width: 100%; height: 0; pointer-events: none; z-index: 2147483001; font-family: Inter, system-ui, sans-serif; }
.zbv-dim { position: absolute; inset: 0; background: rgba(8, 12, 28, 0.3); }
.zbv-hotspot { position: absolute; transform: translate(-50%, -50%); pointer-events: auto; cursor: pointer; z-index: 3; }
.zbv-core { position: relative; border-radius: 9999px; background: var(--zbv-color); box-shadow: 0 0 10px 3px var(--zbv-color), 0 0 34px 12px var(--zbv-soft); }
.zbv-ring { position: absolute; left: 50%; top: 50%; width: 100%; height: 100%; border: 2px solid var(--zbv-color); border-radius: 9999px; opacity: 0; animation: zbv-ripple 2.4s ease-out infinite; }
.zbv-ring:nth-child(2) { animation-delay: 0.8s; }
.zbv-ring:nth-child(3) { animation-delay: 1.6s; }
@keyframes zbv-ripple { 0% { transform: translate(-50%, -50%) scale(1); opacity: 0.75; } 100% { transform: translate(-50%, -50%) scale(4.2); opacity: 0; } }
.zbv-count.zbv-count-left { left: auto; right: calc(100% + 8px); }
.zbv-count.zbv-count-below { left: 50%; top: calc(100% + 6px); transform: translateX(-50%); }
.zbv-count { position: absolute; left: calc(100% + 8px); top: 50%; transform: translateY(-50%); background: rgba(15, 23, 42, 0.92); color: #fff; font: 700 11px/1 Inter, system-ui, sans-serif; padding: 5px 8px; border-radius: 9999px; white-space: nowrap; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); }
.zbv-outline { position: absolute; border-radius: 14px; box-shadow: 0 0 0 2px var(--zbv-color), 0 0 22px 4px var(--zbv-soft); z-index: 2; }
.zbv-section { position: absolute; left: 0; width: 100%; border-left: 6px solid var(--zbv-color); background: var(--zbv-tint); z-index: 1; }
.zbv-card { position: absolute; top: 14px; left: 18px; pointer-events: auto; max-width: 320px; background: rgba(15, 23, 42, 0.9); color: #fff; border-radius: 12px; padding: 9px 12px; font: 12px/1.45 Inter, system-ui, sans-serif; box-shadow: 0 6px 24px rgba(0, 0, 0, 0.3); backdrop-filter: blur(6px); }
.zbv-card b { font-size: 13px; }
.zbv-card a, .zbv-popover a, .zbv-line a { color: inherit; font-weight: 700; text-decoration: underline; text-underline-offset: 2px; }
.zbv-bar { margin-top: 6px; height: 5px; border-radius: 9999px; background: rgba(255, 255, 255, 0.18); overflow: hidden; }
.zbv-bar span { display: block; height: 100%; border-radius: 9999px; background: var(--zbv-color); }
.zbv-line { position: absolute; left: 0; width: 100%; border-top: 2px dashed rgba(244, 63, 94, 0.85); z-index: 2; }
.zbv-line span { position: absolute; right: 18px; top: -13px; pointer-events: auto; background: #f43f5e; color: #fff; font: 700 11px/1 Inter, system-ui, sans-serif; padding: 6px 10px; border-radius: 9999px; box-shadow: 0 4px 14px rgba(244, 63, 94, 0.4); white-space: nowrap; }
.zbv-field { position: absolute; pointer-events: auto; transform: translateY(-50%); background: var(--zbv-color); color: #fff; font: 700 11px/1.2 Inter, system-ui, sans-serif; padding: 6px 9px; border-radius: 9999px; white-space: nowrap; box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.9), 0 0 18px 4px var(--zbv-soft); z-index: 3; }
.zbv-popover { position: absolute; pointer-events: auto; width: 270px; background: #fff; color: #0f172a; border-radius: 14px; padding: 14px 16px; font: 13px/1.5 Inter, system-ui, sans-serif; box-shadow: 0 18px 48px rgba(0, 0, 0, 0.3); z-index: 5; }
.zbv-popover small { display: block; color: #64748b; }
.zbv-popover .zbv-big { font: 800 26px/1.1 Inter, system-ui, sans-serif; margin: 6px 0 2px; }
html.zbv-hide-clicks .zbv-hotspot, html.zbv-hide-clicks .zbv-outline,
html.zbv-hide-sections .zbv-section, html.zbv-hide-scroll .zbv-line,
html.zbv-hide-fields .zbv-field, html.zbv-hide-dim .zbv-dim { display: none; }
@media (prefers-reduced-motion: reduce) { .zbv-ring { animation: none; } }
`;

const escapeHtml = (text) => String(text).replace(/[&<>"']/g, (character) => `&#${character.charCodeAt(0)};`);

// Cool blue for little activity through to hot red for the most.
function heat(intensity) {
    const hue = Math.round(220 - 220 * Math.min(1, Math.max(0, intensity)));

    return { color: `hsl(${hue} 95% 55%)`, soft: `hsl(${hue} 95% 55% / 0.45)`, tint: `hsl(${hue} 95% 55% / ${0.04 + intensity * 0.08})` };
}

function paint(element, intensity) {
    const colors = heat(intensity);
    element.style.setProperty('--zbv-color', colors.color);
    element.style.setProperty('--zbv-soft', colors.soft);
    element.style.setProperty('--zbv-tint', colors.tint);

    return element;
}

function formatSeconds(seconds) {
    return seconds < 60 ? `${seconds}s` : `${Math.floor(seconds / 60)}m ${String(seconds % 60).padStart(2, '0')}s`;
}

function percentOf(part, whole) {
    return whole > 0 ? `${Math.round((part / whole) * 100)}%` : '0%';
}

function isVisible(element) {
    const rect = element.getBoundingClientRect();

    return rect.width > 0 && rect.height > 0 && element.ownerDocument.defaultView.getComputedStyle(element).visibility !== 'hidden';
}

function isInFixedHeader(element) {
    const header = element.closest('header');

    return header !== null && header.ownerDocument.defaultView.getComputedStyle(header).position === 'fixed';
}

class VisualAnalyticsOverlay {
    constructor(root) {
        this.root = root;
        this.iframe = root.querySelector('[data-visual-frame]');
        this.data = JSON.parse(root.querySelector('[data-visual-data]').textContent);
        this.unmatchedList = root.querySelector('[data-visual-unmatched]');
        this.redrawTimer = null;

        root.querySelectorAll('[data-visual-toggle]').forEach((toggle) =>
            toggle.addEventListener('change', () => this.applyToggles()),
        );

        root.querySelectorAll('[data-visual-device]').forEach((button) =>
            button.addEventListener('click', () => {
                root.querySelectorAll('[data-visual-device]').forEach((other) => other.setAttribute('aria-pressed', String(other === button)));
                this.iframe.style.width = button.dataset.visualDevice === 'mobile' ? '390px' : '100%';
            }),
        );

        this.iframe.addEventListener('load', () => this.attach());

        if (this.iframe.contentDocument?.readyState === 'complete' && this.iframe.contentDocument.URL !== 'about:blank') {
            this.attach();
        }
    }

    get doc() {
        return this.iframe.contentDocument;
    }

    attach() {
        const doc = this.doc;
        const frameWindow = this.iframe.contentWindow;

        const style = doc.createElement('style');
        style.textContent = OVERLAY_CSS;
        doc.head.appendChild(style);

        // Keep the admin on this page: links, the demo popup and forms are disabled inside the preview.
        doc.addEventListener(
            'click',
            (event) => {
                if (event.target.closest('.zbv-hotspot, .zbv-popover, .zbv-card, .zbv-line, .zbv-field')) {
                    return;
                }

                this.closePopover();

                if (event.target.closest('a[href], [data-try-it]')) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }

                // FAQ answers and feature tabs still open; redraw once the layout settles.
                this.scheduleRedraw(350);
            },
            true,
        );
        doc.addEventListener('submit', (event) => event.preventDefault(), true);

        // The feature videos try to unmute themselves; keep the admin panel silent.
        doc.querySelectorAll('video').forEach((video) => {
            video.muted = true;
            video.addEventListener('volumechange', () => !video.muted && (video.muted = true));
        });

        frameWindow.addEventListener('resize', () => this.scheduleRedraw(150));
        new frameWindow.ResizeObserver(() => this.scheduleRedraw(200)).observe(doc.body);
        doc.fonts?.ready.then(() => this.scheduleRedraw(0));
        frameWindow.addEventListener('load', () => this.scheduleRedraw(0));

        this.draw();
    }

    scheduleRedraw(delay) {
        clearTimeout(this.redrawTimer);
        this.redrawTimer = setTimeout(() => this.draw(), delay);
    }

    applyToggles() {
        const doc = this.doc;

        if (!doc) {
            return;
        }

        this.root.querySelectorAll('[data-visual-toggle]').forEach((toggle) =>
            doc.documentElement.classList.toggle(`zbv-hide-${toggle.dataset.visualToggle}`, !toggle.checked),
        );
    }

    draw() {
        const doc = this.doc;

        if (!doc?.body) {
            return;
        }

        doc.querySelectorAll('.zbv-layer, .zbv-fixed').forEach((layer) => layer.remove());

        const frameWindow = this.iframe.contentWindow;
        const pageHeight = doc.documentElement.scrollHeight;

        this.layer = doc.createElement('div');
        this.layer.className = 'zbv-layer';
        this.layer.style.height = `${pageHeight}px`;
        this.layer.innerHTML = '<div class="zbv-dim"></div>';

        this.fixedLayer = doc.createElement('div');
        this.fixedLayer.className = 'zbv-fixed';

        doc.body.append(this.layer, this.fixedLayer);

        const toDocument = (rect) => ({ top: rect.top + frameWindow.scrollY, left: rect.left + frameWindow.scrollX, width: rect.width, height: rect.height });

        this.drawSections(doc, toDocument);
        this.drawScrollLines(pageHeight);
        const unmatched = this.drawClicks(doc, toDocument);
        unmatched.push(...this.drawFields(doc, toDocument));

        this.renderUnmatched(unmatched);
        this.applyToggles();
    }

    drawSections(doc, toDocument) {
        const sections = this.data.sections;
        const longest = Math.max(1, ...sections.map((section) => section.averageSeconds));

        sections.forEach((section) => {
            const element = doc.querySelector(`[data-track-section="${CSS.escape(section.name)}"]`);

            if (!element || !isVisible(element) || isInFixedHeader(element)) {
                return;
            }

            const box = toDocument(element.getBoundingClientRect());
            const intensity = section.averageSeconds / longest;
            const overlay = paint(this.layer.ownerDocument.createElement('div'), intensity);
            overlay.className = 'zbv-section';
            overlay.style.top = `${box.top}px`;
            overlay.style.height = `${box.height}px`;
            overlay.innerHTML = `
                <div class="zbv-card">
                    <b>${escapeHtml(section.name)}</b> · avg <b>${formatSeconds(section.averageSeconds)}</b> reading time<br>
                    Read in ${percentOf(section.readers, this.data.homepageVisits)} of homepage visits ·
                    <a href="${escapeHtml(section.url)}" target="_top">${section.readers} visits →</a>
                    <div class="zbv-bar"><span style="width:${Math.round(intensity * 100)}%"></span></div>
                </div>`;
            this.layer.appendChild(overlay);
        });
    }

    drawScrollLines(pageHeight) {
        this.data.scroll.forEach((step) => {
            const line = this.layer.ownerDocument.createElement('div');
            line.className = 'zbv-line';
            line.style.top = `${Math.min(pageHeight - 4, (pageHeight * step.milestone) / 100)}px`;
            line.innerHTML = `<span>${step.milestone}% of the page · reached in ${percentOf(step.visits, this.data.homepageVisits)} of visits · <a href="${escapeHtml(step.url)}" target="_top">${step.visits} →</a></span>`;
            this.layer.appendChild(line);
        });
    }

    drawClicks(doc, toDocument) {
        const clicks = this.data.clicks;
        const most = Math.max(1, ...clicks.map((click) => click.clicks));
        const candidates = [...doc.querySelectorAll('a, button, [data-track]')].filter(
            (element) => !element.closest('.zbv-layer, .zbv-fixed') && element.closest('[data-track-section]'),
        );
        const unmatched = [];

        clicks.forEach((click) => {
            // Same rule the tracker used when recording the click: label text + the section it sits in.
            const element = candidates.find(
                (candidate) =>
                    candidate.closest('[data-track-section]').dataset.trackSection === click.section &&
                    readableLabel(candidate) === click.label &&
                    isVisible(candidate),
            );

            if (!element) {
                unmatched.push(`${click.label} (${click.section}) · ${click.clicks} clicks`);

                return;
            }

            const fixed = isInFixedHeader(element);
            const rect = element.getBoundingClientRect();
            const box = fixed ? { top: rect.top, left: rect.left, width: rect.width, height: rect.height } : toDocument(rect);
            const layer = fixed ? this.fixedLayer : this.layer;
            const intensity = Math.sqrt(click.clicks / most);
            const size = Math.round(12 + intensity * 18);

            const outline = paint(doc.createElement('div'), intensity);
            outline.className = 'zbv-outline';
            Object.assign(outline.style, { top: `${box.top - 4}px`, left: `${box.left - 4}px`, width: `${box.width + 8}px`, height: `${box.height + 8}px` });

            const hotspot = paint(doc.createElement('div'), intensity);
            hotspot.className = 'zbv-hotspot';
            // Sits on the element's right edge like a notification badge, so the button text stays readable.
            const nearRightEdge = box.left + box.width > doc.documentElement.clientWidth - 90;
            Object.assign(hotspot.style, { top: `${box.top + box.height / 2}px`, left: `${box.left + box.width}px` });
            hotspot.innerHTML = `
                <div class="zbv-core" style="width:${size}px;height:${size}px">
                    <span class="zbv-ring"></span><span class="zbv-ring"></span><span class="zbv-ring"></span>
                </div>
                <span class="zbv-count${fixed ? ' zbv-count-below' : nearRightEdge ? ' zbv-count-left' : ''}">${click.clicks.toLocaleString()}</span>`;
            hotspot.addEventListener('click', (event) => {
                event.stopPropagation();
                this.openPopover(layer, box, click);
            });

            layer.append(outline, hotspot);
        });

        return unmatched;
    }

    drawFields(doc, toDocument) {
        const unmatched = [];

        this.data.fields.forEach((field) => {
            const input = doc.querySelector(`[data-track-field="${CSS.escape(field.label)}"]`);

            if (!input || !isVisible(input)) {
                unmatched.push(`Form field "${field.label}" · ${field.stopped} gave up here`);

                return;
            }

            const box = toDocument(input.getBoundingClientRect());
            const badge = paint(doc.createElement('a'), field.focused > 0 ? field.stopped / field.focused : 0);
            badge.className = 'zbv-field';
            badge.href = field.url;
            badge.target = '_top';
            Object.assign(badge.style, { top: `${box.top}px`, left: `${box.left + box.width - 8}px`, transform: 'translate(-100%, -50%)' });
            badge.textContent = `${field.focused} clicked in · ${field.stopped} gave up here`;
            this.layer.appendChild(badge);
        });

        return unmatched;
    }

    openPopover(layer, box, click) {
        this.closePopover();

        const popover = layer.ownerDocument.createElement('div');
        popover.className = 'zbv-popover';
        Object.assign(popover.style, {
            top: `${box.top + box.height + 14}px`,
            left: `${Math.max(8, Math.min(box.left, layer.ownerDocument.documentElement.clientWidth - 290))}px`,
        });
        popover.innerHTML = `
            <small>${escapeHtml(click.section)}</small>
            <b>${escapeHtml(click.label)}</b>
            <div class="zbv-big">${click.clicks.toLocaleString()} clicks</div>
            <small>by ${click.visits.toLocaleString()} visits · ${percentOf(click.visits, this.data.homepageVisits)} of homepage visits</small>
            <p style="margin-top:10px"><a href="${escapeHtml(click.url)}" target="_top">See these visits →</a></p>`;
        layer.appendChild(popover);
        this.popover = popover;
    }

    closePopover() {
        this.popover?.remove();
        this.popover = null;
    }

    renderUnmatched(unmatched) {
        if (!this.unmatchedList) {
            return;
        }

        this.unmatchedList.closest('[data-visual-unmatched-wrapper]').hidden = unmatched.length === 0;
        this.unmatchedList.innerHTML = unmatched.map((item) => `<li>${escapeHtml(item)}</li>`).join('');
    }
}

document.querySelectorAll('[data-visual-analytics]').forEach((root) => new VisualAnalyticsOverlay(root));
