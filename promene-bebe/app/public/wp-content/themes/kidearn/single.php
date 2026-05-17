<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package kidearn
 */

get_header();
?>

<!--Blog Sidebar Start-->
<section class="blog-one blog-one--page">
	<div class="container">
		<?php $kidearn_content_class = (is_active_sidebar('sidebar-1')) ? "col-xl-8 col-lg-7" : "col-xl-12 col-lg-12" ?>
		<div class="row gutter-y-60 <?php echo esc_attr('full-width' == kidearn_blog_layout() ? 'justify-content-center' : ""); ?>">
			<div class="<?php echo esc_attr($kidearn_content_class); ?>">
				<div class="blog-details">
					<?php
					while (have_posts()) :
						the_post();

						get_template_part('template-parts/content', get_post_type());


						// If comments are open or we have at least one comment, load up the comment template.
						if (comments_open() || get_comments_number()) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
				</div><!-- /.col-lg-8 -->
			</div>
			<?php if (is_active_sidebar('sidebar-1') &&  'full-width' != kidearn_blog_layout()) : ?>
				<div class="col-lg-4 <?php echo esc_attr(kidearn_blog_layout()); ?>">
					<div class="sidebar">
						<?php get_sidebar(); ?>
					</div><!-- /.sidebar -->
				</div><!-- /.col-lg-4 -->
			<?php endif; ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</section><!-- /.blog-one blog-one--page -->

<?php
get_footer();
