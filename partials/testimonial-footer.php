<?php
$rows = get_field('footer_testimonials', 'option');
if ($rows) {
    $index = array_rand($rows);
    $rand_row = $rows[$index];
    $rand_row_content = $rand_row['testimonial_content'];
    $rand_row_author = $rand_row['testimonial_author'];
    $rand_row_age = $rand_row['testimonial_student_age'];
    $rand_row_info = $rand_row['testimonial_author_info'];

    if ($rand_row_content != '') {
        echo '<div class="testimonial-footer container">';
        echo '<p class="testimonial-footer__content">';
        echo $rand_row_content;
        echo '</p>';
    }

    // Check if the author info exists
    if ($rand_row_author != '') {
        echo '<div class="testimonial-footer__meta">';
        echo '<span class="testimonial-footer__author">';
        echo $rand_row_author;
    }

    // Check for the age field
    // If it exists then it needs to follow the author text from above
    if ($rand_row_age != '') {
        echo ', ' . $rand_row_age;
    }

    // Check for info
    if ($rand_row_info != '') {
        echo '<span class="testimonial-footer__info">' . $rand_row_info . '</span>';
    }

    // Close the author and meta elements 
    if ($rand_row_author != '') {
        echo '</span>'; // close .testimonial-footer__author
        echo '</div>'; // close .testimonial-footer__meta
    }

    // Close the testimonial container
    if ($rand_row_content != '') {
        echo '</div>';
    }
}
