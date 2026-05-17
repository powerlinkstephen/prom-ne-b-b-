<?php

/**
 * Template part for displaying footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kidearn
 */
?>


<?php
$kidearn_page_id     = get_queried_object_id();
$kidearn_custom_footer_status = !empty(get_post_meta($kidearn_page_id, 'kidearn_custom_footer_status', true)) ? get_post_meta($kidearn_page_id, 'kidearn_custom_footer_status', true) : 'off';

$kidearn_custom_footer_id = '';
if ((is_page() && 'on' === $kidearn_custom_footer_status) || (is_singular('portfolio') && 'on' === $kidearn_custom_footer_status) || (is_singular('service') && 'on' === $kidearn_custom_footer_status) || (is_singular('team') && 'on' === $kidearn_custom_footer_status)) {
    $kidearn_custom_footer_id = get_post_meta($kidearn_page_id, 'kidearn_select_custom_footer', true);
} elseif ('yes' == get_theme_mod('footer_custom')) {
    $kidearn_custom_footer_id = get_theme_mod('footer_custom_post');
} else {
    $kidearn_custom_footer_id = 'default_footer';
}

$kidearn_dynamic_footer = isset($_GET['custom_footer_id']) ? $_GET['custom_footer_id'] : $kidearn_custom_footer_id;
?>


<?php if ('default_footer' == $kidearn_dynamic_footer) : ?>
    <div class="main-footer__bottom default-footer">
        <div class="container">
            <div class="main-footer__bottom__inner">
                <p class="main-footer__copyright">
                    <?php echo wp_kses(get_theme_mod('footer_copytext', esc_html__('&copy; All Copyright 2023 by Kidearn', 'kidearn')), 'kidearn_allowed_tags'); ?>
                </p>
            </div><!-- /.main-footer__inner -->
        </div><!-- /.container -->
    </div>
<?php else : ?>
    <?php echo do_shortcode('[kidearn-footer id="' . $kidearn_dynamic_footer . '"]');
    ?>
<?php endif; ?>