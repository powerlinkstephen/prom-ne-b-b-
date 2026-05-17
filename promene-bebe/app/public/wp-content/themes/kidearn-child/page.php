<?php
/**
 * Template page statique — Promène Bébé.
 *
 * Utilisé pour les pages institutionnelles (mentions légales, politique de
 * confidentialité, divulgation d'affiliation, contact, etc.).
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

    <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'pb-article pb-page' ); ?>>
            <header class="pb-article__header">
                <h1 class="pb-article__title"><?php the_title(); ?></h1>
            </header>

            <div class="pb-article__body pb-article__body--full">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php
get_footer();
