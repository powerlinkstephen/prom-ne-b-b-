<?php
/**
 * Sécurité — hardening léger applicable depuis le thème enfant.
 *
 * Les protections plus profondes (rate-limit login, firewall WAF, scan
 * malware, sauvegardes externalisées) sont déléguées à Wordfence et
 * UpdraftPlus côté plugins (voir docs/setup/plugins-install.md).
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Retire les balises d'info exposant la stack WordPress dans <head>.
 *  - RSD (XML-RPC indirect)
 *  - wlwmanifest (Windows Live Writer, obsolète)
 *  - Shortlink (laisse fuiter l'ID interne, peu utile en SEO)
 *  - REST API discovery (laisser ouvert si front-end utilise REST)
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'template_redirect', 'wp_shortlink_header', 11 );

/**
 * Désactive l'énumération des utilisateurs via ?author=N.
 *
 * Sans cette protection, un attaquant peut lister les logins valides
 * (un par un) puis tenter du brute-force ciblé.
 */
function promenebebe_block_author_enum() {
    if ( is_admin() ) {
        return;
    }
    if ( isset( $_GET['author'] ) && ! is_admin() ) {
        wp_safe_redirect( home_url( '/' ), 301 );
        exit;
    }
}
add_action( 'template_redirect', 'promenebebe_block_author_enum' );

/**
 * Filtre la REST API : empêche /wp-json/wp/v2/users de lister les comptes
 * pour les visiteurs non authentifiés.
 */
function promenebebe_restrict_rest_users( $result ) {
    if ( ! empty( $result ) ) {
        return $result;
    }
    if ( is_user_logged_in() ) {
        return $result;
    }
    $route = isset( $GLOBALS['wp']->query_vars['rest_route'] )
        ? (string) $GLOBALS['wp']->query_vars['rest_route']
        : '';
    if ( preg_match( '#^/wp/v2/users(/|$)#', $route ) ) {
        return new WP_Error(
            'rest_not_logged_in',
            __( 'Vous devez être authentifié pour consulter cette ressource.', 'promenebebe' ),
            array( 'status' => 401 )
        );
    }
    return $result;
}
add_filter( 'rest_authentication_errors', 'promenebebe_restrict_rest_users' );

/**
 * Désactive XML-RPC déjà fait dans functions.php. On bloque ici les
 * pingbacks côté HTTP (méthode courante d'amplification d'attaque).
 */
add_filter( 'wp_xmlrpc_server_class', '__return_false' );

/**
 * En-têtes de sécurité HTTP côté frontend.
 *
 * Approche conservative : on n'envoie pas de Content-Security-Policy
 * stricte ici (risque de casser des intégrations Affilizz / fonts).
 * Le CSP final sera défini lorsque les sources externes seront figées.
 */
function promenebebe_security_headers() {
    if ( is_admin() ) {
        return;
    }
    /* Évite que le site soit chargé dans une iframe étrangère (clickjacking). */
    header( 'X-Frame-Options: SAMEORIGIN' );
    /* Empêche le sniffing MIME. */
    header( 'X-Content-Type-Options: nosniff' );
    /* Politique référent moderne (cohérente avec confidentialité). */
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    /* Désactive certaines features sensibles si pas utilisées. */
    header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), interest-cohort=()' );
}
add_action( 'send_headers', 'promenebebe_security_headers' );

/**
 * Réduit les détails techniques renvoyés sur la page de login en cas d'erreur.
 * Un message générique évite de signaler à un attaquant si le login existe.
 */
function promenebebe_generic_login_error() {
    return __( 'Identifiants incorrects. Réessayez ou utilisez l\'option « Mot de passe oublié ? ».', 'promenebebe' );
}
add_filter( 'login_errors', 'promenebebe_generic_login_error' );
