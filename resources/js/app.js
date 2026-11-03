import { initAiMonitoring, initDemoTabs } from './demo-ai-monitoring';
import { initFinances } from './demo-finances';
import { initPayroll } from './demo-payroll';
import { initFieldReports, initDemoToasts } from './demo-field-reports';
import { initDemoFrameLinks, initDemoGuide, initDemoNav } from './demo-guide';
import { initDemoLang } from './demo-lang';
import { initMaskedText } from './masked-text';
import { initSiteAnalytics } from './site-analytics';

function playWithSoundFallback(video) {
    video.muted = false;

    video.play().catch(() => {
        video.muted = true;
        video.play().catch(() => {});

        document.addEventListener('pointerdown', () => (video.muted = false), { once: true });
    });
}

function initFeatureShowcase() {
    const showcase = document.querySelector('[data-feature-showcase]');

    if (!showcase) {
        return;
    }

    const tabs = showcase.querySelectorAll('[data-feature-tab]');
    const panels = showcase.querySelectorAll('[data-feature-panel]');
    const title = showcase.querySelector('[data-feature-title]');

    const activePanelVideos = () => showcase.querySelectorAll('[data-feature-panel]:not(.hidden) video');

    new IntersectionObserver(
        ([entry]) => {
            activePanelVideos().forEach((video) => (entry.isIntersecting ? playWithSoundFallback(video) : video.pause()));
        },
        { threshold: 0.5 },
    ).observe(showcase);

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const key = tab.dataset.featureTab;

            tabs.forEach((otherTab) => otherTab.setAttribute('aria-selected', String(otherTab === tab)));

            panels.forEach((panel) => {
                const isActive = panel.dataset.featurePanel === key;
                panel.classList.toggle('hidden', !isActive);

                if (!isActive) {
                    panel.querySelectorAll('video').forEach((video) => video.pause());
                }

                if (isActive) {
                    title.textContent = panel.dataset.previewTitle;
                    panel.querySelectorAll('video').forEach(playWithSoundFallback);
                }
            });
        });
    });
}

function initFaqAccordion() {
    const toggles = document.querySelectorAll('[data-faq] [data-faq-toggle]');

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const wasExpanded = toggle.getAttribute('aria-expanded') === 'true';

            toggles.forEach((otherToggle) => {
                otherToggle.setAttribute('aria-expanded', 'false');
                document.getElementById(otherToggle.getAttribute('aria-controls')).classList.add('hidden');
            });

            if (!wasExpanded) {
                toggle.setAttribute('aria-expanded', 'true');
                document.getElementById(toggle.getAttribute('aria-controls')).classList.remove('hidden');
            }
        });
    });
}

function initTryItModal() {
    const modal = document.querySelector('[data-try-it-modal]');

    if (!modal) {
        return;
    }

    const frame = modal.querySelector('iframe');

    const open = () => {
        if (!frame.src) {
            frame.src = frame.dataset.src;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    const close = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    };

    document.querySelectorAll('[data-try-it]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            open();
        });
    });

    modal.addEventListener('click', (event) => event.target === modal && close());
    document.addEventListener('keydown', (event) => event.key === 'Escape' && close());

    window.addEventListener('message', (event) => {
        if (event.origin !== window.location.origin) {
            return;
        }

        if (event.data?.type === 'zionbuild-demo:close') {
            close();
        }

        if (event.data?.type === 'zionbuild-demo:book') {
            close();

            // Only the homepage has the booking form; other pages send the visitor there.
            const bookingForm = document.getElementById('demo');
            bookingForm ? bookingForm.scrollIntoView({ behavior: 'smooth' }) : window.location.assign('/#demo');
        }
    });
}

initMaskedText();
initFeatureShowcase();
initFaqAccordion();
initTryItModal();
initDemoLang();
initDemoGuide();
initDemoFrameLinks();
initDemoNav();
initDemoToasts();
initFieldReports();
initDemoTabs();
initAiMonitoring();
initFinances();
initPayroll();
initSiteAnalytics();
