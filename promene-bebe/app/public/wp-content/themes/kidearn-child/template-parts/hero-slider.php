<?php
/**
 * Hero slider — Promène Bébé.
 *
 * Carrousel d'articles phares. Approche :
 *  - CSS scroll-snap pour le défilement tactile / souris (gratuit, performant)
 *  - Boutons prev / next pour l'accessibilité clavier
 *  - Indicateurs de position cliquables
 *  - Pas de plugin externe (perf, sécurité, contrôle total)
 *
 * Sélection des articles :
 *  - Par défaut, les 5 derniers articles publiés (sticky en priorité).
 *  - Filtre `promenebebe_hero_query_args` pour personnaliser.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$default_args = array(
    'posts_per_page'      => 5,
    'ignore_sticky_posts' => false,
    'post_status'         => 'publish',
);

$args  = apply_filters( 'promenebebe_hero_query_args', $default_args );
$query = new WP_Query( $args );

if ( ! $query->have_posts() ) {
    return;
}
?>
<section class="pb-hero" aria-labelledby="pb-hero-title">
    <div class="pb-container">
        <header class="pb-hero__head">
            <p class="pb-hero__eyebrow"><?php esc_html_e( 'À la une', 'promenebebe' ); ?></p>
            <h1 id="pb-hero-title" class="pb-hero__title">
                <?php esc_html_e( 'Choisir la bonne poussette, en toute confiance.', 'promenebebe' ); ?>
            </h1>
            <p class="pb-hero__subtitle">
                <?php esc_html_e( 'Comparatifs honnêtes, guides d\'achat et conseils empathiques pour vous accompagner à chaque étape.', 'promenebebe' ); ?>
            </p>
        </header>

        <div class="pb-hero__slider"
             data-pb-slider
             role="region"
             aria-roledescription="<?php esc_attr_e( 'carrousel', 'promenebebe' ); ?>"
             aria-label="<?php esc_attr_e( 'Articles phares', 'promenebebe' ); ?>">
            <button type="button"
                    class="pb-hero__nav pb-hero__nav--prev"
                    data-pb-slider-prev
                    aria-label="<?php esc_attr_e( 'Article phare précédent', 'promenebebe' ); ?>">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <ul class="pb-hero__track" data-pb-slider-track>
                <?php
                $i = 0;
                while ( $query->have_posts() ) :
                    $query->the_post();
                    $i++;
                    ?>
                    <li class="pb-hero__slide"
                        role="group"
                        aria-roledescription="<?php esc_attr_e( 'diapositive', 'promenebebe' ); ?>"
                        aria-label="<?php
                            printf(
                                /* translators: 1 : index courant, 2 : total */
                                esc_attr__( '%1$d sur %2$d', 'promenebebe' ),
                                $i,
                                (int) $query->post_count
                            );
                        ?>">
                        <a class="pb-hero__slide-link" href="<?php the_permalink(); ?>">
                            <div class="pb-hero__slide-media">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'large', array(
                                        'loading'  => $i === 1 ? 'eager' : 'lazy',
                                        'fetchpriority' => $i === 1 ? 'high' : 'auto',
                                        'decoding' => 'async',
                                        'alt'      => esc_attr( get_the_title() ),
                                    ) ); ?>
                                <?php endif; ?>
                            </div>
                            <div class="pb-hero__slide-content">
                                <?php
                                $cats = get_the_category();
                                if ( ! empty( $cats ) ) :
                                    ?>
                                    <span class="pb-badge"><?php echo esc_html( $cats[0]->name ); ?></span>
                                <?php endif; ?>
                                <h2 class="pb-hero__slide-title"><?php the_title(); ?></h2>
                                <p class="pb-hero__slide-excerpt">
                                    <?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?>
                                </p>
                                <span class="pb-btn pb-btn--alt pb-hero__slide-cta">
                                    <?php esc_html_e( 'Lire l\'article', 'promenebebe' ); ?>
                                    <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </li>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </ul>

            <button type="button"
                    class="pb-hero__nav pb-hero__nav--next"
                    data-pb-slider-next
                    aria-label="<?php esc_attr_e( 'Article phare suivant', 'promenebebe' ); ?>">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

            <ol class="pb-hero__dots" data-pb-slider-dots aria-label="<?php esc_attr_e( 'Sélecteur de diapositive', 'promenebebe' ); ?>">
                <?php for ( $d = 1; $d <= $query->post_count; $d++ ) : ?>
                    <li>
                        <button type="button"
                                data-pb-slider-dot="<?php echo (int) ( $d - 1 ); ?>"
                                aria-label="<?php
                                    printf(
                                        /* translators: %d : index 1-based */
                                        esc_attr__( 'Aller à la diapositive %d', 'promenebebe' ),
                                        $d
                                    );
                                ?>"
                                <?php echo $d === 1 ? 'aria-current="true"' : ''; ?>></button>
                    </li>
                <?php endfor; ?>
            </ol>
        </div>
    </div>
</section>
