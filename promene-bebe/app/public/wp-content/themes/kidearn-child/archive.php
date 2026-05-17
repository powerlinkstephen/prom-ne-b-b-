<?php
/**
 * Template archive (catégorie, tag, date, auteur) — Promène Bébé.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<div class="pb-container pb-article-wrap">
    <?php get_template_part( 'template-parts/breadcrumbs' ); ?>

    <header class="pb-archive__header">
        <p class="pb-hero__eyebrow">
            <?php
            if ( is_category() ) {
                esc_html_e( 'Catégorie', 'promenebebe' );
            } elseif ( is_tag() ) {
                esc_html_e( 'Étiquette', 'promenebebe' );
            } elseif ( is_author() ) {
                esc_html_e( 'Auteur', 'promenebebe' );
            } else {
                esc_html_e( 'Archive', 'promenebebe' );
            }
            ?>
        </p>
        <h1 class="pb-archive__title"><?php echo wp_kses_post( get_the_archive_title() ); ?></h1>
        <?php
        $desc = get_the_archive_description();
        if ( $desc ) :
            ?>
            <div class="pb-archive__desc"><?php echo wp_kses_post( $desc ); ?></div>
        <?php endif; ?>
    </header>

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
        <p class="pb-empty"><?php esc_html_e( 'Aucun article dans cette section pour le moment.', 'promenebebe' ); ?></p>
    <?php endif; ?>
</div>

<?php
get_footer();
