<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package kidearn
 */


if (!function_exists('kidearn_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function kidearn_posted_on()
    {
        $time_kidearntring = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_kidearntring = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_kidearntring = sprintf(
            $time_kidearntring,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x(' %s', 'post date', 'kidearn'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_kidearntring . '</a>'
        );

        echo '<span class="posted-on"><i class="far fa-clock"></i>' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }
endif;


if (!function_exists('kidearn_posted_by')) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function kidearn_posted_by()
    {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('%s', 'post author', 'kidearn'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline"><i class="fas fa-user-circle"></i> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }
endif;


if (!function_exists('kidearn_entry_footer')) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function kidearn_entry_footer()
    {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(' ', 'kidearn'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<span class="blog-details__tags cat-links"><span>' . esc_html__('Categories %1$s', 'kidearn') . '</span>', '</span>' . $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(' ', 'list item separator', 'kidearn'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="blog-details__tags blog-details__tags--tags tag-links"><span>' . esc_html__('Tags %1$s', 'kidearn') . '</span>', '</span>' . $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }
    }
endif;

if (!function_exists('kidearn_post_thumbnail')) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function kidearn_post_thumbnail()
    {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
?>
            <div class="blog-details__img">
                <?php the_post_thumbnail(); ?>
                <div class="blog-details__date">
                    <?php echo get_the_date(); ?>
                </div>
            </div><!-- details-image -->
        <?php else : ?>
            <div class="blog-one__image no-filter">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                    )
                );
                ?>
                <a href="<?php the_permalink(); ?>"></a>
                <div class="blog-one__date">
                    <?php echo get_the_date(); ?>
                </div><!-- /.blog-date -->
            </div><!-- /.blog-image -->
<?php
        endif; // End is_singular().
    }
endif;

if (!function_exists('kidearn_comment_count')) {
    function kidearn_comment_count()
    {
        if (!post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link"><i class="fas fa-comments"></i> ';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        esc_html__('Leave a Comment', 'kidearn') . '<span class="screen-reader-text">' . esc_html__('on', 'kidearn') . ' %s</span>',
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                )
            );
            echo '</span>';
        }
    }
}


if (!function_exists('kidearn_pagination')) :
    /**
     * Prints HTML with post pagination links.
     */
    function kidearn_pagination()
    {
        global $wp_query;
        $links = paginate_links(array(
            'current'   => max(1, get_query_var('paged')),
            'total'     => $wp_query->max_num_pages,
            'prev_text' => '<i class="fa fa-angle-left"></i>',
            'next_text' => '<i class="fa fa-angle-right"></i>',
        ));
        echo wp_kses($links, 'kidearn_allowed_tags');
    }
endif;

if (!function_exists('wp_body_open')) :
    /**
     * Shim for sites older than 5.2.
     *
     * @link https://core.trac.wordpress.org/ticket/12563
     */
    function wp_body_open()
    {
        do_action('wp_body_open');
    }
endif;
