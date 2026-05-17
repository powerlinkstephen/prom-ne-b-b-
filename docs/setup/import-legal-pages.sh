#!/usr/bin/env bash
#
# Import des 3 pages institutionnelles dans WordPress (Promène Bébé).
# À exécuter depuis la "Site shell" de LocalWP (wp-cli installé).
#
# Le script crée les pages si elles n'existent pas, sinon met à jour leur
# contenu et leur slug pour rester idempotent.

set -euo pipefail

# Résout le chemin du projet à partir de l'emplacement du script.
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"
LEGAL_DIR="$PROJECT_DIR/docs/legal"

if ! command -v wp >/dev/null 2>&1; then
    echo "✘ wp-cli introuvable. Ouvrez la 'Site shell' de LocalWP, ou ajoutez wp-cli au PATH."
    exit 1
fi

upsert_page() {
    local slug="$1"
    local title="$2"
    local file="$3"

    if [[ ! -f "$file" ]]; then
        echo "✘ Fichier manquant : $file"
        return 1
    fi

    local existing_id
    existing_id="$(wp post list --post_type=page --name="$slug" --field=ID 2>/dev/null || true)"

    if [[ -n "$existing_id" ]]; then
        echo "→ Mise à jour de la page existante : $title (#$existing_id)"
        wp post update "$existing_id" "$file" \
            --post_title="$title" \
            --post_name="$slug" \
            --post_status=publish
    else
        echo "→ Création de la page : $title"
        wp post create "$file" \
            --post_type=page \
            --post_title="$title" \
            --post_name="$slug" \
            --post_status=publish
    fi
}

upsert_page "mentions-legales"              "Mentions légales"             "$LEGAL_DIR/mentions-legales.html"
upsert_page "politique-de-confidentialite"  "Politique de confidentialité" "$LEGAL_DIR/politique-de-confidentialite.html"
upsert_page "divulgation-affiliation"       "Divulgation d'affiliation"    "$LEGAL_DIR/divulgation-affiliation.html"

echo "✔ Pages légales importées."
echo "Pensez à compléter les mentions [À COMPLÉTER PAR LE PROPRIÉTAIRE] dans chaque page."
