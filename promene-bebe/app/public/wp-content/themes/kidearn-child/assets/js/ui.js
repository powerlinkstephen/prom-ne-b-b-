/**
 * Promène Bébé — interactions UI.
 *
 * 1. Hero slider scroll-snap (front-page.php)
 * 2. Sommaire automatique d'article (single.php)
 * 3. Injection d'une icône de recherche dans le header Kidearn + overlay
 *
 * Pas de dépendance externe. Vanilla JS, défère.
 */

(function () {
    'use strict';

    var SVG_NS = 'http://www.w3.org/2000/svg';

    /* =========================================================
     * Utilitaires SVG
     * ========================================================= */
    function makeSvg(viewBox, children) {
        var svg = document.createElementNS(SVG_NS, 'svg');
        svg.setAttribute('aria-hidden', 'true');
        svg.setAttribute('viewBox', viewBox);
        svg.setAttribute('fill', 'none');
        svg.setAttribute('stroke', 'currentColor');
        svg.setAttribute('stroke-width', '2');
        svg.setAttribute('stroke-linecap', 'round');
        svg.setAttribute('stroke-linejoin', 'round');
        (children || []).forEach(function (c) { svg.appendChild(c); });
        return svg;
    }
    function svgEl(tag, attrs) {
        var el = document.createElementNS(SVG_NS, tag);
        Object.keys(attrs || {}).forEach(function (k) { el.setAttribute(k, String(attrs[k])); });
        return el;
    }

    /* =========================================================
     * 1. HERO SLIDER
     * ========================================================= */
    function initSlider(root) {
        var track    = root.querySelector('[data-pb-slider-track]');
        var slides   = track ? track.querySelectorAll('.pb-hero__slide') : [];
        var prevBtn  = root.querySelector('[data-pb-slider-prev]');
        var nextBtn  = root.querySelector('[data-pb-slider-next]');
        var dotsRoot = root.querySelector('[data-pb-slider-dots]');
        var dots     = dotsRoot ? dotsRoot.querySelectorAll('[data-pb-slider-dot]') : [];

        if (!track || slides.length < 2) {
            if (prevBtn) prevBtn.hidden = true;
            if (nextBtn) nextBtn.hidden = true;
            if (dotsRoot) dotsRoot.hidden = true;
            return;
        }

        var current = 0;

        function scrollToIndex(idx) {
            if (idx < 0) idx = 0;
            if (idx > slides.length - 1) idx = slides.length - 1;
            var target = slides[idx];
            if (!target) return;
            track.scrollTo({ left: target.offsetLeft - track.offsetLeft, behavior: 'smooth' });
            current = idx;
            updateUI();
        }
        function updateUI() {
            if (prevBtn) prevBtn.disabled = current === 0;
            if (nextBtn) nextBtn.disabled = current === slides.length - 1;
            dots.forEach(function (dot, i) {
                if (i === current) dot.setAttribute('aria-current', 'true');
                else dot.removeAttribute('aria-current');
            });
        }

        if (prevBtn) prevBtn.addEventListener('click', function () { scrollToIndex(current - 1); });
        if (nextBtn) nextBtn.addEventListener('click', function () { scrollToIndex(current + 1); });

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                scrollToIndex(parseInt(dot.getAttribute('data-pb-slider-dot'), 10));
            });
        });

        var scrollTimeout = null;
        track.addEventListener('scroll', function () {
            if (scrollTimeout) cancelAnimationFrame(scrollTimeout);
            scrollTimeout = requestAnimationFrame(function () {
                var center = track.scrollLeft + track.clientWidth / 2;
                var closest = 0;
                var minDist = Infinity;
                slides.forEach(function (slide, i) {
                    var slideCenter = slide.offsetLeft + slide.offsetWidth / 2 - track.offsetLeft;
                    var dist = Math.abs(center - slideCenter);
                    if (dist < minDist) { minDist = dist; closest = i; }
                });
                if (closest !== current) { current = closest; updateUI(); }
            });
        }, { passive: true });

        root.addEventListener('keydown', function (e) {
            if (e.target.closest('input, textarea, button, a')) return;
            if (e.key === 'ArrowLeft')  { scrollToIndex(current - 1); e.preventDefault(); }
            if (e.key === 'ArrowRight') { scrollToIndex(current + 1); e.preventDefault(); }
        });

        updateUI();
    }

    /* =========================================================
     * 2. SOMMAIRE AUTOMATIQUE
     * ========================================================= */
    function slugify(str) {
        return String(str || '')
            .toLowerCase()
            .normalize('NFD').replace(/[̀-ͯ]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .slice(0, 80) || 'section';
    }
    function ensureUniqueId(el, used) {
        var base = el.id || slugify(el.textContent);
        var id   = base;
        var n    = 2;
        while (used[id]) { id = base + '-' + (n++); }
        used[id] = true;
        el.id = id;
        return id;
    }
    function initToc() {
        var body = document.querySelector('[data-pb-article-body]');
        var list = document.querySelector('[data-pb-toc-list]');
        var rootTOC = document.querySelector('[data-pb-toc]');
        if (!body || !list) return;

        var headings = body.querySelectorAll('h2, h3');
        if (headings.length < 2) {
            if (rootTOC) rootTOC.hidden = true;
            return;
        }

        list.textContent = '';
        var used = {};
        headings.forEach(function (h) {
            ensureUniqueId(h, used);
            var li = document.createElement('li');
            if (h.tagName === 'H3') li.classList.add('pb-toc__sub');
            var a  = document.createElement('a');
            a.href = '#' + h.id;
            a.textContent = h.textContent;
            a.dataset.target = h.id;
            li.appendChild(a);
            list.appendChild(li);
        });

        if (!('IntersectionObserver' in window)) return;
        var links = list.querySelectorAll('a[data-target]');
        var byId  = {};
        links.forEach(function (a) { byId[a.dataset.target] = a; });
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                var link = byId[entry.target.id];
                if (!link) return;
                if (entry.isIntersecting) {
                    links.forEach(function (l) { l.removeAttribute('data-active'); });
                    link.setAttribute('data-active', 'true');
                }
            });
        }, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });
        headings.forEach(function (h) { observer.observe(h); });
    }

    /* =========================================================
     * 3. INJECTION D'UNE RECHERCHE DANS LE HEADER KIDEARN
     *
     * Le header par défaut de Kidearn ne contient pas d'icône de recherche.
     * On en ajoute une dans `.main-header__inner` qui ouvre un overlay
     * plein écran avec un formulaire `<form action="/" ?s=…>`.
     * ========================================================= */
    function buildSearchIcon() {
        return makeSvg('0 0 24 24', [
            svgEl('circle', { cx: 11, cy: 11, r: 7 }),
            svgEl('line', { x1: 21, y1: 21, x2: 16.65, y2: 16.65 })
        ]);
    }
    function buildCloseIcon() {
        return makeSvg('0 0 24 24', [
            svgEl('line', { x1: 18, y1: 6,  x2: 6,  y2: 18 }),
            svgEl('line', { x1: 6,  y1: 6,  x2: 18, y2: 18 })
        ]);
    }

    function initSearch() {
        var headerInner = document.querySelector('.main-header__inner, .main-header');
        if (!headerInner) return;

        /* Icône bouton */
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'pb-search-trigger';
        btn.setAttribute('aria-label', 'Ouvrir la recherche');
        btn.appendChild(buildSearchIcon());
        headerInner.appendChild(btn);

        /* Overlay */
        var overlay = document.createElement('div');
        overlay.className = 'pb-search-overlay';
        overlay.setAttribute('aria-hidden', 'true');

        var form = document.createElement('form');
        form.action = (window.location.origin || '') + '/';
        form.method = 'get';
        form.role = 'search';

        var label = document.createElement('label');
        label.className = 'pb-sr-only';
        label.setAttribute('for', 'pb-search-input');
        label.textContent = 'Rechercher sur Promène Bébé';

        var input = document.createElement('input');
        input.id = 'pb-search-input';
        input.type = 'search';
        input.name = 's';
        input.placeholder = 'Rechercher une poussette, un guide, un comparatif…';
        input.autocomplete = 'off';

        var submit = document.createElement('button');
        submit.type = 'submit';
        submit.textContent = 'Rechercher';

        var close = document.createElement('button');
        close.type = 'button';
        close.className = 'pb-search-overlay__close';
        close.setAttribute('aria-label', 'Fermer la recherche');
        close.appendChild(buildCloseIcon());

        form.appendChild(label);
        form.appendChild(input);
        form.appendChild(submit);
        overlay.appendChild(form);
        overlay.appendChild(close);
        document.body.appendChild(overlay);

        function open() {
            overlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            setTimeout(function () { input.focus(); }, 40);
        }
        function shut() {
            overlay.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            btn.focus();
        }

        btn.addEventListener('click', open);
        close.addEventListener('click', shut);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) shut(); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.getAttribute('aria-hidden') === 'false') shut();
        });
    }

    /* =========================================================
     * BOOTSTRAP
     * ========================================================= */
    function boot() {
        document.querySelectorAll('[data-pb-slider]').forEach(initSlider);
        initToc();
        initSearch();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
