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

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($classes); ?>">
    <h2 class="stacked-card-title">FAQ</h2>

    <?php if (have_rows('faq_sections')) : ?>
        <?php while (have_rows('faq_sections')) : the_row(); ?>

            <?php
            // Get the first letter of the title in order to create a custom class
            $string = strip_tags(get_sub_field('faq_text')); // Strip out HTML tags
            $cleanString = str_replace("“", "", "$string"); // Remove quotation marks
            $firstChar = mb_substr($cleanString, 0, 1, "UTF-8"); // Get the first character using multi-byte safe version of substr
            ?>

            <div class="stacked-card">
                <div class="stacked-card__heading-wrapper">
                    <h3 class="stacked-card__heading"><?php the_sub_field('faq_heading'); ?></h3>
                </div>
                <?php

                // Use the first letter from above to add a custom background
                ?>
                <div class="stacked-card__content-wrapper clearfix bg-letter" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/alphabet/<?php echo $firstChar; ?>.svg);">
                    <div class="stacked-card__content">
                        <?php the_sub_field('faq_text');
                        ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <?php // no rows found 
        ?>
    <?php endif; ?>
</div>