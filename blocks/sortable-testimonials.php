<?php

/**
 * Block template file: /www/kinsta/public/janeparriscom/wp-content/themes/janeparris/blocks/sortable-testimonials.php
 *
 * Sortable Testimonial Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'sortable-testimonial-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-sortable-testimonial';
if (!empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $classes .= ' align' . $block['align'];
}
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($classes); ?>">

    <?php
    // data-filter key
    // students: What students say
    // parents: What parents say
    // writing: Writing classes
    // college: College essays
    ?>

    <!-- Sortable testimonial controls -->
    <div class="filter-button-group">
        <button data-filter=".students">What students say</button>
        <button data-filter=".parents">What parents say</button>
        <button data-filter=".writing">Writing classes</button>
        <button data-filter=".college">College essays</button>
        <button data-filter="*">All</button>
    </div>

    <?php if (have_rows('testimonials')) : ?>
        <div class="sortable-testimonial">
            <?php while (have_rows('testimonials')) : the_row(); ?>

                <div class="sortable-testimonial__item-container <?php the_sub_field('testimonial_category'); ?>">
                    <div class="sortable-testimonial__content <?php the_sub_field('testimonial_category'); ?>-content">
                        <p>
                            <?php the_sub_field('testimonial_text'); ?>
                        </p>
                        <?php $case_study_link = get_sub_field('case_study_link'); ?>
                        <?php if ($case_study_link) : ?>
                            <a class="sortable-testimonial__case-study-link" href="<?php echo esc_url($case_study_link); ?>"><?php the_sub_field('case_study_button_text_'); ?></a>
                        <?php endif; ?>
                    </div>

                    <?php
                    // Get the first letter of the author in order to create a custom class
                    $string = get_sub_field('testimonial_author');
                    $firstChar = mb_substr($string, 0, 1, "UTF-8");
                    $url = get_stylesheet_directory_uri() . '/images/alphabet/' . $firstChar . '.svg';
                    ?>

                    <div class="sortable-testimonial__meta">
                        <div aria-hidden="true" class="sortable-testimonial__dropcap <?php the_sub_field('testimonial_category'); ?>-dropcap" style="background-image: url();"><?php echo $firstChar; ?></div>
                        <div class="sortable-testimonial__meta--content">
                            <p>
                                <?php echo '<span class="sortable-testimonial__meta--name">' . get_sub_field('testimonial_author');
                                if (get_sub_field('student_age')) {
                                    echo ', ' . get_sub_field('student_age');
                                }
                                echo '</span>';
                                if (get_sub_field('role')) {
                                    echo '<span class="sortable-testimonial__meta--role"><br />' . get_sub_field('role') . '</span>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

        </div>
    <?php endif; ?>
</div>