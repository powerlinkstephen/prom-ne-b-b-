<?php
/**
 * Archives (catégorie, tag, auteur, date) — Promène Bébé.
 *
 * Hérite du page-header Kidearn (rendu par parent's header.php), puis
 * affiche une grille de cartes natives Kidearn (.blog-card.blog-card-two).
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<section class="blog-one blog-one--page pb-section">
    <div class="container">

        <div class="row gutter-y-60 <?php echo esc_attr( 'full-width' == kidearn_blog_layout() ? 'justify-content-center' : '' ); ?>">

            <?php $pb_content_class = ( is_active_sidebar( 'sidebar-1' ) ) ? 'col-xl-8 col-lg-7' : 'col-xl-12 col-lg-12'; ?>
            <div class="<?php echo esc_attr( $pb_content_class ); ?>">

                <?php
                /* Description de catégorie/tag (utile en SEO et UX). */
                $pb_desc = get_the_archive_description();
                if ( $pb_desc ) :
                    ?>
                    <div class="pb-archive-desc">
                        <?php echo wp_kses_post( $pb_desc ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( have_posts() ) : ?>
                    <div class="row gutter-y-60">
                        <?php while ( have_posts() ) : the_post(); ?>
                            <div class="col-xl-6 col-lg-12 col-md-6">
                                <?php get_template_part( 'template-parts/card' ); ?>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <?php
                    the_posts_pagination( array(
                        'prev_text' => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                        'next_text' => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
                        'class'     => 'pb-pagination',
                    ) );
                    ?>
                <?php else : ?>
                    <p class="pb-empty"><?php esc_html_e( 'Aucun article dans cette section pour le moment.', 'promenebebe' ); ?></p>
                <?php endif; ?>

            </div>

            <?php if ( is_active_sidebar( 'sidebar-1' ) && 'full-width' != kidearn_blog_layout() ) : ?>
                <div class="col-lg-4 <?php echo esc_attr( kidearn_blog_layout() ); ?>">
                    <div class="sidebar">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</section>

<?php
get_footer();
