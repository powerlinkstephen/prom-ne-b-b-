# Checklist sécurité — Promène Bébé

Actions à réaliser **avant la mise en production sur o2switch**. Les
hardenings appliqués automatiquement par le thème enfant sont décrits
sous "Déjà actif". Le reste relève d'actions manuelles côté hébergeur
et plugins.

---

## Déjà actif (via le thème enfant Promène Bébé)

- ✅ Désactivation XML-RPC (`xmlrpc_enabled` → false)
- ✅ Désactivation des pingbacks côté HTTP (`wp_xmlrpc_server_class`)
- ✅ Masquage de la version WP (`wp_generator`, `the_generator` vidés)
- ✅ Suppression de `rsd_link`, `wlwmanifest_link`, `wp_shortlink_wp_head`
- ✅ Blocage de l'énumération d'utilisateurs via `?author=N`
- ✅ Restriction de la route REST `/wp-json/wp/v2/users` (auth requise)
- ✅ En-têtes HTTP : `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`
- ✅ Messages d'erreur de login génériques (anti-énumération)
- ✅ Désactivation des emojis WP (perf + surface d'attaque réduite)
- ✅ Limitation à 5 révisions par article
- ✅ Désactivation du heartbeat frontend

## À configurer dans `wp-config.php` (manuel ou via wp-cli)

```bash
wp config set DISALLOW_FILE_EDIT true        --raw --type=constant
wp config set DISALLOW_FILE_MODS false       --raw --type=constant  # garder false en dev pour permettre l'install de plugins ; passer à true en prod si plus aucun changement n'est attendu
wp config set WP_AUTO_UPDATE_CORE 'minor'    --type=constant
wp config set FORCE_SSL_ADMIN true           --raw --type=constant
wp config set WP_POST_REVISIONS 5            --raw --type=constant
```

## À configurer via plugins (après installation)

### Wordfence (firewall + scan)

- Inscrire la clé gratuite (https://www.wordfence.com/register)
- Activer le firewall en mode "Apprentissage" pendant 7 jours puis bascule en "Activé et protégeant"
- Activer "Brute Force Protection" : limite à 5 tentatives, lockout 30 min
- Activer "Lock out invalid usernames" : bloque les essais sur "admin", "administrator", etc.
- Programmer un scan complet hebdomadaire (fichiers + DB + plugins)

### UpdraftPlus (sauvegardes)

- Destination cloud externe configurée (Google Drive ou Dropbox)
- Fréquence : quotidienne pour BDD, quotidienne pour fichiers
- Rétention : 30 jours minimum
- **Tester une restauration en local AVANT le go-live** (important — sauvegarde non testée = sauvegarde inutile)

### Cookie Notice & Compliance (RGPD)

- Consentement granulaire activé
- Bouton "Refuser" obligatoirement visible (CNIL)
- Blocage des scripts tiers tant que pas de consentement
- Lier vers `/politique-de-confidentialite/`

## À configurer côté o2switch (cPanel)

- ✅ HTTPS forcé (redirect 301 HTTP → HTTPS) : généralement déjà actif sur o2switch via Let's Encrypt
- 🔁 Changement du préfixe de table DB (de `wp_` vers un préfixe non devinable, ex. `pb_x9k_`) — voir wp-cli-commands.md § 8. **Sauvegarde DB obligatoire avant.**
- 🔁 Activer HSTS dans le `.htaccess` (ou via cPanel "Domains > HSTS")
  ```apache
  Header set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
  ```
- 🔁 Restreindre l'accès à `wp-login.php` par IP si possible (cPanel "ConfigServer Security & Firewall")
- 🔁 Désactiver l'indexation des dossiers (`Options -Indexes` dans `.htaccess`)
- 🔁 Désactiver le listing PHP `phpinfo.php` (le supprimer s'il existe)

## Avant le passage en production

- 🚦 Repasser `blog_public` à 1 (`wp option update blog_public 1`)
- 🚦 Rotater les salts dans `wp-config.php` (https://api.wordpress.org/secret-key/1.1/salt/)
- 🚦 Supprimer le compte WP "admin" (par défaut LocalWP : `Sadok` est déjà non-générique, OK)
- 🚦 Activer 2FA sur le compte admin (Wordfence 2FA est gratuit)
- 🚦 Vérifier que `IDENTIFIANTS.md` n'a JAMAIS été commité
- 🚦 Soumettre le sitemap à Google Search Console
- 🚦 Audit final : `wp plugin verify-checksums --all`

## Audit post-déploiement (J+1)

- 🔍 Scanner le site avec WPScan (https://wpscan.com) ou similaire
- 🔍 Test Mozilla Observatory (https://observatory.mozilla.org) sur l'URL prod
- 🔍 Test SecurityHeaders.com sur l'URL prod
- 🔍 Lancer un scan Wordfence "Scan completo" et corriger les warnings

---

## Sources / références

- CNIL — Recommandations cookies : https://www.cnil.fr/fr/cookies-et-autres-traceurs
- WordPress — Hardening WordPress : https://wordpress.org/documentation/article/hardening-wordpress/
- OWASP — WordPress Security Cheat Sheet : https://cheatsheetseries.owasp.org/
