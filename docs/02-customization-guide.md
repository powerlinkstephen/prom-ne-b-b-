# Guide de personnalisation — thème enfant Promène Bébé

Comment ajuster le thème sans casser l'identité de marque ni les mises à jour Kidearn.

## Règles d'or

1. **Ne jamais éditer le thème parent Kidearn** (`wp-content/themes/kidearn/`)
2. Toutes les modifs vont dans `wp-content/themes/kidearn-child/`
3. Pour des modifs ponctuelles (snippet PHP), utiliser le plugin **Code Snippets** au lieu de toucher `functions.php`
4. Tester en local avant de pousser

## Changer la palette de couleurs

Édite `assets/css/main.css` § 1 (Variables). Les tokens dérivés se propagent automatiquement.

```css
:root {
    --pb-primary:    #18454A;  /* Couleur principale */
    --pb-secondary:  #B26494;  /* Couleur accent */
    --pb-pastel:     #F5DAF9;
    /* ... */
}
```

Toute modification du primaire ou du secondaire impacte automatiquement : header (logo, menu actif), CTA, liens, focus rings, breadcrumbs, partages, badges.

### Mode sombre
Édite le bloc `[data-theme="dark"]` (§ 10 du même fichier). Mêmes tokens, valeurs adaptées au contraste sur fond sombre.

⚠️ Toujours vérifier le contraste avec un outil comme [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/). Objectif **WCAG AA** :
- 4.5:1 pour le texte courant
- 3:1 pour le texte large (≥ 18.66px gras ou ≥ 24px regular)

## Changer les polices

Édite `functions.php` → `promenebebe_enqueue_assets` → ligne `wp_enqueue_style( 'promenebebe-fonts', ... )` et `main.css` § 1 (`--pb-font-heading`, `--pb-font-body`).

Conserve `display=swap` + `preconnect` (perfomance).

Exemple : passer de Quicksand à Fredoka pour les titres :
```php
// functions.php
wp_enqueue_style( 'promenebebe-fonts',
    'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap',
    array(), null );
```
```css
/* main.css */
--pb-font-heading: "Fredoka", system-ui, sans-serif;
```

## Ajouter une catégorie

Via wp-admin → **Articles → Catégories**, ou ligne wp-cli :
```bash
wp term create category "Poussettes 3 roues" --slug=poussettes-3-roues --description="..."
```
Puis l'ajouter au menu principal (Apparence → Menus).

## Personnaliser le hero slider

Filtre `promenebebe_hero_query_args` pour changer la requête (par défaut : 5 derniers articles, sticky en priorité).

Exemple : ne montrer que les articles d'une catégorie spécifique :
```php
// functions.php (ou via Code Snippets)
add_filter( 'promenebebe_hero_query_args', function ( $args ) {
    $args['category_name'] = 'a-la-une';
    return $args;
} );
```

## Personnaliser la grille de la home

Filtre `promenebebe_home_grid_args`.

Exemple : montrer 12 articles au lieu de 9 :
```php
add_filter( 'promenebebe_home_grid_args', function ( $args ) {
    $args['posts_per_page'] = 12;
    return $args;
} );
```

## Modifier le nombre d'articles similaires

Filtre `promenebebe_related_args` (par défaut : 4 articles).

## Changer le ton du formulaire newsletter

Édite `template-parts/newsletter.php` (les messages d'attente et la mention RGPD), ou utilise les filtres :
- `promenebebe_newsletter_action` (URL d'endpoint)
- `promenebebe_newsletter_fields` (mapping)
- Action `promenebebe_newsletter_hidden_fields` (champs cachés)

## Logo personnalisé

Deux options, par ordre de priorité :

1. **Recommandé** : Apparence → Personnaliser → **Identité du site** → uploader le logo. Le thème le récupère automatiquement (via `promenebebe_logo_url()`)
2. **Fallback** : remplacer `assets/img/logo.png` dans le child theme (PNG transparent, taille minimum 1000×300 px pour éviter le flou retina)

## Favicon

1. **Recommandé** : Apparence → Personnaliser → **Identité du site** → Site Icon (WordPress génère les déclinaisons automatiquement)
2. **Fallback** : remplacer `assets/img/icon-512.png` (carré, transparent ou fond rose pastel)

## Ajouter un module dans le footer

Édite `footer.php`. Pour rester maintenable, crée un partial dans `template-parts/footer-*.php` et inclus-le via `get_template_part()`.

## Ajouter un composant CSS

Convention : prefix `pb-` sur toutes les classes du thème enfant. Ajoute le bloc dans la section appropriée de `main.css` ou crée une nouvelle section. Réutilise les variables (`--pb-*`) pour rester cohérent avec la charte.

## Ajouter du JavaScript

Deux approches selon le périmètre :

### Petit composant isolé
Ajoute un fichier dans `assets/js/composant.js` puis enqueue-le dans `functions.php` :
```php
wp_enqueue_script(
    'promenebebe-mon-composant',
    PROMENEBEBE_CHILD_URI . '/assets/js/composant.js',
    array(),
    PROMENEBEBE_VERSION,
    array( 'in_footer' => true, 'strategy' => 'defer' )
);
```

### Extension d'un composant existant
Étends `ui.js` ou `theme.js` selon le périmètre (UI vs thématique).

## Désactiver une protection de sécurité spécifique

Si Wordfence ou un audit signale qu'une protection du thème entre en conflit avec un usage légitime, retire-la chirurgicalement. Exemple : autoriser à nouveau la route `/wp-json/wp/v2/users` (utile pour certains plugins headless) :

```php
// functions.php (en bas)
remove_filter( 'rest_authentication_errors', 'promenebebe_restrict_rest_users' );
```

## Désactiver le SEO du thème (si plugin SEO actif)

Aucune action nécessaire : `inc/seo.php` détecte automatiquement Rank Math / Yoast / AIOSEO / SEOPress et se met en retrait. Si tu utilises un autre plugin SEO non détecté, ajoute :
```php
add_filter( 'pre_promenebebe_has_seo_plugin', '__return_true' );
```
(à implémenter avec un `apply_filters` autour de la vérif dans `inc/seo.php` si besoin)

## Mise à jour de Kidearn parent

1. Sauvegarde : `wp db export ../backups/avant-maj-kidearn-$(date +%Y%m%d).sql` + tar des fichiers
2. Mettre à jour le thème parent via ThemeForest / Layerdrops
3. Vérifier que le child theme charge toujours correctement (`wp theme list`)
4. Tester :
   - Home (hero slider, grille)
   - Article (TOC, share, similaires)
   - Catégorie
   - Pages légales
   - Mode sombre
5. Si rupture : voir si une fonction parent dont dépend le child a changé. Adapter dans le child sans toucher au parent

## Workflow de modification recommandé

```
1. Créer une branche : git checkout -b feat/mon-changement
2. Modifier dans le child theme uniquement
3. Tester en local (multi-navigateurs, mobile, dark mode)
4. Documenter dans docs/ si la modif est structurelle
5. Commit + push branche
6. Merge dans dev (puis main quand validé en production)
```
