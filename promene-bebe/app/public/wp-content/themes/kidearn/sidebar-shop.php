<?php

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kidearn
 */

if (!is_active_sidebar('shop')) {
	return;
}
?>

<aside id="secondary" class="widget-area woo-widget">
	<?php dynamic_sidebar('shop'); ?>
</aside><!-- #secondary -->