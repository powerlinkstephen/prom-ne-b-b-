<?php

/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package kidearn
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
 *
 * @return void
 */
function kidearn_woocommerce_setup()
{
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 270,
			'single_image_width'    => 570,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 3,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);

	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'kidearn_woocommerce_setup');


/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function kidearn_woocommerce_scripts()
{
	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style('kidearn-style', $inline_font);
}
add_action('wp_enqueue_scripts', 'kidearn_woocommerce_scripts');

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function kidearn_woocommerce_active_body_class($classes)
{
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter('body_class', 'kidearn_woocommerce_active_body_class');




/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */

add_filter('woocommerce_enqueue_styles', '__return_empty_array');


//shop page
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);



add_action('woocommerce_before_shop_loop_item_title', 'kidearn_thumbnail_markup_open', 10);

add_action('woocommerce_before_shop_loop_item_title', 'kidearn_template_loop_product_thumbnail', 10);

add_action('woocommerce_before_shop_loop_item_title', 'kidearn_thumbnail_markup_end', 10);

add_action('woocommerce_shop_loop_item_title', 'kidearn_product_title_markup_start', 10);

add_action('woocommerce_shop_loop_item_title', 'kidearn_product_title', 10);

add_action('woocommerce_shop_loop_item_title', 'kidearn_template_loop_price', 10);

add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 10);

add_action('woocommerce_shop_loop_item_title', 'kidearn_woocommerce_template_view_cart', 10);


add_action('woocommerce_after_shop_loop_item', 'kidearn_product_title_markup_end', 10);

function kidearn_thumbnail_markup_open()
{ ?>
	<div class="product__all-img">

	<?php }


function kidearn_template_loop_product_thumbnail()
{
	global $product;
	if (function_exists('woocommerce_template_loop_product_thumbnail')) :
		woocommerce_template_loop_product_thumbnail();
	endif; ?>

	<?php
}

function kidearn_thumbnail_markup_end()
{ ?>

	</div>
<?php }

function kidearn_product_title_markup_start()
{ ?>
	<div class="product__all-content">
	<?php }

function kidearn_product_title()
{ ?>
		<h4 class="product__item__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
	<?php }


function kidearn_template_loop_price()
{
	global $product;
	?>
		<div class="product__all-price"><?php echo woocommerce_template_loop_price(); ?></div><!-- /.product__all-price -->
	<?php }

function kidearn_woocommerce_template_view_cart()
{ ?> <div class="product__add-to-cart">
			<?php
			global $product;
			$kidearn_ajax_cart_class = (get_option('woocommerce_enable_ajax_add_to_cart') == 'yes' ? 'kidearn_ajax' : '');
			if ($product->is_type('variable')) {

				echo sprintf(
					'<a href="%s" class="%s">%s</a>',
					esc_url($product->add_to_cart_url()),
					esc_attr(implode(' ', array_filter(array(
						'button', 'product_type_' . $product->get_type(),
						'thm-btn product__all-btn add_to_cart_button'
					)))),
					esc_html($product->add_to_cart_text())
				);
			} else {
				echo sprintf(
					'<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
					esc_url($product->add_to_cart_url()),
					esc_attr(implode(' ', array_filter(array(
						'button', 'product_type_' . $product->get_type(),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports('ajax_add_to_cart') ? 'thm-btn product__all-btn add_to_cart_button ajax_add_to_cart' : 'thm-btn shop-one__cart add_to_cart_button ',
						$kidearn_ajax_cart_class
					)))),
					wc_implode_html_attributes(array(
						'data-product_id'  => $product->get_id(),
						'data-product_sku' => $product->get_sku(),
						'aria-label'       => $product->add_to_cart_description(),
						'rel'              => 'nofollow',
					)),
					esc_html($product->add_to_cart_text())
				);
			}
			?>
		</div>
	<?php
}

function kidearn_product_title_markup_end()
{ ?>
	</div>
	<div class="product__item__btn">
		<?php
		if (class_exists('WPCleverWoosw')) {
			echo do_shortcode('[woosw]');
		}
		?>

		<?php
		if (class_exists('WPCleverWoosq')) {
			echo do_shortcode('[woosq]');
		}
		?>
	</div>
<?php }


//single page

remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);


remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

add_action('woocommerce_single_product_summary', 'kidearn_product_details_title_markup_start');
function kidearn_product_details_title_markup_start()
{ ?>
	<div class="product-details__top">
	<?php
}

add_action('woocommerce_single_product_summary', 'kidearn_template_single_title');
function kidearn_template_single_title()
{
	global $product;
	?>
		<h3 class="product-details__title"><?php the_title(); ?><?php echo woocommerce_template_loop_price(); ?></h3>
	<?php
}


add_action('woocommerce_single_product_summary', 'kidearn_product_details_title_markup_end');
function kidearn_product_details_title_markup_end()
{ ?>
	</div>
	<?php
}

add_action('woocommerce_single_product_summary', 'kidearn_template_single_rating');
function kidearn_template_single_rating()
{
	global $product;
	$rating_count = $product->get_rating_count();
	if ($rating_count > 0) : ?>

		<div class="product-details__content__rating">
			<?php wc_get_template('single-product/rating.php'); ?>
		</div>

	<?php
	endif;
}

add_action('woocommerce_single_product_summary', 'kidearn_template_single_excerpt');
function kidearn_template_single_excerpt()
{
	?>
	<div class="product-details__content__text">
		<?php wc_get_template('single-product/short-description.php'); ?>
	</div>

<?php
}


add_action('woocommerce_before_add_to_cart_quantity', 'kidearn_add_to_cart_input_markup_start');

function kidearn_add_to_cart_input_markup_start()
{ ?>
	<div class="product-details__quantity">
		<h3 class="product-details__quantity-title"><?php esc_html_e('Quantity', 'kidearn'); ?></h3>
		<!-- /.product-details__quantity -->
	<?php }

add_action('woocommerce_after_add_to_cart_quantity', 'kidearn_add_to_cart_input_markup_end');

function kidearn_add_to_cart_input_markup_end()
{ ?>
	</div>

<?php }

add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart');

add_action('woocommerce_after_add_to_cart_button', 'kidearn_wishlist');
function kidearn_wishlist()
{
	if (class_exists('WPCleverWoosw')) {
		echo do_shortcode('[woosw]');
	}
}

add_action('woocommerce_after_add_to_cart_quantity', 'kidearn_after_add_to_cart_quantity', 99);
function kidearn_after_add_to_cart_quantity()
{ ?>
	<div class="product-details__buttons">
	<?php }

add_action('woocommerce_after_add_to_cart_button', 'kidearn_after_add_to_cart_button', 99);
function kidearn_after_add_to_cart_button()
{
	?>
	</div>
<?php }
//social share
add_action('woocommerce_single_product_summary', 'kidearn_product_details_social_share');
if (!function_exists('kidearn_product_details_social_share')) :
	function kidearn_product_details_social_share()
	{
		return false;
	}
endif;


add_action('woocommerce_after_single_product', 'kidearn_product_content');

function kidearn_product_content()
{ ?>
	<section class="product-content product-description">
		<h3 class="product-details__description__title"><?php esc_html_e('Description', 'kidearn'); ?></h3><!-- /.product-description__title -->
		<?php the_content(); ?>
	</section><!-- /.product-content -->
<?php }

function kidearn_register_fields()
{ ?>
	<p class="form-row form-row-first">
		<label for="reg_billing_first_name"><?php _e('First name', 'kidearn'); ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if (!empty($_POST['billing_first_name'])) esc_attr($_POST['billing_first_name']); ?>" />
	</p>
	<p class="form-row form-row-last">
		<label for="reg_billing_last_name"><?php _e('Last name', 'kidearn'); ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if (!empty($_POST['billing_last_name'])) esc_attr($_POST['billing_last_name']); ?>" />
	</p>
	<div class="clear"></div>
<?php
}
add_action('woocommerce_register_form_start', 'kidearn_register_fields');

add_filter('woocommerce_checkout_fields', 'kidearn_billing_checkout_fields', 20, 1);
function kidearn_billing_checkout_fields($fields)
{
	$fields['billing']['billing_first_name']['placeholder'] = esc_html__('First name', 'kidearn');
	$fields['billing']['billing_last_name']['placeholder'] = esc_html__('Last name', 'kidearn');
	$fields['billing']['billing_company']['placeholder'] = esc_html__('Company name (optional)', 'kidearn');
	$fields['billing']['billing_city']['placeholder'] = esc_html__('Town / City', 'kidearn');
	$fields['billing']['billing_postcode']['placeholder'] = esc_html__('ZIP code', 'kidearn');
	$fields['billing']['billing_phone']['placeholder'] = esc_html__('Phone', 'kidearn');
	$fields['billing']['billing_email']['placeholder'] = esc_html__('Email', 'kidearn');
	return $fields;
}

add_filter('woocommerce_checkout_fields', 'kidearn_shipping_checkout_fields', 20, 1);
function kidearn_shipping_checkout_fields($fields)
{
	$fields['shipping']['shipping_first_name']['placeholder'] = esc_html__('First name', 'kidearn');
	$fields['shipping']['shipping_last_name']['placeholder'] = esc_html__('Last name', 'kidearn');
	$fields['shipping']['shipping_company']['placeholder'] = esc_html__('Company name (optional)', 'kidearn');
	return $fields;
}


// WooCommerce Checkout Fields Hook
add_filter('woocommerce_checkout_fields', 'kidearn_checkout_fields_no_label');

// Our hooked in function - $fields is passed via the filter!
// Action: remove label from $fields
function kidearn_checkout_fields_no_label($fields)
{
	// loop by category
	foreach ($fields as $category => $value) {
		// loop by fields
		foreach ($fields[$category] as $field => $property) {
			// remove label property
			unset($fields[$category][$field]['label']);
		}
	}
	return $fields;
}


add_filter('woocommerce_order_button_text', 'change_place_order_button_text', 10, 1);
function change_place_order_button_text($button_text)
{
	$button_text = esc_html__('Place Your Order', 'kidearn');
	return $button_text;
}
