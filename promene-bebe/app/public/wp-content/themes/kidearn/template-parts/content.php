<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kidearn
 */
$category = get_the_category();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-card blog-card-two @@extraClassName">
		<?php if (has_post_thumbnail()) : ?>
			<div class="blog-card__image">
				<?php the_post_thumbnail('kidearn_blog_770X449');  ?>
			</div><!-- /.blog-card__image -->
		<?php endif; ?>
		<div class="blog-card__content">
			<div class="blog-card__content__top">
				<?php if (!empty($category[0]->name)) : ?>
					<a href="<?php the_permalink(); ?>" class="blog-card__category">
						<?php echo esc_html($category[0]->name); ?>
					</a>
				<?php endif; ?>
				<div class="blog-card__date">
					<i class="icon-clock"></i>
					<?php echo get_the_date(); ?>
				</div><!-- /.blog-card__date -->
			</div><!-- /.blog-card__content__top -->
			<?php
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'kidearn'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post(get_the_title())
				)
			);

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__('Pages:', 'kidearn'),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- /.blog-card__content -->
	</div><!-- /.blog-card -->
	<div class="blog-details__meta">
		<?php kidearn_entry_footer(); ?>
	</div><!-- /.blog-details__meta -->
</article><!-- #post-<?php the_ID(); ?> -->