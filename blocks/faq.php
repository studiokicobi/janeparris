<?php

/**
 * Block template file: /www/kinsta/public/janeparriscom/wp-content/themes/janeparris/blocks/faq.php
 *
 * Faq Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'faq-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-faq';
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
    <?php if (have_rows('faq_sections')) : ?>
        <?php while (have_rows('faq_sections')) : the_row(); ?>
            <div class="stacked-card">
                <h2 class="stacked-card__heading"><?php the_sub_field('faq_heading'); ?></h2>
                <div class="stacked-card__content">
                    <?php the_sub_field('faq_text'); ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <?php // no rows found 
        ?>
    <?php endif; ?>
</div>