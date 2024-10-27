var media_uploader = null;

function open_media_uploader_image( callback ) {
    media_uploader = wp.media({
        title:    "Select or upload your advert's creative",
        library : {
            type: 'image'
        },
        button: {
            text: 'Use this media'
        },
        frame:    "post", 
        state:    "insert", 
        multiple: false
    });

    media_uploader.on("insert", function(){
        var json = media_uploader.state().get("selection").first().toJSON();
        if ( json.type === 'image' && callback ) {
            callback( json );
        }
    });

    media_uploader.open();
}
( function($) {

    var form = $(".advert-form");
    var preview = $(form).find(".artwork-box").find(".aside");
    var type = $(form).find("[name='advert-type']");

    function set_preview_link() {

        var link = $(form).find("[name='advert-url']").val();
        var el = $(preview).find(".graphic").find("a");
        if ( link == '' ) {
            $(el).attr("href", '#').attr("target", "").css("cursor", "default");
        } else {
            $(el).attr("href", link).attr("target", "_blank").css("cursor", "pointer");
        }

    }
    set_preview_link();

    function set_preview( media ) {

        // Set the image / link
        var link = document.createElement("A");
        var img = document.createElement("IMG");
        var details = document.createElement("UL");

        link.href = '#';
        link.target = "_blank";

        img.src = media.url;    
        img.alt = media.alt;

        link.appendChild(img);

        $(preview).find(".graphic").html('').append( link );

        // Set the details

        var detail_props = ['width', 'height', 'subtype', 'filesizeHumanReadable'];
        var detail_names = ['Width: %s px', 'Height: %s px', 'File type: %s', 'File size: %s'];

        for( var i = 0; i < detail_props.length; i ++ ) {
            var item = document.createElement("LI");
            item.innerHTML = detail_names[i].replace( "%s", media[ detail_props[i] ] );
            details.appendChild( item );
        }

        $(preview).find(".details").html('').append( details );

    }

    $(form).find(".media-upload").click( function() {
        open_media_uploader_image( function(media) {
           $(form).find("[name='advert-graphic']").val( media.id );
           set_preview(media);
        });
    });

    $(form).find("[name='advert-url']").keyup( function(e) {
        set_preview_link();
    });

})(jQuery);

