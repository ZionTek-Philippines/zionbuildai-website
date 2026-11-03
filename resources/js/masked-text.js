/**
 * Fill in text that the server sends base64-encoded and reversed (data-masked),
 * so names on public pages are readable by people but absent from the raw HTML.
 */
export function initMaskedText() {
    document.querySelectorAll('[data-masked]').forEach((element) => {
        const bytes = Uint8Array.from(atob(element.dataset.masked), (character) => character.charCodeAt(0));

        element.textContent = [...new TextDecoder().decode(bytes)].reverse().join('');
        element.removeAttribute('data-masked');
    });
}
