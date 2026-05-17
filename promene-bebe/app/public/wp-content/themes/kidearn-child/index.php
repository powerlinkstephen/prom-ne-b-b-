<?php
/**
 * Template principal (fallback) — Promène Bébé.
 *
 * Utilisé si aucun template plus spécifique ne s'applique. Page d'accueil
 * en mode "Derniers articles" sans hero (le hero est sur front-page.php
 * quand la home est définie sur "Vos derniers articles").
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<section class="pb-section">
    <div class="pb-container">

        <?php if ( is_home() && ! is_front_page() ) : ?>
            <header class="pb-feed__head">
                <h1 class="pb-feed__title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <?php if ( have_posts() ) : ?>
            <div class="pb-grid-articles">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/card' ); ?>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination( array(
                'prev_text' => __( '&laquo; Précédent', 'promenebebe' ),
                'next_text' => __( 'Suivant &raquo;', 'promenebebe' ),
                'class'     => 'pb-pagination',
            ) );
            ?>

        <?php else : ?>
            <p class="pb-empty"><?php esc_html_e( 'Aucun article pour le moment.', 'promenebebe' ); ?></p>
        <?php endif; ?>

    </div>
</section>

<?php
get_footer();
