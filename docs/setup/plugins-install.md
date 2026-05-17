# Installation des plugins WordPress — Promène Bébé

Liste exhaustive des plugins requis par le CDC (§ 8.1) avec commandes `wp-cli`
prêtes à coller dans la "Site shell" LocalWP.

> Les plugins payants (WP Rocket, Imagify Pro, Rank Math Pro) ne sont pas
> installables directement via WP-CLI : il faut télécharger leur archive ZIP
> depuis le compte client puis l'installer via `wp plugin install /chemin/.zip`.
> Les substituts gratuits sont indiqués entre crochets.

---

## 1. Installation en lot — plugins gratuits du dépôt WP

```bash
wp plugin install \
    seo-by-rank-math \
    wordfence \
    updraftplus \
    akismet \
    relevanssi \
    cookie-notice \
    code-snippets \
    --activate
```

## 2. Plugins gratuits — par catégorie

### SEO
```bash
wp plugin install seo-by-rank-math --activate
# Configuration : Tableau de bord > Rank Math > Setup Wizard
# - Type de site : Blog d'affiliation
# - Activer : Sitemap, schémas, méta-titres, redirections, breadcrumbs
```

### Cache & performance (gratuit — substitut WP Rocket)
```bash
# Option A : LiteSpeed Cache (recommandé sur o2switch qui tourne sur LiteSpeed)
wp plugin install litespeed-cache --activate

# Option B : W3 Total Cache (fallback générique)
# wp plugin install w3-total-cache --activate
```

### Images (gratuit — substitut Imagify)
```bash
wp plugin install ewww-image-optimizer --activate
# Configuration : Réglages > EWWW Image Optimizer
# - WebP : activé
# - Conversion auto à l'upload : activé
```

### Sécurité
```bash
wp plugin install wordfence --activate
# Configuration manuelle requise :
# - Récupérer une clé gratuite sur https://www.wordfence.com/register
# - Activer le firewall en mode "Apprentissage" pendant 1 semaine
# - Programmer scan complet hebdomadaire
```

### Sauvegardes
```bash
wp plugin install updraftplus --activate
# Configuration : Réglages > UpdraftPlus
# - Fréquence : quotidienne pour les fichiers, quotidienne pour la BDD
# - Destination : Google Drive ou Dropbox (compte dans IDENTIFIANTS.md)
# - Tester une restauration AVANT le go-live
```

### Anti-spam
```bash
wp plugin install akismet --activate
# Configuration : Akismet > Réglages > clé API (Akismet Plus gratuit pour blog perso)
```

### Recherche
```bash
wp plugin install relevanssi --activate
# Configuration : Réglages > Relevanssi
# - Indexer : articles + pages
# - Lancer "Build the index" après installation
```

### RGPD / Cookies
```bash
wp plugin install cookie-notice --activate
# Configuration : Réglages > Cookies & RGPD
# - Mode : consentement granulaire
# - Bouton "Refuser" visible obligatoire
# - Bloquer scripts tiers tant que pas de consentement
```

### Snippets PHP custom
```bash
wp plugin install code-snippets --activate
# Permet d'ajouter des bouts de code PHP sans modifier functions.php
```

## 3. Plugins payants (à installer manuellement depuis ZIP)

### Affilizz (déterminer si plugin officiel ou widget JS)
```bash
# Si Affilizz fournit un plugin .zip :
wp plugin install /chemin/vers/affilizz.zip --activate

# Sinon, l'intégration se fait via Code Snippets ou directement dans les articles
# (ID compte Affilizz dans IDENTIFIANTS.md)
```

### WP Rocket (si licence disponible)
```bash
wp plugin install /chemin/vers/wp-rocket-X.Y.Z.zip --activate
# Configuration recommandée :
# - Cache : activé sur visiteurs non connectés
# - Optimisation CSS : minify + combine + remove unused (test progressif)
# - Optimisation JS : defer + delay JavaScript execution
# - Médias : lazy load images + iframes, WebP compatibility
# - Préchargement : sitemap-based
# - DÉSACTIVER WP Rocket si LiteSpeed Cache est actif (incompatibles)
```

### Rank Math Pro (si licence disponible)
```bash
wp plugin install /chemin/vers/seo-by-rank-math-pro-X.Y.Z.zip --activate
# Apporte : schémas avancés (FAQ, HowTo, Review), redirections premium, etc.
```

### Imagify (si licence disponible)
```bash
wp plugin install /chemin/vers/imagify.zip --activate
# Préférable à EWWW pour le rapport qualité/compression et WebP par défaut
# Désactiver EWWW si on active Imagify
```

## 4. Vérification finale

```bash
wp plugin list --status=active
wp plugin update --all
```

## 5. Plugins à NE PAS installer

- **Yoast SEO** : on a déjà Rank Math (un seul plugin SEO à la fois)
- **JetPack** : trop lourd, fonctionnalités redondantes avec ce qu'on a déjà
- **Tout autre plugin de cache** si LiteSpeed Cache ou WP Rocket est actif
- **Tout plugin de page builder** (Elementor, Divi, etc.) : la maquette est entièrement gérée par le thème enfant Promène Bébé

## 6. Plugins Claude Code (référence — pas pour WordPress)

Pour mémoire, les "plugins Claude Code" évoqués au CDC § 9.2 désignent des
**skills** côté agent (BMad, Frontend Design, Caveman, Superpowers, Security
Review) — ils ne nécessitent **aucune installation côté WordPress**. Ils
guident la méthodologie de développement et sont utilisés par l'agent au
fil du projet.
