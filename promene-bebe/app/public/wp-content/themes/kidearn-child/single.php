<?php
/**
 * Single article — Promène Bébé.
 *
 * Surcharge le single.php du parent pour :
 *   - Réutiliser la structure et les classes Kidearn (.blog-one, .blog-details,
 *     .blog-card-two) afin de bénéficier du design hérité du parent.
 *   - Injecter les exigences spécifiques au CDC : breadcrumbs Schema.org,
 *     sommaire automatique, temps de lecture, boutons de partage, bio auteur,
 *     articles similaires.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<section class="blog-one blog-one--page">
    <div class="container">
        <div class="row gutter-y-60 <?php echo esc_attr( 'full-width' == kidearn_blog_layout() ? 'justify-content-center' : '' ); ?>">

            <?php $pb_content_class = ( is_active_sidebar( 'sidebar-1' ) ) ? 'col-xl-8 col-lg-7' : 'col-xl-12 col-lg-12'; ?>
            <div class="<?php echo esc_attr( $pb_content_class ); ?>">

                <?php get_template_part( 'template-parts/breadcrumbs' ); ?>

                <?php while ( have_posts() ) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-details pb-article' ); ?>
                             itemscope itemtype="https://schema.org/Article">

                        <div class="blog-card blog-card-two">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="blog-card__image">
                                    <?php the_post_thumbnail( 'large', array(
                                        'loading'       => 'eager',
                                        'fetchpriority' => 'high',
                                        'decoding'      => 'async',
                                        'itemprop'      => 'image',
                                        'alt'           => esc_attr( get_the_title() ),
                                    ) ); ?>
                                </div>
                            <?php endif; ?>

                            <div class="blog-card__content">
                                <div class="blog-card__content__top">
                                    <?php
                                    $pb_cats = get_the_category();
                                    if ( ! empty( $pb_cats ) ) :
                                        ?>
                                        <a href="<?php echo esc_url( get_category_link( $pb_cats[0]->term_id ) ); ?>" class="blog-card__category">
                                            <?php echo esc_html( $pb_cats[0]->name ); ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="blog-card__date">
                                        <i class="fa fa-clock" aria-hidden="true"></i>
                                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished">
                                            <?php echo esc_html( get_the_date() ); ?>
                                        </time>
                                    </div>
                                </div>

                                <h1 class="blog-card__title" itemprop="headline">
                                    <?php the_title(); ?>
                                </h1>

                                <div class="pb-article__meta">
                                    <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                                        <span class="pb-sr-only"><?php esc_html_e( 'Auteur :', 'promenebebe' ); ?></span>
                                        <span itemprop="name"><?php the_author(); ?></span>
                                    </span>
                                    <span aria-hidden="true">·</span>
                                    <span>
                                        <?php
                                        $pb_reading = promenebebe_reading_time();
                                        printf(
                                            esc_html( _n( '%d min de lecture', '%d min de lecture', $pb_reading, 'promenebebe' ) ),
                                            (int) $pb_reading
                                        );
                                        ?>
                                    </span>
                                </div>

                                <div class="pb-article__layout">

                                    <aside class="pb-toc" data-pb-toc aria-labelledby="pb-toc-title">
                                        <details class="pb-toc__details" open>
                                            <summary class="pb-toc__summary">
                                                <span id="pb-toc-title"><?php esc_html_e( 'Sommaire', 'promenebebe' ); ?></span>
                                                <svg class="pb-toc__chevron" aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </summary>
                                            <nav aria-label="<?php esc_attr_e( 'Sommaire de l\'article', 'promenebebe' ); ?>">
                                                <ol class="pb-toc__list" data-pb-toc-list>
                                                    <li><?php esc_html_e( 'Chargement du sommaire…', 'promenebebe' ); ?></li>
                                                </ol>
                                            </nav>
                                        </details>
                                    </aside>

                                    <div class="pb-article__body" data-pb-article-body itemprop="articleBody">
                                        <?php
                                        the_content();

                                        wp_link_pages( array(
                                            'before' => '<nav class="pb-pagelinks" aria-label="' . esc_attr__( 'Pagination de l\'article', 'promenebebe' ) . '"><span>' . esc_html__( 'Pages :', 'promenebebe' ) . '</span>',
                                            'after'  => '</nav>',
                                        ) );
                                        ?>

                                        <?php get_template_part( 'template-parts/share' ); ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <?php if ( function_exists( 'kidearn_entry_footer' ) ) : ?>
                            <div class="blog-details__meta">
                                <?php kidearn_entry_footer(); ?>
                            </div>
                        <?php endif; ?>

                        <?php
                        $pb_author_bio = get_the_author_meta( 'description' );
                        if ( $pb_author_bio ) :
                            ?>
                            <aside class="pb-author-bio" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <div class="pb-author-bio__avatar">
                                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 96, '', '', array( 'class' => 'pb-author-bio__img' ) ); ?>
                                </div>
                                <div class="pb-author-bio__body">
                                    <h2 class="pb-author-bio__name" itemprop="name"><?php the_author(); ?></h2>
                                    <p class="pb-author-bio__desc" itemprop="description"><?php echo esc_html( $pb_author_bio ); ?></p>
                                </div>
                            </aside>
                        <?php endif; ?>

                    </article>

                    <?php
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    ?>

                <?php endwhile; ?>

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

<?php get_template_part( 'template-parts/related' ); ?>

<?php
get_footer();
