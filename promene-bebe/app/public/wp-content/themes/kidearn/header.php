<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kidearn
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<?php $kidearn_preloader_status = get_theme_mod('preloader', 'no'); ?>
	<?php if ('yes' == $kidearn_preloader_status) : ?>
		<!-- Preloader -->
		<div class="preloader">
			<div class="preloader__image"></div>
		</div>
	<?php endif; ?>
	<!-- /.preloader -->

	<div id="page" class="site page-wrapper">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'kidearn'); ?></a>

		<?php do_action('kidearn_header'); ?>

		<?php $kidearn_page_header_status = empty(get_post_meta(get_the_ID(), 'kidearn_show_page_banner', true)) ? 'on' : get_post_meta(get_the_ID(), 'kidearn_show_page_banner', true);
		?>

		<?php if (is_page() && 'on' === $kidearn_page_header_status) : ?>
			<?php get_template_part('template-parts/layout/page', 'header'); ?>
		<?php elseif (!is_page()) : ?>
			<?php get_template_part('template-parts/layout/page', 'header'); ?>
		<?php endif; ?>