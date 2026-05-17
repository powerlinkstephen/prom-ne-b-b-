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
	<div class="col-md-12">
		<div class="blog-card blog-card-two wow fadeInUp" data-wow-duration='1500ms' data-wow-delay='000ms'>
			<?php if (has_post_thumbnail()) : ?>
				<div class="blog-card__image">
					<?php $thumb = get_the_post_thumbnail_url(); ?>
					<?php the_post_thumbnail('kidearn_blog_770X449');  ?>
					<div class="blog-card__image__layer" style="background-image: url(<?php echo esc_url($thumb); ?>);"></div>
					<div class="blog-card__image__layer" style="background-image: url(<?php echo esc_url($thumb); ?>);"></div>
					<div class="blog-card__image__layer" style="background-image: url(<?php echo esc_url($thumb); ?>);"></div>
					<div class="blog-card__image__layer" style="background-image: url(<?php echo esc_url($thumb); ?>);"></div>
					<a href="<?php the_permalink(); ?>" class="blog-card__image__link"><span class="sr-only"><?php the_title(); ?></span>
						<!-- /.sr-only --></a>
				</div><!-- /.blog-card__image -->
			<?php endif; ?>
			<div class="blog-card__content">
				<div class="blog-card__content__top">
					<?php if (has_category()) : ?>
						<a href="<?php the_permalink(); ?>" class="blog-card__category"><?php echo esc_html($category[0]->name); ?></a>
					<?php endif; ?>
					<div class="blog-card__date">
						<i class="icon-clock"></i>
						<?php echo get_the_date(); ?>
					</div><!-- /.blog-card__date -->
				</div><!-- /.blog-card__content__top -->
				<h3 class="blog-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><!-- /.blog-card__title -->
				<?php $kidearn_excerpt_count = apply_filters('kidearn_excerpt_count', 41); ?>
				<p class="blog-card-two__text"><?php kidearn_excerpt($kidearn_excerpt_count); ?></p><!-- /.blog-card-two__text -->
				<div class="blog-card__content__bottom">
					<a href="<?php the_permalink(); ?>" class="blog-card__link">
						<span class="sr-only"><?php esc_html_e('Read More', 'kidearn'); ?></span><!-- /.sr-only -->
						<i class="icon-right-arrow"></i>
					</a><!-- /.blog-card__link -->
				</div><!-- /.blog-card__content__bottom -->
			</div><!-- /.blog-card__content -->
		</div><!-- /.blog-card -->
	</div><!-- /.col-md-12 -->
</article><!-- #post-<?php the_ID(); ?> -->