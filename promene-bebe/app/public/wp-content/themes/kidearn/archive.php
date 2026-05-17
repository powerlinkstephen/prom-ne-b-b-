<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kidearn
 */

get_header();
?>

<!--Blog Sidebar Start-->
<section class="blog-page">
	<div class="container">
		<div class="row <?php echo esc_attr('full-width' == kidearn_blog_layout() ? 'justify-content-center' : ""); ?>">
			<?php $kidearn_content_class = (is_active_sidebar('sidebar-1')) ? "col-xl-8 col-lg-7" : "col-xl-12 col-lg-12" ?>
			<div class="<?php echo esc_attr($kidearn_content_class); ?>">
				<div class="blog-sidebar__left">
					<div class="blog-sidebar__content-box">
						<?php
						if (have_posts()) :

							/* Start the Loop */
							while (have_posts()) :
								the_post();

								/*
								* Include the Post-Type-specific template for the content.
								* If you want to override this in a child theme, then include a file
								* called content-___.php (where ___ is the Post Type name) and that will be used instead.
								*/
								get_template_part('template-parts/content', 'index');

							endwhile;

						?>
					</div>
					<?php if (paginate_links()) : ?>
						<div class="row">
							<div class="col-lg-12">
								<div class="blog-pagination">
									<?php kidearn_pagination(); ?>
								</div><!-- /.blog-pagination -->
							</div><!-- /.col-lg-12 -->
						</div><!-- /.row -->
					<?php endif; ?>

				<?php

						else :

							get_template_part('template-parts/content', 'none');

						endif;
				?>

				</div>
			</div>
			<?php if (is_active_sidebar('sidebar-1') &&  'full-width' != kidearn_blog_layout()) : ?>
				<div class="col-xl-4 col-lg-5 <?php echo esc_attr(kidearn_blog_layout()); ?>">
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
