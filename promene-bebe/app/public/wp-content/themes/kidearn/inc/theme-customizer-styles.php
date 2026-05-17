<?php

/**
 * kidearn functions for getting inline styles from theme customizer
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kidearn
 */

if (!function_exists('kidearn_theme_customizer_styles')) :
	function kidearn_theme_customizer_styles()
	{
		// kidearn color option
		$kidearn_inline_style = '';
		$kidearn_inline_style .= ':root {
			' . kidearn_source_color_meta('get') . '
		}';

		$kidearn_inner_banner_shape_one = get_theme_mod('page_header_bg_image');
		$kidearn_inline_style .= '.page-header__bg { background-image: url(' . $kidearn_inner_banner_shape_one . '); } ';

		$kidearn_preloader_icon = get_theme_mod('preloader_image');
		if ($kidearn_preloader_icon) {
			$kidearn_inline_style .= '.preloader .preloader__image { background-image: url(' . $kidearn_preloader_icon . '); } ';
		}

		if (is_page()) {

			$kidearn_page_header_bg = empty(get_post_meta(get_the_ID(), 'kidearn_set_header_image', true)) ? get_theme_mod('page_header_bg_image') : get_post_meta(get_the_ID(), 'kidearn_set_header_image', true);

			$kidearn_inline_style .= '.page-header__bg { background-image: url(' . $kidearn_page_header_bg . '); }';
		}

		if (is_singular('post')) {
			$kidearn_post_header_bg = empty(get_post_meta(get_the_ID(), 'kidearn_set_header_image', true)) ? get_theme_mod('page_header_bg_image') : get_post_meta(get_the_ID(), 'kidearn_set_header_image', true);

			$kidearn_inline_style .= '.page-header__bg  { background-image: url(' . $kidearn_post_header_bg . '); }';
		}


		wp_add_inline_style('kidearn-style', $kidearn_inline_style);
	}
endif;

add_action('wp_enqueue_scripts', 'kidearn_theme_customizer_styles');
