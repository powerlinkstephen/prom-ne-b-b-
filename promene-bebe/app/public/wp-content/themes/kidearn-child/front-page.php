<?php
/**
 * Page d'accueil — Promène Bébé.
 *
 * Structure (CDC § 5.1) :
 *   - Hero slider d'articles phares (slider natif Promène Bébé, indépendant
 *     d'owl-carousel — non chargé sans le plugin Kidearn Addon).
 *   - Grille des derniers articles avec les cartes natives Kidearn
 *     (.blog-card.blog-card-two) recolorisées par notre palette.
 *
 * Pas de témoignages, pas de "à propos" : la home est dédiée aux articles.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

get_template_part( 'template-parts/hero-slider' );

/* Articles déjà présents dans le slider — à exclure de la grille. */
$pb_slider_args = apply_filters( 'promenebebe_hero_query_args', array(
    'posts_per_page'      => 5,
    'ignore_sticky_posts' => false,
    'post_status'         => 'publish',
) );
$pb_slider_q    = new WP_Query( $pb_slider_args );
$pb_exclude_ids = wp_list_pluck( $pb_slider_q->posts, 'ID' );
wp_reset_postdata();

$pb_grid_args = apply_filters( 'promenebebe_home_grid_args', array(
    'posts_per_page'      => 9,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => true,
    'post__not_in'        => $pb_exclude_ids,
) );
$pb_grid = new WP_Query( $pb_grid_args );
?>

<section class="blog-one blog-one--page pb-section">
    <div class="container">

        <div class="pb-section-head">
            <h2><?php esc_html_e( 'Derniers articles', 'promenebebe' ); ?></h2>
            <a class="pb-feed__more" href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>">
                <?php esc_html_e( 'Voir tous les articles', 'promenebebe' ); ?>
                <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <?php if ( $pb_grid->have_posts() ) : ?>
            <div class="row gutter-y-60">
                <?php while ( $pb_grid->have_posts() ) : $pb_grid->the_post(); ?>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <?php get_template_part( 'template-parts/card' ); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="pb-empty">
                <?php esc_html_e( 'Les premiers articles arrivent très bientôt. Restez connecté !', 'promenebebe' ); ?>
            </p>
        <?php endif; ?>

    </div>
</section>

<section class="pb-section" style="background-color: var(--pb-pastel-light);">
    <div class="pb-container">
        <div class="pb-newsletter">
            <h2 class="pb-newsletter__title"><?php esc_html_e( 'Notre lettre poussette', 'promenebebe' ); ?></h2>
            <p class="pb-newsletter__desc">
                <?php esc_html_e( 'Un e-mail par mois, des comparatifs et guides utiles. Zéro spam, désabonnement en un clic.', 'promenebebe' ); ?>
            </p>
            <?php get_template_part( 'template-parts/newsletter' ); ?>
        </div>
    </div>
</section>

<?php
wp_reset_postdata();
get_footer();
