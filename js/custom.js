/**
 * jQuery set up
 */

// Smooth scrolling
// https://css-tricks.com/snippets/jquery/smooth-scrolling/

( function ( $ ) {
	'use strict';
    // if (window.console) console.log('foo');

    // Select all links with hashes
$('a[href*="#"]')
  // Remove links that don't actually link to anything
  .not('[href="#"]')
  .not('[href="#0"]')
  .click(function(event) {
    // On-page links
    if (
      location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname
    ) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      // Does a scroll target exist?
      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000, function() {
          // Callback after animation
          // Must change focus!
          var $target = $(target);
          $target.focus();
          if ($target.is(":focus")) { // Checking if the target was focused
            return false;
          } else {
            $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
            $target.focus(); // Set focus again
          }
        });
      }
    }
  });

  // Isotope init
    $(".sortable-testimonial").isotope({
    itemSelector: ".sortable-testimonial__item-container"
  });

  // filter items on button click
  $(".filter-button-group").on("click", "button", function () {
    var filterValue = $(this).attr("data-filter");
    $(".sortable-testimonial").isotope({ filter: filterValue });
    $(".filter-button-group button").removeClass("active");
    $(this).addClass("active");
  });

} )( jQuery );