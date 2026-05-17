<?php
/**
 * Header — Promène Bébé.
 *
 * Surcharge complète du header parent : on rend uniquement les éléments
 * dont nous avons besoin pour respecter l'identité Promène Bébé (logo,
 * menu, recherche, bascule mode sombre) et un balisage HTML5 sémantique.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#18454A" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0F2426" media="(prefers-color-scheme: dark)">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="pb-skip-link" href="#pb-main"><?php esc_html_e( 'Aller au contenu principal', 'promenebebe' ); ?></a>

<header class="pb-header" role="banner">
    <div class="pb-container pb-header__inner">

        <a class="pb-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Promène Bébé — Retour à l\'accueil', 'promenebebe' ); ?>">
            <img class="pb-logo-light" src="<?php echo esc_url( promenebebe_logo_url() ); ?>"
                 alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                 width="220" height="64" decoding="async">
        </a>

        <nav class="pb-header__nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'promenebebe' ); ?>">
            <?php
            if ( has_nav_menu( 'promenebebe_primary' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'promenebebe_primary',
                    'container'      => false,
                    'menu_class'     => 'pb-nav',
                    'depth'          => 2,
                    'fallback_cb'    => false,
                ) );
            } elseif ( has_nav_menu( 'menu-1' ) ) {
                /* Compat : si l'utilisateur n'a pas encore basculé sur les locations enfant. */
                wp_nav_menu( array(
                    'theme_location' => 'menu-1',
                    'container'      => false,
                    'menu_class'     => 'pb-nav',
                    'depth'          => 2,
                    'fallback_cb'    => false,
                ) );
            } else {
                ?>
                <ul class="pb-nav pb-nav--placeholder">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'promenebebe' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/poussettes-citadines/' ) ); ?>"><?php esc_html_e( 'Catégories', 'promenebebe' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/guides-dachat/' ) ); ?>"><?php esc_html_e( 'Guides d\'achat', 'promenebebe' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/comparatifs/' ) ); ?>"><?php esc_html_e( 'Comparatifs', 'promenebebe' ); ?></a></li>
                </ul>
                <?php
            }
            ?>
        </nav>

        <div class="pb-header__actions">
            <button type="button" class="pb-iconbtn" data-pb-search-toggle aria-controls="pb-search" aria-expanded="false" aria-label="<?php esc_attr_e( 'Ouvrir la recherche', 'promenebebe' ); ?>">
                <span class="pb-sr-only"><?php esc_html_e( 'Recherche', 'promenebebe' ); ?></span>
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>

            <?php echo promenebebe_theme_toggle_html(); ?>

            <button type="button" class="pb-iconbtn pb-header__menu-toggle" data-pb-menu-toggle aria-controls="pb-mobile-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'promenebebe' ); ?>">
                <span class="pb-sr-only"><?php esc_html_e( 'Menu', 'promenebebe' ); ?></span>
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3"  y1="6"  x2="21" y2="6"></line>
                    <line x1="3"  y1="12" x2="21" y2="12"></line>
                    <line x1="3"  y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>

    </div>

    <div class="pb-search" id="pb-search" hidden>
        <div class="pb-container">
            <?php get_search_form(); ?>
        </div>
    </div>
</header>

<main id="pb-main" class="pb-main" role="main">
