<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package kidearn
 */

get_header();

$kidearn_page_header_extra_class = apply_filters('kidearn_page_header_extra_class', 'page-header--unit-test');
?>

<?php if ('yes' == get_theme_mod('error_custom')) : ?>
    <?php echo do_shortcode(\Elementor\Plugin::$instance->frontend->get_builder_content(get_theme_mod('error_custom_post'))); ?>
<?php else : ?>
    <main id="primary" class="site-main">
        <!--Error Page Start-->
        <section class="error-404 <?php echo esc_attr($kidearn_page_header_extra_class); ?>">
            <div class="container">
                <?php if (!empty(get_theme_mod('404_page_shape_one')) || !empty(get_theme_mod('404_page_shape_two')) || !empty(get_theme_mod('404_page_shape_three'))) : ?>
                    <div class="error-404__thumb">
                        <?php if (!empty(get_theme_mod('404_page_shape_one'))) : ?>
                            <img src="<?php echo esc_url(get_theme_mod('404_page_shape_one')); ?>" alt="<?php echo esc_attr('404 image', 'kidearn'); ?>" class="error-404__thumb__one">
                        <?php endif; ?>
                        <?php if (!empty(get_theme_mod('404_page_shape_two'))) : ?>
                            <img src="<?php echo esc_url(get_theme_mod('404_page_shape_two')); ?>" alt="<?php echo esc_attr('404 image', 'kidearn'); ?>" class="error-404__thumb__two">
                        <?php endif; ?>
                        <?php if (!empty(get_theme_mod('404_page_shape_three'))) : ?>
                            <img src="<?php echo esc_url(get_theme_mod('404_page_shape_three')); ?>" alt="<?php echo esc_attr('404 image', 'kidearn'); ?>" class="error-404__thumb__bg">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <h3 class="error-404__sub-title"><?php esc_html_e('Oops! page not found', 'kidearn'); ?></h3><!-- /.error-404__title -->
                <p class="error-404__text"><?php esc_html_e('The page you are looking for is not exist.', 'kidearn'); ?></p><!-- /.error-404__text -->
                <form class="error-404__search" method="get" action="<?php echo esc_url(home_url()); ?>">
                    <input type="text" id="error-search" name="s" placeholder="<?php esc_attr_e('Search Here...', 'kidearn'); ?>" />
                    <button type="submit" class="error-404__search__btn" aria-label="search submit">
                        <span><i class="icon-search"></i></span>
                    </button>
                </form><!-- /.error-404__search -->
                <div class="error-404__btns">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="kidearn-btn kidearn-btn--xl error-404__btn"><span><?php esc_html_e('Back to Home', 'kidearn'); ?></span></a>
                </div><!-- /.error-404__btns -->
            </div><!-- /.container -->
        </section><!-- /.error-404 -->

        <!--Error Page End-->
    </main><!-- #main -->
<?php endif; ?>

<?php
get_footer();
