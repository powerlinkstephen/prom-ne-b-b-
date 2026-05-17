/**
 * Promène Bébé — interactions UI (hero slider, recherche, menu mobile).
 *
 * Pas de dépendance externe. Vanilla JS, défère.
 */

(function () {
    'use strict';

    var SVG_NS = 'http://www.w3.org/2000/svg';

    /**
     * Crée un élément SVG avec attributs et enfants <line> simples.
     * @param {{viewBox: string, lines: Array<[number,number,number,number]>}} spec
     */
    function makeSvgIcon(spec) {
        var svg = document.createElementNS(SVG_NS, 'svg');
        svg.setAttribute('aria-hidden', 'true');
        svg.setAttribute('viewBox', spec.viewBox);
        svg.setAttribute('fill', 'none');
        svg.setAttribute('stroke', 'currentColor');
        svg.setAttribute('stroke-width', '2');
        svg.setAttribute('stroke-linecap', 'round');
        svg.setAttribute('stroke-linejoin', 'round');
        (spec.lines || []).forEach(function (coords) {
            var line = document.createElementNS(SVG_NS, 'line');
            line.setAttribute('x1', String(coords[0]));
            line.setAttribute('y1', String(coords[1]));
            line.setAttribute('x2', String(coords[2]));
            line.setAttribute('y2', String(coords[3]));
            svg.appendChild(line);
        });
        return svg;
    }

    /* =========================================================
     * 1. HERO SLIDER (scroll-snap + boutons + dots)
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

        /* Détection de la diapositive courante quand l'utilisateur scrolle (tactile, trackpad). */
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

        /* Navigation clavier : ←/→ */
        root.addEventListener('keydown', function (e) {
            if (e.target.closest('input, textarea, button, a')) return;
            if (e.key === 'ArrowLeft')  { scrollToIndex(current - 1); e.preventDefault(); }
            if (e.key === 'ArrowRight') { scrollToIndex(current + 1); e.preventDefault(); }
        });

        updateUI();
    }

    /* =========================================================
     * 2. RECHERCHE TOGGLE
     * ========================================================= */
    function initSearchToggle() {
        var toggle = document.querySelector('[data-pb-search-toggle]');
        var panel  = document.getElementById('pb-search');
        if (!toggle || !panel) return;

        toggle.addEventListener('click', function () {
            var isOpen = !panel.hidden;
            panel.hidden = isOpen;
            toggle.setAttribute('aria-expanded', String(!isOpen));
            if (!isOpen) {
                var input = panel.querySelector('input[type="search"]');
                if (input) input.focus();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !panel.hidden) {
                panel.hidden = true;
                toggle.setAttribute('aria-expanded', 'false');
                toggle.focus();
            }
        });
    }

    /* =========================================================
     * 3. MENU MOBILE (drawer)
     * ========================================================= */
    function initMobileMenu() {
        var toggle = document.querySelector('[data-pb-menu-toggle]');
        if (!toggle) return;

        var sourceNav = document.querySelector('.pb-header__nav');
        if (!sourceNav) return;

        var drawer = document.createElement('aside');
        drawer.id = 'pb-mobile-menu';
        drawer.className = 'pb-mobile-menu';
        drawer.setAttribute('aria-hidden', 'true');
        drawer.setAttribute('aria-label', 'Menu de navigation');

        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'pb-iconbtn pb-mobile-menu__close';
        closeBtn.setAttribute('aria-label', 'Fermer le menu');
        closeBtn.appendChild(makeSvgIcon({
            viewBox: '0 0 24 24',
            lines: [
                [18, 6, 6, 18],
                [6,  6, 18, 18]
            ]
        }));
        drawer.appendChild(closeBtn);

        var nav = sourceNav.cloneNode(true);
        nav.removeAttribute('aria-label');
        drawer.appendChild(nav);

        var backdrop = document.createElement('div');
        backdrop.className = 'pb-backdrop';

        document.body.appendChild(backdrop);
        document.body.appendChild(drawer);

        function open() {
            drawer.setAttribute('aria-hidden', 'false');
            backdrop.setAttribute('data-visible', 'true');
            toggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
            closeBtn.focus();
        }
        function close() {
            drawer.setAttribute('aria-hidden', 'true');
            backdrop.removeAttribute('data-visible');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
            toggle.focus();
        }

        toggle.addEventListener('click', open);
        closeBtn.addEventListener('click', close);
        backdrop.addEventListener('click', close);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && drawer.getAttribute('aria-hidden') === 'false') close();
        });
    }

    /* =========================================================
     * 4. SOMMAIRE AUTOMATIQUE (TOC)
     *
     * Construit dynamiquement à partir des H2/H3 du corps d'article.
     * Ajoute des IDs stables aux titres, peuple [data-pb-toc-list],
     * met en évidence le titre courant avec IntersectionObserver.
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

        list.textContent = ''; /* retire le placeholder */
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

        /* Scroll-spy : surligne le titre actif. */
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
        }, {
            rootMargin: '-30% 0px -60% 0px',
            threshold: 0
        });

        headings.forEach(function (h) { observer.observe(h); });
    }

    /* =========================================================
     * BOOTSTRAP
     * ========================================================= */
    function boot() {
        document.querySelectorAll('[data-pb-slider]').forEach(initSlider);
        initSearchToggle();
        initMobileMenu();
        initToc();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
