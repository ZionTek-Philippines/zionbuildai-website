// English / Taglish switch for the demo's explanations. The app screens themselves stay in English, like the real app.
const STORAGE_KEY = 'zionbuild-demo-lang';

export const getLang = () => (document.documentElement.dataset.demoLang === 'tl' ? 'tl' : 'en');

// Pick the copy for the current language from {en, tl}; plain strings pass through.
export const translate = (copy) => (typeof copy === 'string' ? copy : (copy?.[getLang()] ?? copy?.en));

function setLang(lang) {
    document.documentElement.dataset.demoLang = lang;
    renderToggles();

    try {
        localStorage.setItem(STORAGE_KEY, lang);
    } catch {
        // Blocked storage: the choice just lasts for this page.
    }

    document.dispatchEvent(new CustomEvent('demo:lang', { detail: { lang } }));
}

function renderToggles() {
    document.querySelectorAll('[data-demo-lang-set]').forEach((button) => {
        button.setAttribute('aria-pressed', String(button.dataset.demoLangSet === getLang()));
    });
}

export function initDemoLang() {
    const buttons = document.querySelectorAll('[data-demo-lang-set]');

    if (!buttons.length) {
        return;
    }

    buttons.forEach((button) => button.addEventListener('click', () => setLang(button.dataset.demoLangSet)));
    renderToggles();
}
