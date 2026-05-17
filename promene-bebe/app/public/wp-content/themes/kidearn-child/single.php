<?php
/**
 * Template article (single) — Promène Bébé.
 *
 * Inclut : breadcrumbs, titre, méta (auteur, date, reading-time, catégorie),
 * image à la une, sommaire automatique (TOC), corps article, partages
 * sociaux sticky, bio auteur (optionnelle), articles similaires,
 * commentaires natifs.
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

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'pb-article' ); ?> itemscope itemtype="https://schema.org/Article">

            <header class="pb-article__header">
                <?php
                $cats = get_the_category();
                if ( ! empty( $cats ) ) :
                    ?>
                    <a class="pb-badge pb-article__category" href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>">
                        <?php echo esc_html( $cats[0]->name ); ?>
                    </a>
                <?php endif; ?>

                <h1 class="pb-article__title" itemprop="headline"><?php the_title(); ?></h1>

                <div class="pb-article__meta">
                    <span class="pb-article__author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <span class="pb-sr-only"><?php esc_html_e( 'Auteur :', 'promenebebe' ); ?></span>
                        <span itemprop="name"><?php the_author(); ?></span>
                    </span>
                    <span aria-hidden="true">·</span>
                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished">
                        <?php echo esc_html( get_the_date() ); ?>
                    </time>
                    <span aria-hidden="true">·</span>
                    <span>
                        <?php
                        $r = promenebebe_reading_time();
                        printf(
                            esc_html( _n( '%d min de lecture', '%d min de lecture', $r, 'promenebebe' ) ),
                            (int) $r
                        );
                        ?>
                    </span>
                </div>

                <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="pb-article__cover">
                        <?php the_post_thumbnail( 'large', array(
                            'loading'       => 'eager',
                            'fetchpriority' => 'high',
                            'decoding'      => 'async',
                            'itemprop'      => 'image',
                            'alt'           => esc_attr( get_the_title() ),
                        ) ); ?>
                    </figure>
                <?php endif; ?>
            </header>

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
                                <li class="pb-toc__placeholder"><?php esc_html_e( 'Chargement du sommaire…', 'promenebebe' ); ?></li>
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

            <?php
            $author_bio = get_the_author_meta( 'description' );
            if ( $author_bio ) :
                ?>
                <aside class="pb-author-bio" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <div class="pb-author-bio__avatar">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 96, '', '', array( 'class' => 'pb-author-bio__img' ) ); ?>
                    </div>
                    <div class="pb-author-bio__body">
                        <h2 class="pb-author-bio__name" itemprop="name"><?php the_author(); ?></h2>
                        <p class="pb-author-bio__desc" itemprop="description"><?php echo esc_html( $author_bio ); ?></p>
                    </div>
                </aside>
            <?php endif; ?>

        </article>

        <?php get_template_part( 'template-parts/related' ); ?>

        <?php
        if ( comments_open() || get_comments_number() ) :
            ?>
            <div class="pb-container">
                <section class="pb-comments" id="comments">
                    <?php comments_template(); ?>
                </section>
            </div>
        <?php endif; ?>

    <?php endwhile; ?>
</div>

<?php
get_footer();
