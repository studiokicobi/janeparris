<?php

/**
 * Block template file: block-home-page-extended-hero
 *
 * Home Page Extended Hero Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'home-page-extended-hero-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-home-page-extended-hero';
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
	<?php the_field('heading'); ?>
	<?php if (have_rows('buttons')) : ?>
		<?php while (have_rows('buttons')) : the_row(); ?>
			<?php the_sub_field('button_text'); ?>
			<?php $button_link = get_sub_field('button_link'); ?>
			<?php if ($button_link) : ?>
				<a href="<?php echo esc_url($button_link); ?>"><?php echo esc_html($button_link); ?></a>
			<?php endif; ?>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found 
		?>
	<?php endif; ?>
	<?php $background_image = get_field('background_image'); ?>
	<?php if ($background_image) : ?>
		<img src="<?php echo esc_url($background_image['url']); ?>" alt="<?php echo esc_attr($background_image['alt']); ?>" />
	<?php endif; ?>
	<?php if (have_rows('testimonial_slider')) : ?>
		<?php while (have_rows('testimonial_slider')) : the_row(); ?>
			<?php the_sub_field('testimonial'); ?>
			<?php the_sub_field('author'); ?>
			<?php the_sub_field('author_age'); ?>
			<?php the_sub_field('role'); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found 
		?>
	<?php endif; ?>
	<?php if (have_rows('services')) : ?>
		<?php while (have_rows('services')) : the_row(); ?>
			<?php the_sub_field('service_title'); ?>
			<?php the_sub_field('service_strapline'); ?>
			<?php the_sub_field('service_description'); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found 
		?>
	<?php endif; ?>
</div>