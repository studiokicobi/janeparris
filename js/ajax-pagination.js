// Ajax pagination for the Writing index page

jQuery(function($) {
    
    'use strict';
    
    $(window).resize(function(){
        if ($(window).width() >= 940) {  
            
            $('#content').on('click', '#pagination > a', function(e) {
                e.preventDefault();
                var link = $(this).attr('href');
                    $('#content').fadeOut(200, function() {
                        $(this).load(link + ' #content', function() {
                            $(this).fadeIn(200);
                        });
                    });
                });
            }
        });

    });


