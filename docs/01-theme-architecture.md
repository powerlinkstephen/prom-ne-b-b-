# Architecture du thème enfant Kidearn Child — Promène Bébé

## Principe général

Le site repose sur le thème **Kidearn** (parent, commercial, non modifié) et un **thème enfant** dédié `kidearn-child` qui porte 100 % de la personnalisation Promène Bébé.

Règle inviolable : **aucune modification du thème parent Kidearn**, ce qui garantit la capacité de mettre à jour le parent sans perdre la moindre personnalisation.

## Arborescence

```
promene-bebe/app/public/wp-content/themes/kidearn-child/
├── style.css                       Header WP du thème (déclaration + métadonnées)
├── functions.php                   Setup, enqueue, helpers globaux, perf
├── searchform.php                  Formulaire de recherche Promène Bébé
├── header.php                      Override : header sticky, logo, menu, search, dark toggle
├── footer.php                      Override : footer avec newsletter + nav + légal
├── front-page.php                  Home : hero slider + grille articles
├── index.php                       Fallback générique (listing articles)
├── archive.php                     Catégories, tags, dates, auteurs
├── page.php                        Pages institutionnelles (mentions, etc.)
├── single.php                      Article : breadcrumbs, TOC, share, similaires
│
├── inc/
│   ├── seo.php                     OG / Twitter Cards / JSON-LD Schema.org / robots.txt
│   └── security.php                Hardening léger (headers HTTP, blocage author=N, etc.)
│
├── template-parts/
│   ├── breadcrumbs.php             Fil d'Ariane + microdonnées BreadcrumbList
│   ├── card.php                    Carte article (utilisée par grilles et similaires)
│   ├── hero-slider.php             Slider d'articles phares (CSS scroll-snap + JS)
│   ├── newsletter.php              Form newsletter neutre (hook-based)
│   ├── related.php                 Bloc "Sur le même sujet"
│   └── share.php                   Boutons Facebook / Pinterest / WhatsApp
│
└── assets/
    ├── img/                        Logo, favicon, image OG par défaut
    ├── css/main.css                Toute la feuille de style Promène Bébé
    └── js/
        ├── theme.js                Bascule mode sombre + anti-FOUC
        └── ui.js                   Hero slider, recherche, menu mobile, TOC
```

## Cycle de rendu d'une page

1. **`wp_head`** (priorité 1) : script inline anti-FOUC qui pose `data-theme` immédiatement
2. **`wp_enqueue_scripts`** : Google Fonts → style parent → style.css enfant → main.css enfant → theme.js → ui.js
3. **`wp_head`** (priorité 5) : `<link rel="icon">` fallback si pas de Site Icon
4. **`wp_head`** (priorité 6) : balises OG et Twitter (si pas de plugin SEO actif)
5. **`wp_head`** (priorité 7) : JSON-LD Schema.org (si pas de plugin SEO actif)
6. **`wp_body_open`** : skip-link accessible vers `#pb-main`
7. Rendu du template adapté (front-page, single, archive, page, index)

## Système de design CSS

Tout est piloté par les variables CSS définies dans `main.css` § 1 :

| Token | Rôle |
|---|---|
| `--pb-primary` (`#18454A`) | Vert sapin — texte, titres, CTA primaire |
| `--pb-secondary` (`#B26494`) | Magenta — accents, liens, CTA secondaire |
| `--pb-pastel` (`#F5DAF9`) | Rose pastel — backgrounds doux, hero |
| `--pb-pastel-light` (`#FBF5FC`) | Rose ultra-clair — sections alternées |
| `--pb-ink` (`#1F2937`) | Texte corps |
| `--pb-ink-soft` (`#6B7280`) | Méta-infos |
| `--pb-font-heading` | Quicksand (titres ronds) |
| `--pb-font-body` | Inter (corps lisible) |
| `--pb-radius` (8–18px) | Coins arrondis |
| `--pb-shadow-*` | 4 niveaux d'ombre légère |
| `--pb-transition-*` | 3 vitesses (120ms / 200ms / 320ms) |

**Mode sombre** : redéfinit les mêmes tokens dans `[data-theme="dark"]`. Aucune duplication de règles : changer un token suffit à propager.

## Hooks et filtres exposés

Le thème expose les filtres suivants pour personnalisation sans modifier le code :

| Filtre | Rôle |
|---|---|
| `promenebebe_hero_query_args` | Critères de la requête du hero slider (par défaut : 5 derniers articles publiés, sticky en priorité) |
| `promenebebe_home_grid_args` | Critères de la grille articles sous le hero |
| `promenebebe_related_args` | Critères des articles similaires |
| `promenebebe_newsletter_action` | URL d'endpoint du formulaire newsletter (vide tant que pas de service) |
| `promenebebe_newsletter_fields` | Mapping des noms de champs attendus par le fournisseur |
| `promenebebe_default_og_image` | URL de l'image OG par défaut |

Action utile pour la newsletter :

| Action | Rôle |
|---|---|
| `promenebebe_newsletter_hidden_fields` | Permet d'ajouter des `<input type="hidden">` (consent, list_id, etc.) |

## Helpers PHP exposés

| Fonction | Rôle |
|---|---|
| `promenebebe_asset( $relative )` | URL absolue d'un asset du child theme |
| `promenebebe_logo_url()` | URL du logo (custom logo > asset par défaut) |
| `promenebebe_reading_time( $post_id = null )` | Temps de lecture estimé (200 mots/min) |
| `promenebebe_theme_toggle_html()` | Markup du bouton mode sombre, à utiliser dans un template |

## Mode sombre

- Implémenté via `[data-theme="dark"]` sur `<html>`
- Persistance `localStorage` (clé `pb-theme`, valeurs `light` ou `dark`)
- Fallback préférence système (`prefers-color-scheme`)
- Anti-FOUC : script inline en tête de `<head>` (avant le CSS)
- API publique : `window.PB.setTheme('light'|'dark'|'auto')`

## Performance — choix structurants

- Polices Google chargées en `display=swap` + preconnect (pas de FOIT)
- Image hero du premier slide en `fetchpriority=high` + `loading=eager`
- Reste des images en `loading=lazy`
- Scripts en `defer`, pas de jQuery, vanilla JS partout
- Slider : CSS scroll-snap natif (pas de framework)
- TOC : généré uniquement si ≥ 2 H2/H3 dans l'article
- Heartbeat WP désactivé sur le frontend
- Emojis WP désactivés
- 5 révisions maximum par article (vs illimité par défaut)
