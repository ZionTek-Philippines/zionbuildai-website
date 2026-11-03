import { translate } from './demo-lang';

const TOAST_MS = 3600;

let toastTimer = null;

// message: a string or {en, tl}
export function showDemoToast(message) {
    const toast = document.querySelector('[data-demo-toast]');

    if (!toast) {
        return;
    }

    toast.textContent = translate(message);
    toast.classList.remove('hidden');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.add('hidden'), TOAST_MS);
}

export function initDemoToasts() {
    document.querySelectorAll('[data-demo-toast-message]').forEach((trigger) => {
        trigger.addEventListener('click', () => showDemoToast({ en: trigger.dataset.demoToastMessage, tl: trigger.dataset.demoToastMessageTl }));
    });
}

export function initFieldReports() {
    const tabs = document.querySelectorAll('[data-report-tab]');

    if (!tabs.length) {
        return;
    }

    // Tabs: Daily Work Logs / Progress Updates
    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const key = tab.dataset.reportTab;

            tabs.forEach((other) => other.setAttribute('aria-selected', String(other === tab)));
            document.querySelectorAll('[data-report-panel]').forEach((panel) => panel.classList.toggle('hidden', panel.dataset.reportPanel !== key));
            document.querySelectorAll('[data-report-panel-label]').forEach((label) => label.classList.toggle('hidden', label.dataset.reportPanelLabel !== key));
            clearSelection();
        });
    });

    // Show / hide from client
    const sharedCount = document.querySelector('[data-shared-count]');

    document.querySelectorAll('[data-client-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const card = toggle.closest('[data-report-card]');
            const isVisible = !card.querySelector('[data-when-visible]').hidden;

            card.querySelectorAll('[data-when-visible]').forEach((node) => (node.hidden = isVisible));
            card.querySelectorAll('[data-when-hidden]').forEach((node) => (node.hidden = !isVisible));

            if (card.dataset.thisWeek === '1') {
                sharedCount.textContent = String(Number(sharedCount.textContent) + (isVisible ? -1 : 1));
            }

            showDemoToast(isVisible
                ? { en: 'Hidden. The homeowner no longer sees this in their Client Portal.', tl: 'Naka-hide na. Hindi na ito makikita ng homeowner sa Client Portal.' }
                : { en: 'Shared. The homeowner can now see this in their Client Portal.', tl: 'Na-share na. Makikita na ito ng homeowner sa Client Portal.' });
        });
    });

    // Merge progress updates
    const mergeBar = document.querySelector('[data-merge-bar]');
    const mergeCount = document.querySelector('[data-merge-count]');
    const checkboxes = document.querySelectorAll('[data-report-select]');
    const selectedCount = () => [...checkboxes].filter((checkbox) => checkbox.checked).length;

    function renderMergeBar() {
        const count = selectedCount();
        mergeCount.textContent = String(count);
        mergeBar.classList.toggle('hidden', count === 0);
        mergeBar.classList.toggle('flex', count > 0);
    }

    function clearSelection() {
        checkboxes.forEach((checkbox) => (checkbox.checked = false));
        renderMergeBar();
    }

    checkboxes.forEach((checkbox) => checkbox.addEventListener('change', renderMergeBar));
    document.querySelector('[data-merge-clear]').addEventListener('click', clearSelection);
    document.querySelector('[data-merge-action]').addEventListener('click', () => {
        if (selectedCount() < 2) {
            showDemoToast({ en: 'Select at least two updates to merge.', tl: 'Pumili ng hindi bababa sa dalawang updates para i-merge.' });

            return;
        }

        showDemoToast({
            en: `In the real app, these ${selectedCount()} updates become one combined report you can share with the client.`,
            tl: `Sa totoong app, magiging isang combined report ang ${selectedCount()} updates na ito na puwede mong i-share sa client.`,
        });
        clearSelection();
    });

    // Worker report form
    const modal = document.querySelector('[data-report-modal]');

    const openModal = () => {
        modal.classList.replace('hidden', 'flex');
    };

    const closeModal = () => {
        modal.classList.replace('flex', 'hidden');
    };

    document.querySelectorAll('[data-report-open]').forEach((button) => button.addEventListener('click', openModal));
    modal.querySelectorAll('[data-report-close]').forEach((button) => button.addEventListener('click', closeModal));
    modal.addEventListener('click', (event) => event.target === modal && closeModal());
    document.addEventListener('keydown', (event) => event.key === 'Escape' && closeModal());

    modal.querySelector('[data-report-form]').addEventListener('submit', (event) => {
        event.preventDefault();
        closeModal();
        showDemoToast({ en: 'Submitted! In the real app, this log and its photos now appear in Field Reports.', tl: 'Na-submit na! Sa totoong app, lalabas na ang log at photos na ito sa Field Reports.' });
    });
}
