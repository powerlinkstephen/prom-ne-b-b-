# Journal BMad — Promène Bébé

Suivi des phases franchies par l'agent en mode autonome, conformément à la méthodologie BMad (CDC § 9).

## Phase 0 — Audit et prérequis (✅ 2026-05-17)

Vérifications réalisées :

- ✅ CDC `CDC_PromeneBebe_FINAL.docx` lu intégralement (692 lignes après conversion txt)
- ✅ Structure projet vérifiée vs CDC § 14
- ✅ Assets reçus (7 PNG) normalisés en slugs kebab-case dans `assets/`
- ✅ `IDENTIFIANTS.md` présent, gitignoré
- ⚠️ **og-default 1200×630 manquant** — fallback temporaire avec `logo-badge-1125.png` carré
- ⚠️ **Logo SVG manquant** — utilisation des PNG transparents (pixelisation aux très grandes tailles)
- ℹ️ BMad = skills Claude Code (pas plugin WordPress) — confirmé par le propriétaire

Décisions prises sans validation (CDC autorise les défauts raisonnables) :

| Décision | Raison |
|---|---|
| Polices : **Quicksand** (titres) + **Inter** (corps) | Choix moderne et performant parmi les options CDC, variable fonts, couverture FR complète |
| Mode sombre : palette dérivée du vert sapin assombri + rose pastel pour titres | Conserve l'identité tout en assurant un contraste WCAG AA |
| Newsletter : forme neutre hook-based | Service non encore choisi par le propriétaire, intégration différée |
| Slider hero : CSS scroll-snap + JS vanilla | Pas de dépendance externe, perf et accessibilité maximales |
| Affilizz : styling défensif via sélecteurs `[class*="..."]` | Plugin pas encore installé, prêt à appliquer la palette dès activation |

## Phase 1 — Fondations du thème enfant (✅ 2026-05-17)

- ✅ `style.css` propre (header WordPress)
- ✅ `assets/css/main.css` — design system complet (variables, typo, composants, header, footer, hero, article, dark mode)
- ✅ `assets/js/theme.js` — toggle mode sombre + localStorage + anti-FOUC
- ✅ `functions.php` refactorisé : enqueue ordonné, helpers, performance, hardening léger
- ✅ Favicon hook (fallback si pas de Site Icon)
- ✅ Helper `promenebebe_logo_url()` qui respecte le custom logo WP s'il existe

## Phase 2 — Templates Home et structure générale (✅ 2026-05-17)

- ✅ `header.php` — header sticky, logo, menu, recherche toggle, dark toggle, menu mobile
- ✅ `footer.php` — newsletter + nav + zone légale
- ✅ `front-page.php` — hero slider + grille articles
- ✅ `index.php` — fallback générique pagination incluse
- ✅ `searchform.php` — formulaire de recherche stylé
- ✅ `template-parts/hero-slider.php` — slider accessible (scroll-snap + boutons + dots + ARIA)
- ✅ `template-parts/card.php` — carte article
- ✅ `template-parts/newsletter.php` — form newsletter neutre

## Phase 3 — Template Article (✅ 2026-05-17)

- ✅ `single.php` — breadcrumbs + meta + cover + layout TOC/body + share + bio + similaires + commentaires
- ✅ `template-parts/breadcrumbs.php` — fil d'Ariane + microdonnées Schema.org BreadcrumbList
- ✅ `template-parts/share.php` — Facebook + Pinterest + WhatsApp (URL d'intent, zéro JS tiers)
- ✅ `template-parts/related.php` — articles similaires aléatoires de la même catégorie
- ✅ TOC automatique JS : génère IDs uniques + scroll-spy via IntersectionObserver
- ✅ Styling Affilizz défensif prêt à l'emploi (palette appliquée via `!important` sur sélecteurs génériques)

## Phase 4 — Templates archive et catégories (✅ 2026-05-17)

- ✅ `archive.php` — catégorie/tag/auteur/date
- ✅ `page.php` — pages institutionnelles
- ✅ Commandes wp-cli prêtes pour création des 5 catégories placeholder (CDC § 8.3)

## Phase 5 — Newsletter discrète (✅ 2026-05-17)

- ✅ Form footer + fin d'article via `template-parts/newsletter.php`
- ✅ Hook `promenebebe_newsletter_action` pour brancher le service quand il sera choisi
- ✅ Hook `promenebebe_newsletter_fields` pour adapter au mapping fournisseur
- ✅ Lien automatique vers `/politique-de-confidentialite/`
- ✅ Pas de popup, pas de tracker tiers tant que le service n'est pas branché

## Phase 6 — Plugins WordPress (✅ 2026-05-17, install pending)

- ✅ `docs/setup/plugins-install.md` — liste exhaustive avec commandes wp-cli
- ⏳ Installation effective : nécessite la "Site shell" LocalWP du propriétaire
- ✅ Substituts gratuits documentés (LiteSpeed/W3 Total Cache vs WP Rocket, EWWW vs Imagify)

## Phase 7 — Recherche (✅ 2026-05-17)

- ✅ Icône recherche dans le header (toggle clavier + souris)
- ✅ `searchform.php` stylé avec autocompletion désactivée
- ⏳ Pertinence améliorée via Relevanssi à activer après install plugin

## Phase 8 — Pages légales rédigées intégralement (✅ 2026-05-17)

- ✅ `docs/legal/mentions-legales.html` — 7 sections complètes (éditeur, directeur publication, hébergement o2switch détaillé, propriété intellectuelle, liens hypertextes, limitation de responsabilité, droit applicable)
- ✅ `docs/legal/politique-de-confidentialite.html` — 13 sections RGPD complètes (préambule, responsable, données collectées détaillées, finalités, base légale, durées, destinataires, transferts hors UE, droits utilisateurs avec CNIL, cookies, sécurité, modifications, contact)
- ✅ `docs/legal/divulgation-affiliation.html` — 8 sections (engagement transparence, explication affiliation, méthodologie sélection, programmes utilisés avec mention Amazon obligatoire, reconnaissance liens, indépendance éditoriale, soutien lecteurs, contact)
- ✅ `docs/setup/import-legal-pages.sh` — script idempotent d'import via wp-cli
- ℹ️ Mentions `[À COMPLÉTER PAR LE PROPRIÉTAIRE]` clairement identifiées

## Phase 9 — SEO technique et Schema.org (✅ 2026-05-17)

- ✅ `inc/seo.php` — détection plugin SEO concurrent + sortie défensive si rien d'installé
- ✅ Open Graph complet (type, locale, site_name, title, description, url, image, article:* pour les posts)
- ✅ Twitter Cards (summary_large_image)
- ✅ JSON-LD `@graph` : Organization + WebSite + Article + BreadcrumbList
- ✅ Filter `robots_txt` qui ajoute le sitemap
- ✅ Heartbeat frontend désactivé
- ✅ Image OG par défaut filtrable (`promenebebe_default_og_image`)

## Phase 10 — Hardening sécurité (✅ 2026-05-17)

- ✅ `inc/security.php` — modules de sécurité indépendants
- ✅ Headers HTTP : `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`
- ✅ Blocage `?author=N` (anti énumération users)
- ✅ Restriction `/wp-json/wp/v2/users` aux utilisateurs authentifiés
- ✅ Suppression RSD / wlwmanifest / shortlink des `<head>`
- ✅ Messages d'erreur de login génériques
- ✅ `docs/setup/security-checklist.md` — actions complémentaires côté hébergeur / plugins

## Phase 11 — Documentation finale (en cours)

- ✅ `docs/README.md` — index
- ✅ `docs/01-theme-architecture.md` — vue d'ensemble du child theme
- ✅ `docs/03-owner-guide.md` — guide quotidien pour le propriétaire
- ✅ `docs/04-bmad-progress.md` — ce fichier
- ⏳ `docs/02-customization-guide.md` — guide de personnalisation avancée (palette, typo, composants)

## Reste à faire (hors agent — actions propriétaire)

| # | Action | Bloquant pour | Owner |
|---|---|---|---|
| 1 | Activer le thème `kidearn-child` via wp-admin ou wp-cli | Test visuel | Propriétaire |
| 2 | Exécuter les commandes `setup/wp-cli-commands.md` § 2-7 | Catégories, menus, articles démo | Propriétaire |
| 3 | Installer les plugins (`setup/plugins-install.md`) | Cache, sécurité, SEO, affiliation | Propriétaire |
| 4 | Importer les pages légales (`setup/import-legal-pages.sh`) | Conformité RGPD | Propriétaire |
| 5 | Compléter les mentions `[À COMPLÉTER]` dans les 3 pages légales | Mise en production | Propriétaire |
| 6 | Fournir un logo SVG vectoriel | Qualité affichage grand format | Propriétaire |
| 7 | Fournir une image `og-default.png` au ratio 1200×630 | Partages sociaux optimaux | Propriétaire |
| 8 | Choisir un service newsletter (MailerLite vs Brevo) | Activation formulaire | Propriétaire |
| 9 | Compléter `IDENTIFIANTS.md` (cPanel o2switch, GA4, Search Console, Akismet, Affilizz, newsletter) | Configuration plugins | Propriétaire |
| 10 | Test visuel complet en local + correction itérative | Validation P1–P5 | Propriétaire (avec agent) |
| 11 | Audit PageSpeed + Core Web Vitals sur la home et un article | Critère CDC § 10.2 | Propriétaire (avec agent) |
| 12 | Test mobile (320px → desktop large) sur Chrome / Firefox / Safari / iOS Safari | Critère CDC § 16.4 | Propriétaire (avec agent) |
| 13 | Rotation des salts `wp-config.php` avant le passage en production | Sécurité go-live | Propriétaire |
