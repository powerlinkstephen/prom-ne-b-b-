# Assets — Promène Bébé

Inventaire des fichiers visuels du projet et leur usage prévu.

## Logos disponibles

| Fichier | Dimensions | Fond | Usage |
|---|---|---|---|
| `logo-full-transparent.png` | 500×500 (texte centré bas) | Transparent | **Logo principal** — header, footer, pages internes. Posable sur n'importe quel fond. |
| `logo-full-pink.png` | 500×500 | Rose pastel `#F5DAF9` | Logo complet sur fond uni, alternative décorative. |
| `logo-badge-500.png` | 500×500 | Transparent autour, badge rose pastel arrondi | Variante badge (logo dans cadre arrondi rose). |
| `logo-badge-1125.png` | 1125×1125 | Idem badge | Version haute résolution du badge (image OG fallback, partages sociaux). |
| `icon-500.png` | 500×500 | Rose pastel arrondi | Icône fleur seule — source favicon, app icon. |
| `icon-250.png` | 250×250 | Rose pastel arrondi | Idem, version mobile/touch icon. |
| `icon-large.png` | ~1000+ | Rose pastel arrondi | Icône fleur grande résolution. |

## Manquants (à fournir par le propriétaire ou à générer)

- `logo-promenebebe.svg` — version vectorielle (CDC l'attend). Sans SVG, le rendu reste pixelisé sur très grandes tailles ; à recréer dans Figma/Illustrator si possible.
- `og-default.png` — image Open Graph par défaut **1200×630** pour partages réseaux sociaux. En attendant, `logo-badge-1125.png` peut servir de fallback mais le ratio n'est pas optimal pour Facebook/Twitter.
- `favicon.ico` multi-résolutions (16/32/48) — à générer depuis `icon-500.png` via un outil type [realfavicongenerator.net](https://realfavicongenerator.net) ou directement par WordPress (Site Identity → Site Icon).

## Couleurs détectées sur le logo (référence)

- Vert sapin (texte) : proche de `#18454A` ✓ palette CDC
- Magenta (fleur) : proche de `#823263`–`#B26494`, palette CDC retient `#B26494`
- Rose pastel (fond badge) : `#F5DAF9` ✓
- Rose ultra-clair (halo extérieur) : `#FBF5FC` ✓
