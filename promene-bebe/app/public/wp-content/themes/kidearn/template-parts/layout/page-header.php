<?php

/**
 * Template part for displaying Page Header
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kidearn
 */
?>
<?php
$kidearn_page_header_extra_class = apply_filters('kidearn_page_header_extra_class', 'page-header--unit-test');
?>
<section class="page-header <?php echo esc_attr($kidearn_page_header_extra_class); ?>">
	<div class="page-header__bg"></div><!-- /.page-header__bg -->
	<div class="page-header__shape1"></div><!-- /.page-header__shape1 -->
	<div class="page-header__shape2"></div><!-- /.page-header__shape1 -->
	<div class="page-header__shape3 wow slideInRight" data-wow-delay="300ms"></div><!-- /.page-header__shape3 -->
	<div class="container">
		<?php
		$kidearn_page_title_text = !empty(get_post_meta(get_the_ID(), 'kidearn_set_header_title', true)) ? get_post_meta(get_the_ID(), 'kidearn_set_header_title', true) : get_the_title();
		$kidearn_page_header_tag = apply_filters('kidearn_page_header_tag', 'h2');
		?>
		<<?php echo esc_attr($kidearn_page_header_tag); ?> class="page-header__title">
			<?php if (!is_page()) : ?>
				<?php kidearn_page_title(); ?>
			<?php else : ?>
				<?php echo wp_kses($kidearn_page_title_text, 'kidearn_allowed_tags') ?>
			<?php endif; ?>
		</<?php echo esc_attr($kidearn_page_header_tag); ?>><!-- /.page-title -->
		<?php $kidearn_page_meta_breadcumb_status = empty(get_post_meta(get_the_ID(), 'kidearn_show_page_breadcrumb', true)) ? 'on' : get_post_meta(get_the_ID(), 'kidearn_show_page_breadcrumb', true); ?>
		<?php if (function_exists('bcn_display_list') && 'yes' == get_theme_mod('breadcrumb_opt', 'off') && 'on' == $kidearn_page_meta_breadcumb_status) : ?>
			<ul class="page-header__breadcrumb list-unstyled ml-0">
				<?php bcn_display(); ?>
			</ul>
		<?php endif; ?>


	</div><!-- /.container -->
</section><!-- /.page-header -->