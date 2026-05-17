<?php
/**
 * Carte article — Promène Bébé.
 *
 * Affiche un article au format carte (image, catégorie, titre, extrait,
 * temps de lecture, date). Utilisée par les grilles (home, archives,
 * articles similaires).
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$categories = get_the_category();
$primary_cat = ! empty( $categories ) ? $categories[0] : null;
$reading_min = promenebebe_reading_time( get_the_ID() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'pb-card' ); ?>>
    <a class="pb-card__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'medium_large', array(
                'loading'  => 'lazy',
                'decoding' => 'async',
                'alt'      => esc_attr( get_the_title() ),
            ) ); ?>
        <?php else : ?>
            <img src="<?php echo esc_url( promenebebe_asset( 'img/logo.png' ) ); ?>"
                 alt="" loading="lazy" decoding="async">
        <?php endif; ?>
    </a>

    <div class="pb-card__body">
        <?php if ( $primary_cat ) : ?>
            <a class="pb-card__category" href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>">
                <?php echo esc_html( $primary_cat->name ); ?>
            </a>
        <?php endif; ?>

        <h3 class="pb-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <div class="pb-card__excerpt">
            <?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 24, '…' ) ); ?>
        </div>

        <footer class="pb-card__meta">
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                <?php echo esc_html( get_the_date() ); ?>
            </time>
            <span aria-hidden="true">·</span>
            <span>
                <?php
                printf(
                    /* translators: %d : nombre de minutes */
                    esc_html( _n( '%d min de lecture', '%d min de lecture', $reading_min, 'promenebebe' ) ),
                    (int) $reading_min
                );
                ?>
            </span>
        </footer>
    </div>
</article>
