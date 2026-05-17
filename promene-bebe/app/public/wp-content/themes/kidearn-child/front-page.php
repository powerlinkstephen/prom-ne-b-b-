<?php
/**
 * Page d'accueil — Promène Bébé.
 *
 * Structure (CDC section 5.1) :
 *   - Hero slider d'articles phares
 *   - Grille flux d'articles (image, catégorie, titre, extrait, reading time, date)
 *
 * Aucune section parasite (pas de témoignages, pas de "à propos").
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

get_template_part( 'template-parts/hero-slider' );

/* Sous le hero : grille des articles récents, en excluant ceux du slider. */
$slider_args = apply_filters( 'promenebebe_hero_query_args', array(
    'posts_per_page'      => 5,
    'ignore_sticky_posts' => false,
    'post_status'         => 'publish',
) );

$slider_query = new WP_Query( $slider_args );
$exclude_ids  = wp_list_pluck( $slider_query->posts, 'ID' );
wp_reset_postdata();

$grid_args = apply_filters( 'promenebebe_home_grid_args', array(
    'posts_per_page'      => 9,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => true,
    'post__not_in'        => $exclude_ids,
) );

$grid = new WP_Query( $grid_args );
?>

<section class="pb-section pb-feed" aria-labelledby="pb-feed-title">
    <div class="pb-container">
        <header class="pb-feed__head">
            <h2 id="pb-feed-title" class="pb-feed__title">
                <?php esc_html_e( 'Derniers articles', 'promenebebe' ); ?>
            </h2>
            <a class="pb-feed__more" href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>">
                <?php esc_html_e( 'Voir tous les articles', 'promenebebe' ); ?>
                <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </header>

        <?php if ( $grid->have_posts() ) : ?>
            <div class="pb-grid-articles">
                <?php while ( $grid->have_posts() ) : $grid->the_post(); ?>
                    <?php get_template_part( 'template-parts/card' ); ?>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="pb-empty">
                <?php esc_html_e( 'Les premiers articles arrivent très bientôt. Restez connecté !', 'promenebebe' ); ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<?php
wp_reset_postdata();
get_footer();
