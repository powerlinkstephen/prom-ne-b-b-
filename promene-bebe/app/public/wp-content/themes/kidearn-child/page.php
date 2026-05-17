<?php
/**
 * Page statique (mentions légales, politique de confidentialité, etc.) —
 * Promène Bébé.
 *
 * Le parent affiche déjà un page-header (banderole supérieure) automatiquement
 * via `template-parts/layout/page-header.php` inclus dans header.php. Ici,
 * on se contente du corps de page, dans le conteneur Kidearn `.blog-one`.
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
        <div class="row gutter-y-60 justify-content-center">
            <div class="col-xl-10">

                <?php get_template_part( 'template-parts/breadcrumbs' ); ?>

                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-details pb-article' ); ?>>
                        <div class="pb-article__body">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>

            </div>
        </div>
    </div>
</section>

<?php
get_footer();
