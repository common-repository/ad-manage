( function($) {

    var inputs = $(".check-column").find("input[type='checkbox']"),
        id_field = $(".bulk-actions-form").find("input[name='ids']");

	$(inputs).change( function(e) {
        var ids = [];
        $(inputs).each( function() {
            if ( $(this).prop("checked") && !$(this).parent().hasClass("manage-column") )
                ids.push( $(this).val() );
        });
        $(id_field).val( ids.join() );
    });

})(jQuery);