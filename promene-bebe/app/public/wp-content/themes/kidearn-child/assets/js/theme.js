/**
 * Promène Bébé — script principal du thème enfant.
 *
 * Périmètre :
 *   - Bascule mode sombre / mode clair avec persistance localStorage
 *   - Préférence système (prefers-color-scheme) en fallback
 *   - Hook anti-flash (FOUC) appliqué très tôt via inline-script dans wp_head
 *     (voir functions.php / promenebebe_inline_theme_init).
 *   - API publique : window.PB.setTheme('light'|'dark'|'auto')
 */

(function () {
    'use strict';

    var STORAGE_KEY = 'pb-theme';
    var THEME_LIGHT = 'light';
    var THEME_DARK  = 'dark';
    var html        = document.documentElement;

    /**
     * Retourne la préférence système courante.
     */
    function systemPrefers() {
        return (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)
            ? THEME_DARK
            : THEME_LIGHT;
    }

    /**
     * Applique un thème ('light' | 'dark') sur le document.
     */
    function applyTheme(theme) {
        if (theme !== THEME_DARK) theme = THEME_LIGHT;
        html.setAttribute('data-theme', theme);
        updateToggleButtons(theme);
    }

    /**
     * Met à jour le rendu des boutons de bascule (aria + icônes).
     */
    function updateToggleButtons(theme) {
        var buttons = document.querySelectorAll('[data-pb-theme-toggle]');
        buttons.forEach(function (btn) {
            var isDark = theme === THEME_DARK;
            btn.setAttribute('aria-pressed', String(isDark));
            btn.setAttribute(
                'aria-label',
                isDark ? 'Activer le mode clair' : 'Activer le mode sombre'
            );
        });
    }

    /**
     * Initialise depuis le storage ou la préférence système.
     */
    function init() {
        var stored = null;
        try { stored = localStorage.getItem(STORAGE_KEY); } catch (e) { /* storage bloqué */ }

        var theme = stored === THEME_DARK || stored === THEME_LIGHT
            ? stored
            : systemPrefers();

        applyTheme(theme);

        // Permet les transitions douces seulement après l'init pour éviter le flash.
        requestAnimationFrame(function () {
            html.classList.add('pb-theme-ready');
        });
    }

    /**
     * Bascule manuelle.
     */
    function toggle() {
        var next = html.getAttribute('data-theme') === THEME_DARK ? THEME_LIGHT : THEME_DARK;
        try { localStorage.setItem(STORAGE_KEY, next); } catch (e) { /* storage bloqué */ }
        applyTheme(next);
    }

    /**
     * Câblage des boutons de bascule (utilise délégation).
     */
    function bindToggles() {
        document.addEventListener('click', function (ev) {
            var btn = ev.target.closest('[data-pb-theme-toggle]');
            if (!btn) return;
            ev.preventDefault();
            toggle();
        });
    }

    /**
     * Suit la préférence système si l'utilisateur n'a pas explicitement choisi.
     */
    function watchSystem() {
        if (!window.matchMedia) return;
        var mq = window.matchMedia('(prefers-color-scheme: dark)');
        var handler = function (e) {
            var stored = null;
            try { stored = localStorage.getItem(STORAGE_KEY); } catch (err) { /* storage bloqué */ }
            if (stored !== THEME_DARK && stored !== THEME_LIGHT) {
                applyTheme(e.matches ? THEME_DARK : THEME_LIGHT);
            }
        };
        mq.addEventListener('change', handler);
    }

    // API publique minimaliste
    window.PB = window.PB || {};
    window.PB.setTheme = function (theme) {
        if (theme === 'auto') {
            try { localStorage.removeItem(STORAGE_KEY); } catch (e) { /* storage bloqué */ }
            applyTheme(systemPrefers());
            return;
        }
        if (theme !== THEME_DARK && theme !== THEME_LIGHT) return;
        try { localStorage.setItem(STORAGE_KEY, theme); } catch (e) { /* storage bloqué */ }
        applyTheme(theme);
    };

    // Bootstrap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            init();
            bindToggles();
            watchSystem();
        });
    } else {
        init();
        bindToggles();
        watchSystem();
    }
})();
