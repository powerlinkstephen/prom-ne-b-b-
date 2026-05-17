<?php
/**
 * Carte article — Promène Bébé.
 *
 * Utilise la structure de carte native de Kidearn (.blog-card.blog-card-two)
 * pour bénéficier automatiquement de l'ensemble du styling parent
 * (image hover en couches, badge catégorie, transitions, etc.), recolorisé
 * via les CSS custom-properties surchargées dans assets/css/main.css.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$pb_cats        = get_the_category();
$pb_primary_cat = ! empty( $pb_cats ) ? $pb_cats[0] : null;
$pb_thumb_url   = get_the_post_thumbnail_url( null, 'kidearn_blog_770X449' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="blog-card blog-card-two wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="000ms">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="blog-card__image">
                <?php the_post_thumbnail( 'kidearn_blog_770X449', array(
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                    'alt'      => esc_attr( get_the_title() ),
                ) ); ?>
                <?php if ( $pb_thumb_url ) : ?>
                    <div class="blog-card__image__layer" style="background-image: url(<?php echo esc_url( $pb_thumb_url ); ?>);"></div>
                    <div class="blog-card__image__layer" style="background-image: url(<?php echo esc_url( $pb_thumb_url ); ?>);"></div>
                    <div class="blog-card__image__layer" style="background-image: url(<?php echo esc_url( $pb_thumb_url ); ?>);"></div>
                    <div class="blog-card__image__layer" style="background-image: url(<?php echo esc_url( $pb_thumb_url ); ?>);"></div>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>" class="blog-card__image__link">
                    <span class="screen-reader-text"><?php the_title(); ?></span>
                </a>
            </div>
        <?php endif; ?>

        <div class="blog-card__content">
            <div class="blog-card__content__top">
                <?php if ( $pb_primary_cat ) : ?>
                    <a class="blog-card__category" href="<?php echo esc_url( get_category_link( $pb_primary_cat->term_id ) ); ?>">
                        <?php echo esc_html( $pb_primary_cat->name ); ?>
                    </a>
                <?php endif; ?>
                <div class="blog-card__date">
                    <i class="fa fa-clock" aria-hidden="true"></i>
                    <?php echo esc_html( get_the_date() ); ?>
                </div>
            </div>

            <h3 class="blog-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>

            <p class="blog-card-two__text">
                <?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?>
            </p>

            <div class="blog-card__content__bottom">
                <a href="<?php the_permalink(); ?>" class="blog-card__link" aria-label="<?php esc_attr_e( 'Lire l\'article', 'promenebebe' ); ?>">
                    <span class="screen-reader-text"><?php esc_html_e( 'Lire l\'article', 'promenebebe' ); ?></span>
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</article>
