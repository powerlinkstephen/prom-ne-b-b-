<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kidearn
 */

get_header();
?>


<!--Blog Sidebar Start-->
<section class="blog-one blog-one--page">
	<div class="container">
		<div class="row">
			<?php $kidearn_content_class = (is_active_sidebar('sidebar-1')) ? "col-xl-8 col-lg-7" : "col-xl-12 col-lg-12" ?>
			<div class="<?php echo esc_attr($kidearn_content_class); ?>">
				<div class="blog-details__left page__left">
					<div id="primary" class="site-main">

						<?php
						while (have_posts()) :
							the_post();

							get_template_part('template-parts/content', 'page');

							// If comments are open or we have at least one comment, load up the comment template.
							if (comments_open() || get_comments_number()) :
								comments_template();
							endif;

						endwhile; // End of the loop.
						?>

					</div><!-- #main -->
				</div>
			</div>
			<?php if (is_active_sidebar('sidebar-1')) : ?>
				<div class="col-xl-4 col-lg-5">
					<div class="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<!--Blog Sidebar End-->


<?php
get_footer();
