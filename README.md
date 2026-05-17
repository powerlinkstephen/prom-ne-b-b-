# Promène Bébé

Blog d'affiliation spécialisé dans les poussettes pour bébé.

## Description

Promène Bébé est un blog d'affiliation francophone dédié exclusivement à l'aide à l'achat de poussettes.

## Workflow

- Développement : LOCAL via Local by Flywheel + Claude Code
- Mise en ligne : upload sur o2switch après validation

## Stack technique

- CMS : WordPress (dernière version)
- Thème : Kidearn (personnalisé via thème enfant)
- Environnement local : Local by Flywheel
- Hébergeur final : o2switch
- Plateforme d'affiliation : Affilizz
- Méthodologie de développement : BMad (Claude Code CLI)

## URLs

- Site local (développement) : https://promene-bebe.local
- Admin local : https://promene-bebe.local/wp-admin
- Site production (après upload) : [https://votredomaine.com]
- Admin production (après upload) : [https://votredomaine.com/wp-admin]

## Chemin du WordPress local

Le WordPress local est dans le dossier `app/public/` du site Local by Flywheel.

Chemin exact : voir Local > clic-droit sur le site > Reveal in Finder/Show folder

## Liens utiles

- Cahier des Charges : voir le fichier `CDC_PromeneBebe.docx` dans ce dossier
- Identifiants et accès : voir le fichier `IDENTIFIANTS.md` (NE PAS COMMIT)
- Logo et assets : dossier `assets/`

## Comptes et services

- cPanel o2switch : [URL d'accès]
- Affilizz : https://www.affilizz.com
- Google Search Console : https://search.google.com/search-console
- Google Analytics 4 : https://analytics.google.com
- Newsletter : [MailerLite ou Brevo]

## URL locale réelle

L'URL actuelle est `http://localhost:10009` (port géré par Local by Flywheel). À ajuster dans LocalWP si un domaine `.local` propre est souhaité.

## État d'avancement

Détail complet dans [`docs/04-bmad-progress.md`](docs/04-bmad-progress.md).

- [x] Phase 1 : Analyse du projet et plan d'action
- [x] Phase 2 : Création du thème enfant Kidearn (`promene-bebe/app/public/wp-content/themes/kidearn-child/`)
- [x] Phase 3 : Personnalisation visuelle (palette, typographie Quicksand+Inter, logo, mode sombre)
- [x] Phase 4 : Développement de la page d'accueil (hero slider + grille articles)
- [x] Phase 5 : Templates d'articles et fonctionnalités (TOC, breadcrumbs, share, similaires)
- [ ] Phase 6 : Installation et configuration des plugins WordPress *(commandes prêtes dans [`docs/setup/plugins-install.md`](docs/setup/plugins-install.md), exécution à faire dans la Site shell LocalWP)*
- [x] Phase 7 : Rédaction des pages légales *(HTML prêt dans [`docs/legal/`](docs/legal/), import via [`docs/setup/import-legal-pages.sh`](docs/setup/import-legal-pages.sh))*
- [ ] Phase 8 : Tests, optimisations et sécurité — hardening fait, audit PageSpeed à lancer après activation thème
- [x] Phase 9 : Documentation et livraison ([`docs/`](docs/))
- [ ] Phase 10 : (Plus tard) Upload sur o2switch

### Actions propriétaire restantes

Voir le tableau "Reste à faire" dans [`docs/04-bmad-progress.md`](docs/04-bmad-progress.md).

Résumé des bloquants :
1. Activer le thème `kidearn-child` (`wp theme activate kidearn-child`)
2. Exécuter les commandes de [`docs/setup/wp-cli-commands.md`](docs/setup/wp-cli-commands.md)
3. Installer les plugins de [`docs/setup/plugins-install.md`](docs/setup/plugins-install.md)
4. Importer les pages légales : `bash docs/setup/import-legal-pages.sh`
5. Compléter les `[À COMPLÉTER PAR LE PROPRIÉTAIRE]` dans les 3 pages légales
6. Fournir un logo SVG et une image `og-default.png` 1200×630
7. Choisir le service newsletter (MailerLite ou Brevo) et compléter `IDENTIFIANTS.md`

## Règles importantes pour Claude Code

- Travailler EXCLUSIVEMENT dans ce dossier projet et dans le WordPress local
- Utiliser la méthodologie BMad pour structurer le travail
- Respecter strictement la palette de couleurs définie dans le Cahier des Charges
- Ne JAMAIS modifier le thème parent Kidearn (uniquement via thème enfant)
- Documenter chaque grande étape dans le dossier `docs/`
- Le développement est en LOCAL, l'upload vers o2switch se fait en fin de projet