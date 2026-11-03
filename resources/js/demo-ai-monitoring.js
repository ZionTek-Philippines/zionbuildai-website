import { showDemoToast } from './demo-field-reports';

// Sub-tabs on demo pages: [data-demo-tab="key"] buttons switch [data-demo-panel="key"] panels.
export function initDemoTabs() {
    const tabs = document.querySelectorAll('[data-demo-tab]');

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            tabs.forEach((other) => other.setAttribute('aria-selected', String(other === tab)));
            document.querySelectorAll('[data-demo-panel]').forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.demoPanel !== tab.dataset.demoTab);
            });
        });
    });
}

const doneBadge = (text, classes) => {
    const badge = document.createElement('span');
    badge.className = `inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold whitespace-nowrap ${classes}`;
    badge.textContent = text;

    return badge;
};

export function initAiMonitoring() {
    // AI Photo Analysis: accepting a match makes the suggested % official (in the demo, just on screen).
    document.querySelectorAll('[data-accept-match]').forEach((button) => {
        button.addEventListener('click', () => {
            const row = button.closest('[data-match-row]');
            const { recommended, item } = button.dataset;

            row.querySelector('[data-match-current]').innerHTML = `<span class="font-bold text-demo-green">${recommended}%</span>`;
            button.replaceWith(doneBadge('✓ Accepted', 'bg-emerald-50 text-demo-green'));
            document.querySelector('[data-queue-status]')?.replaceWith(doneBadge('Accepted', 'border border-emerald-100 bg-emerald-50 text-demo-green'));

            showDemoToast({
                en: `Accepted. ${item} is now ${recommended}% in the official progress.`,
                tl: `Tinanggap. ${recommended}% na ang ${item} sa official na progress.`,
            });
        });
    });
}
