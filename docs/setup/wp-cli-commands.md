# Commandes WP-CLI — Promène Bébé

Toutes les commandes ci-dessous doivent être exécutées **depuis la "Site shell" de LocalWP** (clic droit sur le site Promène Bébé → "Open site shell"), qui ouvre un terminal avec `wp-cli` correctement configuré.

> Le proprio peut copier les blocs un par un, ou exécuter le script `import-legal-pages.sh` fourni dans ce dossier pour l'étape "pages légales".

---

## 1. Vérifications préalables

```bash
# Position dans la racine WordPress
wp option get siteurl
wp core version

# Mise à jour core + traductions FR
wp core update
wp language core install fr_FR --activate
wp language plugin install --all fr_FR
wp language theme install --all fr_FR
```

## 2. Réglages WordPress de base (CDC § 8.2)

```bash
# Permaliens propres
wp option update permalink_structure "/%postname%/"

# Fuseau horaire + format de date FR
wp option update timezone_string "Europe/Paris"
wp option update date_format "j F Y"
wp option update time_format "H\\hi"
wp option update start_of_week 1

# Désactiver pingbacks / trackbacks
wp option update default_ping_status closed
wp option update default_pingback_flag 0

# Indexation désactivée pendant le développement (à remettre à 0 au lancement)
wp option update blog_public 0

# Identité du site
wp option update blogname "Promène Bébé"
wp option update blogdescription "Comparatifs et guides d'achat poussettes pour bébé"
```

## 3. Activation du thème enfant Promène Bébé

```bash
wp theme activate kidearn-child
wp theme list
```

## 4. Création des 5 catégories placeholder (CDC § 8.3)

```bash
wp term create category "Poussettes citadines"   --slug=poussettes-citadines     --description="Poussettes pensées pour la ville : compactes, maniables, légères."
wp term create category "Poussettes tout-terrain" --slug=poussettes-tout-terrain  --description="Poussettes robustes pour les parents actifs et les terrains variés."
wp term create category "Poussettes doubles"      --slug=poussettes-doubles       --description="Solutions pour deux enfants : jumeaux ou rapprochés en âge."
wp term create category "Poussettes cannes"       --slug=poussettes-cannes        --description="Poussettes ultra-légères, idéales pour les voyages et les transports."
wp term create category "Poussettes combinées"    --slug=poussettes-combinees     --description="Poussettes évolutives compatibles nacelle et siège auto."
```

## 5. Import des 3 pages légales (CDC § 12)

Exécutez le script fourni :

```bash
bash /path/vers/projet/docs/setup/import-legal-pages.sh
```

Ou, manuellement, page par page :

```bash
# Mentions légales
wp post create /chemin/vers/projet/docs/legal/mentions-legales.html \
  --post_type=page \
  --post_title="Mentions légales" \
  --post_name=mentions-legales \
  --post_status=publish

# Politique de confidentialité
wp post create /chemin/vers/projet/docs/legal/politique-de-confidentialite.html \
  --post_type=page \
  --post_title="Politique de confidentialité" \
  --post_name=politique-de-confidentialite \
  --post_status=publish

# Divulgation d'affiliation
wp post create /chemin/vers/projet/docs/legal/divulgation-affiliation.html \
  --post_type=page \
  --post_title="Divulgation d'affiliation" \
  --post_name=divulgation-affiliation \
  --post_status=publish
```

## 6. Création du menu principal et du menu pied de page

```bash
# Menu principal
wp menu create "Menu principal"
wp menu location assign menu-principal promenebebe_primary

# Items du menu principal
wp menu item add-custom menu-principal "Accueil" "$(wp option get home)"
wp menu item add-custom menu-principal "Comparatifs" "$(wp option get home)/comparatifs/"
wp menu item add-custom menu-principal "Guides d'achat" "$(wp option get home)/guides-dachat/"
wp menu item add-term  menu-principal category poussettes-citadines     --title="Poussettes citadines"
wp menu item add-term  menu-principal category poussettes-tout-terrain  --title="Poussettes tout-terrain"
wp menu item add-term  menu-principal category poussettes-doubles       --title="Poussettes doubles"
wp menu item add-term  menu-principal category poussettes-cannes        --title="Poussettes cannes"
wp menu item add-term  menu-principal category poussettes-combinees     --title="Poussettes combinées"

# Menu pied de page
wp menu create "Menu pied de page"
wp menu location assign menu-pied-de-page promenebebe_footer
wp menu item add-post  menu-pied-de-page $(wp post list --post_type=page --name=mentions-legales --field=ID)
wp menu item add-post  menu-pied-de-page $(wp post list --post_type=page --name=politique-de-confidentialite --field=ID)
wp menu item add-post  menu-pied-de-page $(wp post list --post_type=page --name=divulgation-affiliation --field=ID)
```

## 7. Création de 2-3 articles de démonstration (livrable CDC § 15.1)

```bash
wp post create --post_type=post --post_title="Comment choisir sa première poussette en 5 étapes" --post_status=publish --post_category=$(wp term list category --slug=poussettes-citadines --field=term_id)
wp post create --post_type=post --post_title="Top 5 des meilleures poussettes citadines 2026" --post_status=publish --post_category=$(wp term list category --slug=poussettes-citadines --field=term_id)
wp post create --post_type=post --post_title="Poussette tout-terrain : laquelle choisir pour la randonnée ?" --post_status=publish --post_category=$(wp term list category --slug=poussettes-tout-terrain --field=term_id)
```

## 8. Sécurité — hardening complémentaire (CDC § 11)

```bash
# Désactiver l'éditeur de fichiers depuis l'admin
wp config set DISALLOW_FILE_EDIT true --raw --type=constant

# Changer le préfixe DB (NB : à ne faire qu'avant le lancement, sauvegarde DB requise)
# Étape 1 : sauvegarde
wp db export ../backups/pre-prefix-change-$(date +%Y%m%d).sql
# Étape 2 : nouveau préfixe (exemple, à adapter)
# Éditer manuellement wp-config.php : $table_prefix = 'pb_';
# puis :
# wp db query "RENAME TABLE wp_options TO pb_options;"  (etc. pour chaque table)
```

## 9. Plugins WordPress à installer (CDC § 8.1)

Voir `docs/setup/plugins-install.md` pour la liste détaillée et les commandes.

---

## Conventions

- Les chemins absolus doivent être adaptés à votre installation LocalWP. Sous macOS, le projet est typiquement situé sous `/Volumes/Apps/LocalSite/prom-ne-b-b-/` mais wp-cli est exécuté depuis la racine du site WordPress (`promene-bebe/app/public/`).
- Les commandes `wp` ne demandent pas de droits sudo dans la "Site shell" de LocalWP.
- En cas de doute sur l'exécution réelle d'une commande, préfixez-la par `wp` puis ajoutez `--dry-run` quand disponible.
