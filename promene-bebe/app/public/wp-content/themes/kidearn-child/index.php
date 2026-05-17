<?php
/**
 * Index (fallback / liste articles) — Promène Bébé.
 *
 * Réutilise la structure du parent Kidearn (`.blog-one`, ligne Bootstrap)
 * mais affiche les articles via notre template-parts/card.php (qui s'appuie
 * sur `.blog-card.blog-card-two` natif). On bénéficie ainsi d'une grille
 * cohérente avec la home et les archives.
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

        <?php if ( is_home() && ! is_front_page() ) : ?>
            <div class="pb-section-head">
                <h2><?php single_post_title(); ?></h2>
            </div>
        <?php endif; ?>

        <div class="row gutter-y-60 <?php echo esc_attr( 'full-width' == kidearn_blog_layout() ? 'justify-content-center' : '' ); ?>">

            <?php $pb_content_class = ( is_active_sidebar( 'sidebar-1' ) ) ? 'col-xl-8 col-lg-7' : 'col-xl-12 col-lg-12'; ?>
            <div class="<?php echo esc_attr( $pb_content_class ); ?>">

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
                    <p class="pb-empty"><?php esc_html_e( 'Aucun article pour le moment.', 'promenebebe' ); ?></p>
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
