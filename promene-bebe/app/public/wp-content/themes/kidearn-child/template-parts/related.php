<?php
/**
 * Articles similaires — Promène Bébé.
 *
 * 3 à 6 articles de la même catégorie principale, hors article courant.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_id = get_the_ID();
$cats       = wp_get_post_categories( $current_id );

if ( empty( $cats ) ) {
    return;
}

$related_args = apply_filters( 'promenebebe_related_args', array(
    'post_type'           => 'post',
    'posts_per_page'      => 4,
    'post__not_in'        => array( $current_id ),
    'category__in'        => $cats,
    'orderby'             => 'rand',
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
) );

$related = new WP_Query( $related_args );
if ( ! $related->have_posts() ) {
    return;
}
?>
<section class="pb-related" aria-labelledby="pb-related-title">
    <div class="pb-container">
        <h2 id="pb-related-title" class="pb-related__title">
            <?php esc_html_e( 'Sur le même sujet', 'promenebebe' ); ?>
        </h2>
        <div class="pb-grid-articles">
            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                <?php get_template_part( 'template-parts/card' ); ?>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php
wp_reset_postdata();
