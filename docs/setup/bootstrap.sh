#!/usr/bin/env bash
#
# Promène Bébé — script bootstrap complet.
#
# Idempotent : exécute toutes les étapes de configuration WordPress
# requises par le CDC, sans rien casser si certaines étapes sont déjà
# faites.
#
# Pré-requis :
#   - Site Local by Flywheel `promene-bebe` démarré (MySQL doit tourner)
#   - Exécution depuis la racine du dépôt Promène Bébé
#
# Le script auto-détecte wp-cli bundlé avec Local. Si tu préfères, ouvre
# la "Site shell" de Local et lance les commandes une par une depuis
# `docs/setup/wp-cli-commands.md`.

set -euo pipefail

# --- Auto-détection de wp-cli + PHP fournis par Local --------------------
PHP_BIN="$(find /Applications/Local.app/Contents/Resources/extraResources/lightning-services -name 'php' -type f 2>/dev/null | head -1)"
WP_PHAR="/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"
SITE_PATH="$PROJECT_DIR/promene-bebe/app/public"
LEGAL_DIR="$PROJECT_DIR/docs/legal"

if [[ ! -x "$PHP_BIN" ]]; then
    echo "✘ PHP Local introuvable. Ouvre la Site shell de Local ou installe wp-cli globalement."
    exit 1
fi
if [[ ! -f "$WP_PHAR" ]]; then
    echo "✘ wp-cli.phar Local introuvable à $WP_PHAR"
    exit 1
fi
if [[ ! -d "$SITE_PATH" ]]; then
    echo "✘ Racine WordPress introuvable à $SITE_PATH"
    exit 1
fi

WP() { "$PHP_BIN" "$WP_PHAR" --path="$SITE_PATH" "$@"; }

# --- Vérification du démarrage du site -----------------------------------
if ! WP option get siteurl >/dev/null 2>&1; then
    echo "✘ Impossible de se connecter à la base. Démarre le site Local 'promene-bebe' puis relance ce script."
    exit 1
fi

echo "✔ wp-cli OK, base accessible. Démarrage du bootstrap…"

# --- 1. Réglages WordPress (CDC § 8.2) -----------------------------------
echo "→ Réglages WordPress de base"
WP option update permalink_structure "/%postname%/" --quiet
WP option update timezone_string "Europe/Paris" --quiet
WP option update date_format "j F Y" --quiet
WP option update start_of_week 1 --quiet
WP option update default_ping_status closed --quiet
WP option update default_pingback_flag 0 --quiet
WP option update blog_public 0 --quiet
WP option update blogname "Promène Bébé" --quiet
WP option update blogdescription "Comparatifs et guides d'achat poussettes pour bébé" --quiet
WP rewrite flush --quiet

# --- 2. Activation du thème enfant ---------------------------------------
echo "→ Activation du thème enfant"
WP theme activate kidearn-child

# --- 3. Catégories placeholder (CDC § 8.3) -------------------------------
echo "→ Création des 5 catégories placeholder"
create_cat() {
    local name="$1" slug="$2" desc="$3"
    if WP term get category "$slug" --by=slug --field=term_id >/dev/null 2>&1; then
        echo "  · existe déjà : $name"
    else
        WP term create category "$name" --slug="$slug" --description="$desc" >/dev/null
        echo "  ✓ créée : $name"
    fi
}
create_cat "Poussettes citadines"    "poussettes-citadines"     "Poussettes pensées pour la ville : compactes, maniables, légères."
create_cat "Poussettes tout-terrain" "poussettes-tout-terrain"  "Poussettes robustes pour les parents actifs et les terrains variés."
create_cat "Poussettes doubles"      "poussettes-doubles"       "Solutions pour deux enfants : jumeaux ou rapprochés en âge."
create_cat "Poussettes cannes"       "poussettes-cannes"        "Poussettes ultra-légères, idéales pour les voyages et transports."
create_cat "Poussettes combinées"    "poussettes-combinees"     "Poussettes évolutives compatibles nacelle et siège auto."

# --- 4. Pages légales (CDC § 12) -----------------------------------------
echo "→ Import des 3 pages légales (mentions, confidentialité, divulgation)"
upsert_page() {
    local slug="$1" title="$2" file="$3"
    local existing_id
    existing_id="$(WP post list --post_type=page --name="$slug" --field=ID 2>/dev/null || true)"
    if [[ -n "$existing_id" ]]; then
        WP post update "$existing_id" "$file" \
            --post_title="$title" --post_name="$slug" --post_status=publish >/dev/null
        echo "  ✓ mise à jour : $title (#$existing_id)"
    else
        WP post create "$file" \
            --post_type=page --post_title="$title" --post_name="$slug" --post_status=publish >/dev/null
        echo "  ✓ créée : $title"
    fi
}
upsert_page "mentions-legales"             "Mentions légales"              "$LEGAL_DIR/mentions-legales.html"
upsert_page "politique-de-confidentialite" "Politique de confidentialité"  "$LEGAL_DIR/politique-de-confidentialite.html"
upsert_page "divulgation-affiliation"      "Divulgation d'affiliation"     "$LEGAL_DIR/divulgation-affiliation.html"

# --- 5. Menus (principal + pied de page) ---------------------------------
echo "→ Configuration des menus"
ensure_menu() {
    local name="$1"
    if WP menu list --fields=name | grep -qx "$name"; then
        echo "  · menu existe : $name"
    else
        WP menu create "$name" >/dev/null
        echo "  ✓ créé : $name"
    fi
}
ensure_menu "Menu principal"
ensure_menu "Menu pied de page"
WP menu location assign menu-principal       promenebebe_primary --quiet || true
WP menu location assign menu-pied-de-page    promenebebe_footer  --quiet || true

# Items du menu principal (idempotent : on vide et on remplit pour rester propre)
WP menu item list menu-principal --fields=db_id 2>/dev/null | tail -n +2 \
    | xargs -I{} WP menu item delete {} 2>/dev/null || true

WP menu item add-custom menu-principal "Accueil" "$(WP option get home)" >/dev/null
WP menu item add-term  menu-principal category poussettes-citadines    --title="Poussettes citadines"    >/dev/null
WP menu item add-term  menu-principal category poussettes-tout-terrain --title="Poussettes tout-terrain" >/dev/null
WP menu item add-term  menu-principal category poussettes-doubles      --title="Poussettes doubles"      >/dev/null
WP menu item add-term  menu-principal category poussettes-cannes       --title="Poussettes cannes"       >/dev/null
WP menu item add-term  menu-principal category poussettes-combinees    --title="Poussettes combinées"    >/dev/null

# Items du menu pied de page
WP menu item list menu-pied-de-page --fields=db_id 2>/dev/null | tail -n +2 \
    | xargs -I{} WP menu item delete {} 2>/dev/null || true

WP menu item add-post menu-pied-de-page "$(WP post list --post_type=page --name=mentions-legales --field=ID)"             >/dev/null
WP menu item add-post menu-pied-de-page "$(WP post list --post_type=page --name=politique-de-confidentialite --field=ID)" >/dev/null
WP menu item add-post menu-pied-de-page "$(WP post list --post_type=page --name=divulgation-affiliation --field=ID)"      >/dev/null

# --- 6. Articles de démonstration (CDC § 15.1) ---------------------------
echo "→ Création des 3 articles de démonstration"
create_demo_post() {
    local slug="$1" title="$2" category_slug="$3" file="$4"
    local existing_id
    existing_id="$(WP post list --post_type=post --name="$slug" --field=ID 2>/dev/null || true)"
    if [[ -n "$existing_id" ]]; then
        echo "  · article existe : $title (#$existing_id)"
        return
    fi
    local cat_id
    cat_id="$(WP term get category "$category_slug" --by=slug --field=term_id)"
    WP post create "$file" \
        --post_type=post --post_title="$title" --post_name="$slug" \
        --post_status=publish --post_category="$cat_id" >/dev/null
    echo "  ✓ créé : $title"
}

DEMO_DIR="$PROJECT_DIR/docs/demo-articles"
if [[ -d "$DEMO_DIR" ]]; then
    create_demo_post "choisir-premiere-poussette-5-etapes" \
        "Comment choisir sa première poussette en 5 étapes" \
        "poussettes-citadines" \
        "$DEMO_DIR/choisir-premiere-poussette.html"
    create_demo_post "top-5-poussettes-citadines-2026" \
        "Top 5 des meilleures poussettes citadines 2026" \
        "poussettes-citadines" \
        "$DEMO_DIR/top-5-citadines.html"
    create_demo_post "poussette-tout-terrain-randonnee" \
        "Poussette tout-terrain : laquelle choisir pour la randonnée ?" \
        "poussettes-tout-terrain" \
        "$DEMO_DIR/poussette-tout-terrain.html"
else
    echo "  · dossier docs/demo-articles introuvable, articles de démo ignorés"
fi

# --- 7. Hardening léger via wp-config ------------------------------------
echo "→ Hardening wp-config.php"
WP config set DISALLOW_FILE_EDIT true --raw --type=constant 2>/dev/null || true
WP config set WP_AUTO_UPDATE_CORE 'minor' --type=constant 2>/dev/null || true
WP config set WP_POST_REVISIONS 5 --raw --type=constant 2>/dev/null || true

echo ""
echo "════════════════════════════════════════════════════════════"
echo "✔ Bootstrap terminé."
echo "  Front : $(WP option get home)"
echo "  Admin : $(WP option get home)/wp-admin"
echo ""
echo "Prochaines étapes :"
echo "  - Visite le front pour valider le rendu visuel"
echo "  - Installe les plugins via docs/setup/plugins-install.md"
echo "  - Complète les mentions [À COMPLÉTER PAR LE PROPRIÉTAIRE] dans les 3 pages légales"
echo "  - Lance un audit PageSpeed sur la home"
echo "════════════════════════════════════════════════════════════"
