<?php
/**
 * Thème enfant Promène Bébé (Kidearn Child) — functions.php
 *
 * Toutes les personnalisations passent par ce fichier (et les inclusions
 * dans inc/). Aucune modification du thème parent Kidearn.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Constantes utilitaires.
 */
define( 'PROMENEBEBE_VERSION', '1.0.0' );
define( 'PROMENEBEBE_TEXTDOMAIN', 'promenebebe' );
define( 'PROMENEBEBE_CHILD_URI', get_stylesheet_directory_uri() );
define( 'PROMENEBEBE_CHILD_PATH', get_stylesheet_directory() );

/**
 * Chargement des modules.
 */
require_once PROMENEBEBE_CHILD_PATH . '/inc/seo.php';
require_once PROMENEBEBE_CHILD_PATH . '/inc/security.php';

/* =============================================================
 * 1. SETUP DU THÈME
 * ============================================================= */

/**
 * Charge le textdomain du thème enfant (i18n).
 */
function promenebebe_setup_textdomain() {
    load_child_theme_textdomain(
        PROMENEBEBE_TEXTDOMAIN,
        PROMENEBEBE_CHILD_PATH . '/languages'
    );
}
add_action( 'after_setup_theme', 'promenebebe_setup_textdomain' );

/**
 * Déclare les supports de thème spécifiques à Promène Bébé.
 *
 * Note : `custom-logo` est déjà géré par le parent Kidearn ; on ajoute
 * uniquement ce qui n'est pas garanti dans toutes les versions du parent.
 */
function promenebebe_theme_supports() {
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery',
        'caption', 'style', 'script',
    ) );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );

    // Menus complémentaires (le parent peut déjà en enregistrer, register_nav_menus est cumulatif)
    register_nav_menus( array(
        'promenebebe_primary' => __( 'Menu principal Promène Bébé', 'promenebebe' ),
        'promenebebe_footer'  => __( 'Menu pied de page Promène Bébé', 'promenebebe' ),
    ) );
}
add_action( 'after_setup_theme', 'promenebebe_theme_supports', 20 );

/* =============================================================
 * 2. ASSETS — FEUILLES DE STYLES & SCRIPTS
 * ============================================================= */

/**
 * Charge le style parent puis le style enfant + main.css + theme.js.
 *
 * Ordre voulu :
 *   1. Polices Google (preconnect + preload + chargement)
 *   2. Style parent Kidearn (préservé)
 *   3. style.css enfant (header seulement, dépend du parent)
 *   4. main.css enfant (palette + composants Promène Bébé)
 *   5. theme.js (mode sombre, footer + defer)
 */
function promenebebe_enqueue_assets() {

    // --- Polices Google Fonts -------------------------------------
    // Preconnect pour réduire la latence DNS/TLS sur fonts.googleapis.com
    add_filter( 'wp_resource_hints', 'promenebebe_resource_hints', 10, 2 );

    wp_enqueue_style(
        'promenebebe-fonts',
        'https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap',
        array(),
        null  /* pas de querystring de version sur une URL externe avec hash */
    );

    // --- Styles parent (conservé tel quel) ------------------------
    wp_enqueue_style(
        'kidearn-parent-style',
        get_template_directory_uri() . '/style.css',
        array( 'kidearn-fonts', 'kidearn-icons', 'bootstrap', 'fontawesome' ),
        PROMENEBEBE_VERSION
    );

    // --- style.css enfant (header WordPress seulement) ------------
    wp_enqueue_style(
        'promenebebe-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'kidearn-parent-style' ),
        PROMENEBEBE_VERSION
    );

    // --- main.css enfant (palette + composants) -------------------
    wp_enqueue_style(
        'promenebebe-main',
        PROMENEBEBE_CHILD_URI . '/assets/css/main.css',
        array( 'promenebebe-child-style', 'promenebebe-fonts' ),
        PROMENEBEBE_VERSION
    );

    // --- Script principal (mode sombre, etc.) ---------------------
    wp_enqueue_script(
        'promenebebe-theme',
        PROMENEBEBE_CHILD_URI . '/assets/js/theme.js',
        array(),
        PROMENEBEBE_VERSION,
        array( 'in_footer' => true, 'strategy' => 'defer' )
    );

    // --- Interactions UI (slider, recherche, menu mobile) ----------
    wp_enqueue_script(
        'promenebebe-ui',
        PROMENEBEBE_CHILD_URI . '/assets/js/ui.js',
        array(),
        PROMENEBEBE_VERSION,
        array( 'in_footer' => true, 'strategy' => 'defer' )
    );
}
add_action( 'wp_enqueue_scripts', 'promenebebe_enqueue_assets', 20 );

/**
 * Ajoute les resource hints pour les polices Google.
 */
function promenebebe_resource_hints( $hints, $relation ) {
    if ( 'preconnect' === $relation ) {
        $hints[] = array(
            'href'        => 'https://fonts.googleapis.com',
            'crossorigin' => 'anonymous',
        );
        $hints[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $hints;
}

/**
 * Injecte un mini-script de bootstrap thème dans <head> AVANT le rendu du body
 * pour appliquer immédiatement data-theme et éviter le flash (FOUC).
 *
 * Ce code est volontairement minuscule et synchrone.
 */
function promenebebe_inline_theme_init() {
    ?>
    <script>
    (function () {
        try {
            var t = localStorage.getItem('pb-theme');
            if (t !== 'light' && t !== 'dark') {
                t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
                    ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', t);
        } catch (e) {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    })();
    </script>
    <?php
}
add_action( 'wp_head', 'promenebebe_inline_theme_init', 1 );

/**
 * Ajoute la classe `pb-themed` sur <body> pour scoper les styles enfant.
 */
function promenebebe_body_class( $classes ) {
    $classes[] = 'pb-themed';
    return $classes;
}
add_filter( 'body_class', 'promenebebe_body_class' );

/* =============================================================
 * 3. LOGO & FAVICON
 * ============================================================= */

/**
 * Helper : URL absolue d'un asset du child theme.
 */
function promenebebe_asset( $relative ) {
    return PROMENEBEBE_CHILD_URI . '/assets/' . ltrim( $relative, '/' );
}

/**
 * Renvoie l'URL du logo principal (PNG fond transparent).
 *
 * Si un logo personnalisé a été défini côté Apparence > Personnaliser,
 * il est utilisé en priorité (compatibilité custom-logo WordPress).
 */
function promenebebe_logo_url() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $src = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        if ( ! empty( $src[0] ) ) {
            return $src[0];
        }
    }
    return promenebebe_asset( 'img/logo.png' );
}

/**
 * Définit un favicon par défaut si aucune Site Icon n'a été configurée.
 * Ne remplace pas une Site Icon active (Apparence > Personnaliser > Identité).
 */
function promenebebe_default_favicon() {
    if ( has_site_icon() ) {
        return;
    }
    $favicon = promenebebe_asset( 'img/icon-512.png' );
    echo '<link rel="icon" type="image/png" sizes="512x512" href="' . esc_url( $favicon ) . '" />' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url( $favicon ) . '" />' . "\n";
}
add_action( 'wp_head', 'promenebebe_default_favicon', 5 );

/* =============================================================
 * 4. PERFORMANCE & HARDENING LÉGER
 *    (Hardening complet : voir P10. Ici, gains rapides.)
 * ============================================================= */

/**
 * Désactive les emojis WordPress (gain perf, peu utiles ici).
 */
function promenebebe_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', function ( $plugins ) {
        return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
    } );
}
add_action( 'init', 'promenebebe_disable_emojis' );

/**
 * Limite les révisions d'articles à 5 (CDC : section 10.3).
 * Définie via filtre pour respecter une éventuelle constante existante.
 */
function promenebebe_limit_revisions( $num ) {
    if ( defined( 'WP_POST_REVISIONS' ) && WP_POST_REVISIONS !== true ) {
        return $num;
    }
    return 5;
}
add_filter( 'wp_revisions_to_keep', 'promenebebe_limit_revisions', 10, 1 );

/**
 * Masque la version de WordPress (entête + meta generator).
 */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Désactive XML-RPC (vecteur d'attaques courant, non utilisé ici).
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/* =============================================================
 * 5. UTILITAIRES TEMPLATES
 *    (Helpers réutilisés par les templates P2/P3/P4.)
 * ============================================================= */

/**
 * Temps de lecture estimé d'un article (en minutes).
 *
 * Base : 200 mots/minute (lecture confortable, langue FR).
 */
function promenebebe_reading_time( $post_id = null ) {
    $post    = get_post( $post_id );
    if ( ! $post ) {
        return 0;
    }
    $content = wp_strip_all_tags( $post->post_content );
    $words   = str_word_count( $content );
    $minutes = max( 1, (int) ceil( $words / 200 ) );
    return $minutes;
}

/**
 * Marqueur HTML pour le toggle mode sombre (à insérer dans header.php).
 *
 * Usage dans un template :
 *   echo promenebebe_theme_toggle_html();
 */
function promenebebe_theme_toggle_html() {
    ob_start();
    ?>
    <button type="button"
            class="pb-iconbtn"
            data-pb-theme-toggle
            aria-pressed="false"
            aria-label="<?php esc_attr_e( 'Activer le mode sombre', 'promenebebe' ); ?>">
        <span class="pb-sr-only"><?php esc_html_e( 'Bascule mode sombre', 'promenebebe' ); ?></span>
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path>
        </svg>
    </button>
    <?php
    return ob_get_clean();
}
