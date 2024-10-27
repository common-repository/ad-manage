( function($) {

    $.fn.admg_slug = function( options ) {

        options = $.extend({
            source : false
        }, options );

        var self = this;

        function sanitize_slug(str) {
            return encodeURIComponent( str.replace(/ /g, "-").toLowerCase() );
        }

        $(options.source).on("keydown paste", function(e){
            $(self).val( sanitize_slug( $(this).val() ));
        }); 
    }

    $("[name='advert-slug']").admg_slug({ 
        source : $("[name='advert-name']")
    });

    $("[name='location-slug']").admg_slug({ 
        source : $("[name='location-name']")
    });

})(jQuery);