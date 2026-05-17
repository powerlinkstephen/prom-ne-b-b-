# Guide d'utilisation — propriétaire Promène Bébé

Manuel court pour gérer le site au quotidien. Suppose que tu es connecté à `/wp-admin` avec un compte administrateur.

## 1. Publier un article

1. **Articles → Ajouter** dans la sidebar admin
2. Rédige le titre — c'est le H1 unique de la page. Concis, descriptif, le mot-clé principal au début si possible
3. Ajoute le contenu :
   - **H2** pour les grandes sections (utilisés par le sommaire automatique)
   - **H3** pour les sous-sections
   - Pas de **H1** dans le corps (réservé au titre)
4. **Image à la une** (colonne de droite) — obligatoire, sert :
   - À la couverture de l'article
   - Aux cartes article (home, archives, similaires)
   - À l'image Open Graph pour les partages sociaux (Facebook, Pinterest, WhatsApp)
   - Format conseillé : 1600×900 px, WebP ou JPEG, < 200 ko
5. **Catégorie** : une seule catégorie principale (au choix parmi les 5)
6. **Extrait** (panneau "Résumé") : 2 phrases qui s'affichent dans les grilles et dans le SEO. Si vide, WordPress prend les premiers mots du contenu — préfère l'écrire explicitement
7. **Permalien** (panneau "URL") : vérifie qu'il est court et descriptif (ex. `top-10-poussettes-citadines-2026`)
8. **Publier** ou **Planifier**

### Astuces
- Le sommaire automatique apparaît dès qu'il y a ≥ 2 H2/H3 dans l'article
- Le temps de lecture est calculé tout seul (200 mots/min)
- Les boutons de partage et la section "Sur le même sujet" se génèrent toutes seules

## 2. Insérer un comparatif Affilizz

Une fois le plugin Affilizz installé et connecté (voir `setup/plugins-install.md`) :

1. Dans l'éditeur Gutenberg, clique sur **+** → cherche **Affilizz**
2. Sélectionne le produit (référence Affilizz)
3. Le bloc s'insère automatiquement avec le style Promène Bébé (palette appliquée via CSS)
4. Vérifie que le lien d'affiliation est bien actif en mode aperçu (clic-droit → "Inspecter" sur le bouton, l'URL doit contenir ton ID Affilizz)

### Bonnes pratiques
- Pas plus d'un bloc Affilizz tous les 300 mots (sinon ça devient publicitaire)
- Toujours commenter le produit avec un avis honnête (avantages **et** inconvénients) — exigence du CDC § 3.3
- Mentionner explicitement la nature affiliée si l'article est entièrement orienté produit (au-delà de la page "Divulgation d'affiliation")

## 3. Gérer les catégories

Articles → **Catégories** :

- Modifier le nom, le slug (= URL) et la description d'une catégorie
- La description s'affiche en haut de la page d'archive — c'est de la matière SEO précieuse, à compléter (200-300 mots si possible)
- Tu peux créer des catégories en plus des 5 placeholder du CDC. Évite d'en multiplier (5–8 catégories est l'idéal pour un blog d'affiliation niché)

## 4. Activer / désactiver le mode sombre

Côté visiteur : le bouton 🌙 dans le header bascule le mode sombre. La préférence est mémorisée dans le navigateur.

Côté admin (pour toi) : aucun mode sombre admin n'est forcé — WordPress garde son interface standard. Le mode sombre n'affecte que le frontend visiteur.

## 5. Modifier le menu principal

Apparence → **Menus** :

- Sélectionner "Menu principal" (location : `Menu principal Promène Bébé`)
- Ajouter / retirer des entrées (pages, catégories, liens custom)
- Glisser-déposer pour réordonner ou imbriquer (sous-menus déroulants supportés sur desktop)
- **Enregistrer**

Idem pour "Menu pied de page" (location : `Menu pied de page Promène Bébé`).

## 6. Newsletter — quand le service sera choisi

Pour l'instant, le formulaire dans le footer affiche un message d'attente. Quand MailerLite ou Brevo sera retenu :

### Option A — plugin officiel du fournisseur
1. Installer le plugin (MailerLite ou Brevo) depuis Plugins → Ajouter
2. Connecter le compte via la clé API (présente dans `IDENTIFIANTS.md`)
3. Remplacer `template-parts/newsletter.php` dans le child theme par le shortcode officiel du fournisseur, ou modifier le fichier pour appeler la fonction du plugin
4. Tester l'opt-in (un mail de confirmation doit arriver)

### Option B — endpoint direct
Ajouter dans `functions.php` du child theme :
```php
add_filter( 'promenebebe_newsletter_action', function () {
    return 'https://assets.mailerlite.com/jsonp/XXXXX/forms/YYYYY/subscribe';
} );
add_action( 'promenebebe_newsletter_hidden_fields', function () {
    echo '<input type="hidden" name="anti-bot-field" value="">';
} );
```

## 7. Sauvegardes

UpdraftPlus tourne tous les jours (configuré dans `setup/plugins-install.md`). Pour vérifier :

- Réglages → UpdraftPlus → **Existing Backups** : la dernière sauvegarde doit dater de < 24h
- Au moins une fois par mois, lance une "Backup Now" manuelle puis télécharge l'archive sur ton disque local (redondance)
- **Tester** une restauration sur un environnement local avant tout passage en production

## 8. Sécurité — gestes quotidiens

- Ne **jamais** te connecter depuis un wifi public sans VPN
- Ne **jamais** réutiliser ton mot de passe admin ailleurs
- Si Wordfence te notifie une activité suspecte, **lis** le détail avant de cliquer "Block" en masse
- Au moindre doute (changement de fichier inattendu, plugin lent, comportement bizarre), lance un scan complet Wordfence

## 9. Avant de passer en production sur o2switch

Voir `docs/setup/security-checklist.md` section "Avant le passage en production" — checklist exhaustive.

Résumé :
- Repasser `blog_public` à `1` (réactivation indexation moteurs)
- Rotater les salts dans `wp-config.php`
- Activer 2FA Wordfence sur ton compte
- Soumettre le sitemap à Google Search Console
- Vérifier que `IDENTIFIANTS.md` n'est jamais committé sur Git
