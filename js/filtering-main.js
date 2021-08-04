// Isotope JS init

$(document).ready(function () {
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
});


// $('.grid').isotope({
//   // set itemSelector so .grid-sizer is not used in layout
//   itemSelector: '.grid-item',
//   percentPosition: true,
//   masonry: {
//     // use element for option
//     columnWidth: '.grid-sizer'
//   }
// })