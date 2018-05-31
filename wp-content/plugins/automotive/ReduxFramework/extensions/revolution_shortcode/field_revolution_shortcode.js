(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.revolution_shortcode  = redux.field_objects.revolution_shortcode  || {};

    $( document ).ready(
        function() {
            redux.field_objects.revolution_shortcode.init();
            redux.field_objects.revolution_shortcode.update_card_content();
            redux.field_objects.revolution_shortcode.generate_preview();

            $("#rev_border_style").on("change", function(){
                redux.field_objects.revolution_shortcode.update_card_content();
                redux.field_objects.revolution_shortcode.generate_preview();
            });

            $("#rev_card_type").on("change", function(){
                redux.field_objects.revolution_shortcode.update_card_content();
                redux.field_objects.revolution_shortcode.update_card_type();
                redux.field_objects.revolution_shortcode.generate_preview();
            });

            $("#rev_text").on("change keyup paste", function(){
                redux.field_objects.revolution_shortcode.update_card_content();
                redux.field_objects.revolution_shortcode.generate_preview();
            });

            $("#insert_category").on("click", function(e){
                e.preventDefault();

                var current_val = $("#rev_text").val();

                $("#rev_text").val(current_val + " %%" + $("#rev_categories").val() + "%%");

                redux.field_objects.revolution_shortcode.update_card_content();
                redux.field_objects.revolution_shortcode.generate_preview();
            });

            $("#rev_listing_data").on("change", function(){
                redux.field_objects.revolution_shortcode.update_card_content();
                redux.field_objects.revolution_shortcode.generate_preview();
            });

            function get_rev_options(){
                var options       = {};

                $("#rev_preview_options :input").each( function(){
                    var id    = $(this).attr("id");
                    var value = $(this).val();

                    if(typeof id != "undefined") {
                        options[id] = value;
                    }
                });

                options['text'] = $("#rev_text").val();
                options['type'] = $("#rev_card_type").val();

                return options;
            }

            function rev_ajax_message(message){
                $("#rev_ajax_message").text(message);

                $("#rev_update_existing_form").slideUp();
                $("#rev_save_as_form").slideUp();

                $("#rev_ajax_message").slideDown(function(){
                    setTimeout(function(){
                        $("#rev_ajax_message").slideUp();
                    }, 5000);
                });
            }

            $("#rev_save_template").on("click", function(e){
                e.preventDefault();

                var template_name = $("#rev_template_name").val();
                var options       = get_rev_options();

                $.ajax({
                    type: "post",
                    url: ajaxurl,
                    data: {
                        action: "save_rev_template",
                        template_name: template_name,
                        options: options
                    },
                    success: function (response) {
                        //redux.args.disable_save_warn = true;
                        rev_ajax_message(response);
                    }
                });
            });

            $("#rev_update_template").on("click", function(e){
                e.preventDefault();

                var template_id   = $("#overwrite_rev_template").val();
                var options       = get_rev_options();

                $.ajax({
                    type: "post",
                    url: ajaxurl,
                    data: {
                        action: "update_rev_template",
                        template_id: template_id,
                        options: options
                    },
                    success: function (response) {
                        rev_ajax_message(response);
                    }
                });
            });

            // load templates
            $("#rev_templates").on("change", function(){
                var options = $(this).find("option:selected").data('options');

                if(options){
                    if(typeof options['text'] != "undefined"){
                        $("#rev_text").val(options['text']);

                        delete options['text'];
                    }

                    if(typeof options['type'] != "undefined"){
                        $("#rev_card_type").val(options['type']);

                        delete options['type'];
                    }

                    $.each(options, function(key, value){
                        var $key        = $("#" + key);
                        var field_type  = $key.closest("tr").data('type');

                        if(field_type == "slider"){
                            $key.parent().find(".redux-slider-container").val(value);
                        } else if(field_type == "color"){
                            $key.iris('color', value);
                        } else {
                            $key.val(value);
                        }
                    });

                    // load google font
                    rev_load_google_font(options['rev_font_family']);

                    redux.field_objects.revolution_shortcode.generate_preview();
                    redux.field_objects.revolution_shortcode.update_card_content();
                    redux.field_objects.revolution_shortcode.update_card_type();
                }
            });

            $("#rev_save_as_new").on("click", function(e){
                e.preventDefault();

                $("#rev_save_as_form").slideDown();
                $("#rev_update_existing_form").slideUp();
            });

            $("#rev_save_as_existing").on("click", function(e){
                e.preventDefault();

                $("#rev_update_existing_form").slideDown();
                $("#rev_save_as_form").slideUp();
            });

            $("#rev_font_family").change( function(){
                var link = $(this).val();

                rev_load_google_font(link);
            });

            function rev_load_google_font(font){
                if ( typeof (WebFont) !== "undefined" && WebFont ) {
                    WebFont.load({
                        google: {
                            families: [font]
                        },
                        active: function(){
                            redux.field_objects.revolution_shortcode.generate_preview();
                        }
                    });
                }
            }
        }
    );

    redux.field_objects.revolution_shortcode .update_card_type = function(){
        var card_type = $("#rev_card_type").val();

        if(card_type == "info"){

            $("#rev_preview_text_container").slideUp();
            $("#revolution_slider_preview").html();
            $("#rev_preview_options tr.font_size").hide();
        } else {

            $("#rev_preview_text_container").slideDown();
            $("#revolution_slider_preview .inner").text($("#rev_text").val());
            $("#rev_preview_options tr.font_size").show();
        }
    };

    redux.field_objects.revolution_shortcode .update_card_content = function(){

        var listing_id   = $("#rev_listing_data").val();
        var data         = listing_data['listings'][listing_id];
        var current_text = $("#rev_text").val();
        var card_type    = $("#rev_card_type").val();

        if(card_type == "text") {

            $.each(data, function (key, val) {
                var search = "%%" + key + "%%";

                if (current_text.indexOf(search) !== -1) {
                    current_text = current_text.replace(search, val);
                }
            });

            $("#revolution_slider_preview .inner").html(current_text);
        } else {
            var inventory_template = "<div class=\"inventory clearfix\">" +
                "<a class=\"inventory\">" +
                "<div class=\"title\">" + data['title'] + "</div>" +
                "<table class=\"options-primary\">" +
                "<tbody>";

                $.each(listing_data['use_categories'], function(key, value){
                    inventory_template += "<tr><td class='option primary'>" + value.singular + "</td>";
                    inventory_template += "<td class='spec'>" + data[value.slug] + "</td></tr>";
                });

                inventory_template += "</tbody>" +
                "</table>" +
                "<div class=\"view-details gradient_button\"><i class=\"fa fa-plus-circle\"></i> View Details </div>" +
                "<div class=\"clearfix\"></div>" +
                "</a>";

                if(data['listing_price']) {
                    inventory_template += "<div class=\"price\">" +
                    "<b>" + listing_data['text']['price'] + ":</b>" +
                    "<br><div class=\"figure\">" + data['listing_price'] +
                    "<br>" +
                    "</div>" +
                    "<div class=\"tax\">" + listing_data['text']['tax'] + "</div>" +
                    "</div>";
                }

                inventory_template += "</div>";

                $("#revolution_slider_preview .inner").html(inventory_template);
        }
    };

    // generating live preview
    redux.field_objects.revolution_shortcode .generate_preview = function(){
        var $options  = $('#rev_preview_options');
        var $css      = $("#rev_shortcode_css");
        var card_type = $("#rev_card_type").val();


        var css       = "#revolution_slider_preview .inner {";

        css += "background-color: " + $("#rev_background_color").val() + "; ";
        css += "color: " + $("#rev_text_color").val() + "; ";
        css += "width: " + $("#rev_width").val() + "px; ";
        css += "padding: " + $("#rev_padding_vertical").val() + "px " + $("#rev_padding_horizontal").val() + "px; ";
        css += "border-radius: " + $("#rev_border_radius").val() + "px; ";
        css += (card_type == "text" ? "font-size: " + $("#rev_font_size").val() + "px; " : "");
        css += "border: " + $("#rev_border_width").val() + "px " + $("#rev_border_style").val() + " " + $("#rev_border_color").val() + ";";
        css += "font-family: '" + $("#rev_font_family").val() + "'";
        css += "}";

        //console.log(css);

        $css.html(css);

        // replace text vars
        redux.field_objects.revolution_shortcode.update_card_content();
    };

    redux.field_objects.revolution_shortcode .init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( '.redux-container-revolution_shortcode:visible' );
        }

        $( selector ).each(
            function() {
                var el = $( this );
                var parent = el;
                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( '.redux-field-container:first' );
                }
                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }
                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }
                el.find( '.redux-custom-badges-remove' ).live(
                    'click', function() {
                        redux_change( $( this ) );
                        $( this ).prev( 'input[type="text"]' ).val( '' );
                        $( this ).parent().slideUp(
                            'medium', function() {
                                $( this ).remove();

                                // update checkbox numbers
                                var i=0;
                                $("#revolution_shortcode-ul > li").filter(":visible").each( function(index, element){
                                    $(this).find("input[type='checkbox']").attr("name", "listing_wp[additional_categories][check][" + i + "]");
                                    i++;
                                });
                            }
                        );
                    }
                );

                el.find( '.redux-custom-badges-add' ).click(
                    function() {
                        var number = parseInt( $( this ).attr( 'data-add_number' ) );
                        var id = $( this ).attr( 'data-id' );
                        var name = $( this ).attr( 'data-name' );
                        for ( var i = 0; i < number; i++ ) {
                            var new_input = $( '#' + id + ' li:hidden' ).clone();
                            console.log(new_input);
                            el.find( '#' + id ).append( new_input );
                            el.find( '#' + id + ' li:last-child' ).removeAttr( 'style' );
                            el.find( '#' + id + ' li:last-child input[type="text"]' ).val( '' );
                            el.find( '#' + id + ' li:last-child input[type="text"]' ).attr( 'name', name );

                            el.find( '#' + id + ' li:last-child input[type="text"]' ).attr("name", "listing_wp[revolution_shortcode][name][" + ($("#revolution_shortcode-ul > li").length - 2) + "]");

                            el.find( '#' + id + ' li:last-child .custom_badge_color').attr("name", "listing_wp[revolution_shortcode][color][" + ($("#revolution_shortcode-ul > li").length - 2) + "]").addClass('redux-color-init ');
                            el.find( '#' + id + ' li:last-child .custom_badge_font').attr("name", "listing_wp[revolution_shortcode][font][" + ($("#revolution_shortcode-ul > li").length - 2) + "]").addClass('redux-color-init ');
                            //"listing_wp[additional_categories][check][" + $(".additional_categories-ul > li").length + "]"

                        }
                        redux.field_objects.rev_color .init();
                    }
                );
            }
        );

    };
})( jQuery );