jQuery(document).ready( function($){
    if($(".contact-form-editor-box-mail legend").length){
        if(typeof listing_categories != "undefined") {
            $.each(listing_categories, function (i, value) {
                $(".contact-form-editor-box-mail legend").append('<span class="mailtag code">[_' + value + ']</span>');
            });
        }
    }
});