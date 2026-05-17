<?php
/**
 * Breadcrumbs — Promène Bébé.
 *
 * Affiche un fil d'Ariane sémantique + microdonnées Schema.org
 * (BreadcrumbList). Pas de dépendance plugin.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( is_front_page() ) {
    return;
}

$crumbs   = array();
$crumbs[] = array(
    'url'   => home_url( '/' ),
    'label' => __( 'Accueil', 'promenebebe' ),
);

if ( is_singular( 'post' ) ) {
    $cats = get_the_category();
    if ( ! empty( $cats ) ) {
        $crumbs[] = array(
            'url'   => get_category_link( $cats[0]->term_id ),
            'label' => $cats[0]->name,
        );
    }
    $crumbs[] = array(
        'url'   => '',
        'label' => get_the_title(),
    );
} elseif ( is_singular( 'page' ) ) {
    $crumbs[] = array(
        'url'   => '',
        'label' => get_the_title(),
    );
} elseif ( is_category() ) {
    $crumbs[] = array(
        'url'   => '',
        'label' => single_cat_title( '', false ),
    );
} elseif ( is_tag() ) {
    $crumbs[] = array(
        'url'   => '',
        'label' => single_tag_title( '', false ),
    );
} elseif ( is_search() ) {
    $crumbs[] = array(
        'url'   => '',
        'label' => sprintf( __( 'Recherche : %s', 'promenebebe' ), get_search_query() ),
    );
} elseif ( is_404() ) {
    $crumbs[] = array(
        'url'   => '',
        'label' => __( 'Page introuvable', 'promenebebe' ),
    );
} elseif ( is_archive() ) {
    $crumbs[] = array(
        'url'   => '',
        'label' => get_the_archive_title(),
    );
}
?>
<nav class="pb-breadcrumbs" aria-label="<?php esc_attr_e( 'Fil d\'Ariane', 'promenebebe' ); ?>">
    <ol class="pb-breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ( $crumbs as $i => $crumb ) :
            $position = $i + 1;
            $is_last  = ( $i === count( $crumbs ) - 1 );
            ?>
            <li class="pb-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <?php if ( $crumb['url'] && ! $is_last ) : ?>
                    <a href="<?php echo esc_url( $crumb['url'] ); ?>" itemprop="item">
                        <span itemprop="name"><?php echo esc_html( $crumb['label'] ); ?></span>
                    </a>
                <?php else : ?>
                    <span itemprop="name" aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span>
                <?php endif; ?>
                <meta itemprop="position" content="<?php echo (int) $position; ?>" />
                <?php if ( ! $is_last ) : ?>
                    <span class="pb-breadcrumbs__sep" aria-hidden="true">/</span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
