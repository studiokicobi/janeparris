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

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($classes); ?>">

	<div class="hero container">

		<div class="hero-top">
			<div class="hero-top-text">
				<div class="hero-top-text__content">
					<!-- Heading -->
					<div class="hero-top-text__heading"><?php the_field('heading_hero'); ?></div>
					<!-- Buttons -->
					<?php if (have_rows('buttons')) : ?>
						<div class="hero-top-text__button-wrapper">
							<?php while (have_rows('buttons')) : the_row(); ?>
								<?php $button_link = get_sub_field('button_link'); ?>
								<a class="hero-top-text__button" href="<?php echo esc_url($button_link); ?>"><?php the_sub_field('button_text'); ?></a>
							<?php endwhile; ?>
						</div>
						<!--1 -->
					<?php endif; ?>
				</div><!-- 2-->
			</div><!-- 3-->

			<div class="hero-top-image">
				<img <?php awesome_acf_responsive_image(get_field('hero_image_id'), 'medium', '640px'); ?> alt="" />
			</div> <!-- .hero-top-image -->
		</div> <!-- .hero-top -->

		<div class="hero__services-wrapper">

			<!-- Testimonial slider -->
			<?php if (have_rows('testimonial_slider')) : ?>
				<div class="hero__testimonial-container" data-flickity='{ "fade": true, "autoPlay": 5000, "prevNextButtons": false, "pageDots": true, "adaptiveHeight": true, "wrapAround": true }'>
					<?php while (have_rows('testimonial_slider')) : the_row(); ?>
						<div class="hero__testimonial">
							<p><?php the_sub_field('testimonial'); ?></p>
							<p>
								<span class="hero__testimonial-author"><?php the_sub_field('author'); ?></span>
								<?php if (get_sub_field('author_age')) {
									echo '<span class="hero__testimonial-author--age">, ' . get_sub_field('author_age') . '</span><br />';
								} ?>
								<?php if (get_sub_field('role')) {
									echo '<span class="hero__testimonial-author--role">' . get_sub_field('role') . '</span>';
								} ?>
							</p>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>


			<!-- Services -->
			<?php if (have_rows('services')) : ?>
				<div class="hero__services container">
					<?php while (have_rows('services')) : the_row(); ?>
						<div class="hero__service">
							<div class="hero__service-header">
								<h2 class="hero__service-title"><?php the_sub_field('service_title'); ?></h2>
								<p class="hero__service-strapline"><?php the_sub_field('service_strapline'); ?></p>
							</div>

							<div class="hero__service-description">
								<p class="has-drop-cap"><?php the_sub_field('service_description'); ?></p>
								<?php $link = get_sub_field('link'); ?>
								<?php if ($link) : ?>
									<a class="hero__service-link" href="<?php echo esc_url($link); ?>">
										<?php the_sub_field('label'); ?>
									</a>
								<?php endif; ?>

							</div>
						</div>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found 
					?>
				</div>
			<?php endif; ?>

		</div> <!-- .hero__services-wrapper-->
	</div>
</div>