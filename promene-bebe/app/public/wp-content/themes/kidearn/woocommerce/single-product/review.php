<?php

/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
global $comment;
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<!--Start Comment Box-->
	<div class="product-details__comment-box">
		<figure class="product-details__comment-box__thumb">
			<?php echo get_avatar($comment, apply_filters('woocommerce_review_gravatar_size', '165'), ''); ?>
		</figure><!-- comment-image -->
		<h4 class="product-details__comment-box__meta"><?php comment_author(); ?><span class="product-details__comment-box__date"><?php echo esc_html(get_comment_date(wc_date_format())); ?></span></h4><!-- comment-meta -->
		<div class="product-details__comment-box__ratings">
			<?php wc_get_template('single-product/review-rating.php'); ?>
		</div><!-- comment-ratings -->
		<?php comment_text() ?>
	</div>
	<!--End Comment Box-->