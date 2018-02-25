
(function($) {
    "use strict";
    
    var dropdown = $('.dropdown');

    // Add slidedown animation to dropdown
    dropdown.on('show.bs.dropdown', function(e){
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    });

    // Add slideup animation to dropdown
    dropdown.on('hide.bs.dropdown', function(e){
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });
})(jQuery);

$(window).load(function() {
    "use strict";
    // Grid slider,used on index_2.html
    $('#grid-slider').flexslider({
    
        slideshowSpeed: 100000,
        animation: "fade",  
        smoothHeight: true,
        easing: "linear",
        controlNav: false,
        nextText: '<i class="ti-angle-right"></i>',
        prevText: '<i class="ti-angle-left"></i>'
    });
    // The slider being synced must be initialized first
    $('#carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 150,
        itemMargin: 5,
        asNavFor: '#slider'
    });

    $('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carousel"
    });

    
    // Isotope init
    $('.row-isotope').isotope({
        itemSelector: '.item',
        layoutMode: 'masonry',
        masonry: {
            columnWidth: '.grid-sizer'
        }
    });
    
     $('.truncate').succinct({
            size: 80
             
        });


});


// Seetings for loader
$(document).ready(function() {
    "use strict";
     
    $(".animsition").animsition({

        inClass: 'fade-in',
        outClass: 'fade-out',
        inDuration: 800,
        outDuration: 800,
        linkElement: '.animsition-link',
        // e.g. linkElement   :   'a:not([target="_blank"]):not([href^=#])'
        loading: true,
        loadingParentElement: 'body', //animsition wrapper element
        loadingClass: 'animsition-loading',
        unSupportCss: ['animation-duration',
            '-webkit-animation-duration',
            '-o-animation-duration'
        ],
        //"unSupportCss" option allows you to disable the "animsition" in case the css property in the array is not supported by your browser.
        //The default setting is to disable the "animsition" in a browser that does not support "animation-duration".

        overlay: false,

        overlayClass: 'animsition-overlay-slide',
        overlayParentElement: 'body'
    });
// Responsive video 
    $(".responsive-video").fitVids();
// Responsive tabs
    $('.nav-tabs:first', '.nav-pills:first').tabdrop({
        text: '<i class="ti-menu"></i>'
    });
// Light box image
    $('.popup-img').magnificPopup({
        type: 'image'
            // other options
    });

    // This will create a single gallery from all elements that have class "gallery-item"
    $('.gallery-img').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });
// Add image via data 
    $(".bg-image").css('background', function() {
        var bg = ('url(' + $(this).data("image-src") + ') no-repeat center center');
        return bg;
    });
    // fit image nicely
    $(".bg-image").css("background-size", "cover");




 // Collapse,icon change
    $('.collapse').on('shown.bs.collapse', function() {
        $(this).parent().find(".ti-plus").removeClass("ti-plus").addClass("ti-minus");
    }).on('hidden.bs.collapse', function() {
        $(this).parent().find(".ti-minus").removeClass("ti-minus").addClass("ti-plus");
    });


});

// Wizards forms
(function($) {
    "use strict";
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('a[data-toggle="tab"]').removeClass('btn-primary');
            $('a[data-toggle="tab"]').addClass('btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
        })

        $('.next').click(function(){
            var nextId = $(this).parents('.tab-pane').next().attr("id");
            $('[href=#'+nextId+']').tab('show');
        })

        $('.prev').click(function(){
            var prevId = $(this).parents('.tab-pane').prev().attr("id");
            $('[href=#'+prevId+']').tab('show');
        })

})(jQuery);
// Ripple-effect animation for buttons
(function($) {
    "use strict";
    $(".ripple-effect").click(function(e) {
        var rippler = $(this);

        // create .ink element if it doesn't exist
        if (rippler.find(".ink").length == 0) {
            rippler.append("<span class='ink'></span>");
        }

        var ink = rippler.find(".ink");

        // prevent quick double clicks
        ink.removeClass("animate");

        // set .ink diametr
        if (!ink.height() && !ink.width()) {
            var d = Math.max(rippler.outerWidth(), rippler.outerHeight());
            ink.css({
                height: d,
                width: d
            });
        }

        // get click coordinates
        var x = e.pageX - rippler.offset().left - ink.width() / 2;
        var y = e.pageY - rippler.offset().top - ink.height() / 2;

        // set .ink position and add class .animate
        ink.css({
            top: y + 'px',
            left: x + 'px'
        }).addClass("animate");
    })
})(jQuery);






