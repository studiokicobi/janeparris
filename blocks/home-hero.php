<?php

/**
 * Block template file: blocks/home-hero.php
 *
 * Home Hero Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'home-hero-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-home-hero';
if (!empty($block['className'])) {
	$classes .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$classes .= ' align' . $block['align'];
}
?>

<style type="text/css">
	<?php echo '#' . $id; ?> {
		/* Add styles that use ACF values here */
	}
</style>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($classes); ?>">

	<?php if (get_field('background_image')) : ?>
		<img src="<?php the_field('background_image'); ?>" />
	<?php endif ?>

	<div class="hero">

		<!-- Heading -->
		<?php if (have_rows('heading_highlighted')) : ?>
			<?php while (have_rows('heading_highlighted')) : the_row(); ?>
				<h1 class="hero__heading"><?php the_sub_field('heading'); ?></h1>
				<?php // the_sub_field('first_highlighted_word'); 
				?>
				<?php // the_sub_field('second_highlighted_word'); 
				?>
			<?php endwhile; ?>
		<?php endif; ?>

		<!-- Buttons -->
		<?php if (have_rows('buttons')) : ?>
			<?php while (have_rows('buttons')) : the_row(); ?>
				<?php $button_link = get_sub_field('button_link'); ?>
				<a class="hero__button" href="<?php echo esc_url($button_link); ?>"><?php the_sub_field('button_text'); ?></a>
			<?php endwhile; ?>
		<?php else : ?>
			<?php // no rows found 
			?>
		<?php endif; ?>

		<!-- Testimonial slider -->

		<?php if (have_rows('testimonial_slider')) : ?>
			<div class="hero__testimonial swiper-container">
				<div class="swiper-wrapper">
					<?php while (have_rows('testimonial_slider')) : the_row(); ?>
						<div class="swiper-slide">
							<p><?php the_sub_field('testimonial'); ?></p>
							<p>
								<span class="hero__testimonial-author"><?php the_sub_field('author'); ?></span><?php if (get_sub_field('author_age')) {
																													echo ',';
																												} ?>
								<?php
								if (get_sub_field('author_age')) {
									echo '<span class="hero__testimonial-author--age">' . get_sub_field('author_age') . '</span>';
								}
								?>
								<?php
								if (get_sub_field('role')) {
									echo '<br /><span class="hero__testimonial-author--role">' . get_sub_field('role') . '</span>';
								}
								?>
							</p>
						</div>
					<?php endwhile; ?>
				</div>
				<!-- Pagination -->
				<div class="swiper-pagination"></div>
			</div>
		<?php endif; ?>

		<!-- Services -->
		<?php if (have_rows('services')) : ?>
			<div class="hero__services">
				<?php while (have_rows('services')) : the_row(); ?>
					<div class="hero__service">
						<h2 class="hero__service-title"><?php the_sub_field('service_title'); ?></h2>
						<p class="hero__service-strapline"><?php the_sub_field('service_strapline'); ?></p>
						<div class="hero__service-description">
							<p><?php the_sub_field('service_description'); ?></p>
						</div>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found 
				?>
			</div>
		<?php endif; ?>

	</div>
</div>