// Ajax pagination for the Writing index page

jQuery(function($) {
    
    'use strict';
    
    $('#content').on('click', '#pagination > a', function(e) {
        e.preventDefault();
        var link = $(this).attr('href');
        $('#content').fadeOut(500, function() {
            $(this).load(link + ' #content', function() {
                $(this).fadeIn(500);
            });
        });
    });
});


