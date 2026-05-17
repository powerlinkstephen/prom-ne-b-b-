<?php
/**
 * SEO, Open Graph, Twitter Cards, JSON-LD Schema.org — Promène Bébé.
 *
 * Stratégie défensive : ne produit du contenu QUE si aucun plugin SEO
 * concurrent n'est actif (Rank Math, Yoast, AIOSEO, SEOPress). Ces plugins,
 * une fois installés, prennent le relais avec une couverture supérieure.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Détecte si un plugin SEO produit déjà l'OG/Twitter/JSON-LD.
 */
function promenebebe_has_seo_plugin() {
    if ( class_exists( 'RankMath' ) ) {
        return true;
    }
    if ( defined( 'WPSEO_VERSION' ) ) {
        return true;
    }
    if ( defined( 'AIOSEO_VERSION' ) ) {
        return true;
    }
    if ( defined( 'SEOPRESS_VERSION' ) ) {
        return true;
    }
    return false;
}

/**
 * Image OG par défaut.
 */
function promenebebe_default_og_image() {
    return apply_filters(
        'promenebebe_default_og_image',
        promenebebe_asset( 'img/og-default.png' )
    );
}

/**
 * Renvoie l'URL d'image pertinente pour la page courante.
 */
function promenebebe_current_og_image() {
    if ( is_singular() && has_post_thumbnail() ) {
        $src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
        if ( ! empty( $src[0] ) ) {
            return $src[0];
        }
    }
    return promenebebe_default_og_image();
}

/**
 * Renvoie une description SEO de la page courante.
 */
function promenebebe_current_description() {
    if ( is_singular() ) {
        $excerpt = get_the_excerpt();
        if ( $excerpt ) {
            return wp_strip_all_tags( $excerpt );
        }
        $content = wp_strip_all_tags( get_the_content() );
        return mb_substr( $content, 0, 160 );
    }
    if ( is_category() || is_tag() || is_tax() ) {
        $desc = term_description();
        if ( $desc ) {
            return wp_strip_all_tags( $desc );
        }
    }
    return get_bloginfo( 'description' );
}

/**
 * Injecte les balises Open Graph, Twitter Cards et description.
 */
function promenebebe_output_meta_social() {
    if ( promenebebe_has_seo_plugin() ) {
        return;
    }

    $site_name   = get_bloginfo( 'name' );
    $description = promenebebe_current_description();
    $image       = promenebebe_current_og_image();
    $url         = is_singular() ? get_permalink() : home_url( add_query_arg( null, null ) );

    if ( is_singular() ) {
        $title = single_post_title( '', false );
    } elseif ( is_archive() ) {
        $title = wp_strip_all_tags( get_the_archive_title() );
    } else {
        $title = $site_name;
    }

    $title_full = ( $title === $site_name )
        ? $site_name
        : $title . ' — ' . $site_name;
    ?>
    <meta name="description" content="<?php echo esc_attr( $description ); ?>">

    <!-- Open Graph -->
    <meta property="og:type"        content="<?php echo is_singular( 'post' ) ? 'article' : 'website'; ?>">
    <meta property="og:locale"      content="fr_FR">
    <meta property="og:site_name"   content="<?php echo esc_attr( $site_name ); ?>">
    <meta property="og:title"       content="<?php echo esc_attr( $title_full ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
    <meta property="og:url"         content="<?php echo esc_url( $url ); ?>">
    <meta property="og:image"       content="<?php echo esc_url( $image ); ?>">

    <?php if ( is_singular( 'post' ) ) :
        $author_name = get_the_author_meta( 'display_name' );
        ?>
        <meta property="article:published_time" content="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
        <meta property="article:modified_time"  content="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>">
        <?php if ( $author_name ) : ?>
            <meta property="article:author"     content="<?php echo esc_attr( $author_name ); ?>">
        <?php endif; ?>
        <?php
        $cats = get_the_category();
        foreach ( $cats as $cat ) :
            ?>
            <meta property="article:section" content="<?php echo esc_attr( $cat->name ); ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?php echo esc_attr( $title_full ); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>">
    <meta name="twitter:image"       content="<?php echo esc_url( $image ); ?>">

    <?php
}
add_action( 'wp_head', 'promenebebe_output_meta_social', 6 );

/**
 * Injecte le Schema.org JSON-LD : Article, BreadcrumbList, Organization.
 */
function promenebebe_output_jsonld() {
    if ( promenebebe_has_seo_plugin() ) {
        return;
    }

    $site_name = get_bloginfo( 'name' );
    $home_url  = home_url( '/' );
    $logo_url  = promenebebe_logo_url();

    /* Bloc Organization : toujours présent. */
    $organization = array(
        '@type'  => 'Organization',
        '@id'    => $home_url . '#organization',
        'name'   => $site_name,
        'url'    => $home_url,
        'logo'   => array(
            '@type' => 'ImageObject',
            'url'   => $logo_url,
        ),
    );

    /* Bloc WebSite avec SearchAction. */
    $website = array(
        '@type'           => 'WebSite',
        '@id'             => $home_url . '#website',
        'name'            => $site_name,
        'url'             => $home_url,
        'inLanguage'      => 'fr-FR',
        'publisher'       => array( '@id' => $home_url . '#organization' ),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'       => 'EntryPoint',
                'urlTemplate' => $home_url . '?s={search_term_string}',
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );

    $graph = array( $organization, $website );

    /* Bloc Article (sur les articles). */
    if ( is_singular( 'post' ) ) {
        $post_id   = get_the_ID();
        $author_id = (int) get_post_field( 'post_author', $post_id );
        $image     = promenebebe_current_og_image();

        $graph[] = array(
            '@type'            => 'Article',
            '@id'              => get_permalink( $post_id ) . '#article',
            'mainEntityOfPage' => get_permalink( $post_id ),
            'headline'         => wp_strip_all_tags( get_the_title( $post_id ) ),
            'description'      => promenebebe_current_description(),
            'image'            => array(
                '@type' => 'ImageObject',
                'url'   => $image,
            ),
            'datePublished'    => get_the_date( 'c', $post_id ),
            'dateModified'     => get_the_modified_date( 'c', $post_id ),
            'author'           => array(
                '@type' => 'Person',
                'name'  => get_the_author_meta( 'display_name', $author_id ),
                'url'   => get_author_posts_url( $author_id ),
            ),
            'publisher'        => array( '@id' => $home_url . '#organization' ),
            'inLanguage'       => 'fr-FR',
        );

        /* BreadcrumbList sur les articles. */
        $cats = get_the_category();
        $breadcrumb_items = array(
            array(
                '@type'    => 'ListItem',
                'position' => 1,
                'name'     => __( 'Accueil', 'promenebebe' ),
                'item'     => $home_url,
            ),
        );
        $position = 2;
        if ( ! empty( $cats ) ) {
            $breadcrumb_items[] = array(
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $cats[0]->name,
                'item'     => get_category_link( $cats[0]->term_id ),
            );
        }
        $breadcrumb_items[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => wp_strip_all_tags( get_the_title( $post_id ) ),
        );

        $graph[] = array(
            '@type'           => 'BreadcrumbList',
            '@id'             => get_permalink( $post_id ) . '#breadcrumb',
            'itemListElement' => $breadcrumb_items,
        );
    }

    $jsonld = array(
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    );

    echo '<script type="application/ld+json">';
    echo wp_json_encode( $jsonld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    echo '</script>' . "\n";
}
add_action( 'wp_head', 'promenebebe_output_jsonld', 7 );

/**
 * Ajoute le sitemap WordPress (et celui de Rank Math s'il existe) au robots.txt.
 */
function promenebebe_robots_txt( $output, $public ) {
    if ( '0' === (string) $public ) {
        return $output; /* Site en dev : laissons WP générer le Disallow par défaut. */
    }
    $sitemap = home_url( '/wp-sitemap.xml' );
    $output .= "Sitemap: " . $sitemap . "\n";
    return $output;
}
add_filter( 'robots_txt', 'promenebebe_robots_txt', 10, 2 );

/**
 * Préload des polices critiques (Quicksand 600 + Inter 400) si disponibles
 * en local. Ici on s'appuie sur Google Fonts, qui sert déjà des woff2 ;
 * on ajoute préconnexions seulement (déjà fait dans functions.php).
 *
 * NB : un futur passage en self-hosted permettra un vrai preload.
 */

/**
 * Désactive le heartbeat sur le frontend (perf — l'admin garde son heartbeat).
 */
function promenebebe_disable_heartbeat_frontend() {
    if ( ! is_admin() ) {
        wp_deregister_script( 'heartbeat' );
    }
}
add_action( 'init', 'promenebebe_disable_heartbeat_frontend', 1 );
