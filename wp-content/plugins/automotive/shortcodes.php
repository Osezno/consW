<?php
//********************************************
//	TinyMCE Editor Button
//***********************************************************
function add_editor_button() {
	//Check if user has correct level of privileges + hook into Tiny MC methods.
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		//Check if Editor is in Visual, or rich text, edior mode.
		if ( get_user_option( 'rich_editing' ) ) {
			//Called when tiny MCE loads plugins - 'add_custom' is defined below.
			add_filter( 'mce_external_plugins', 'add_custom' );
			//Called when buttons are loading. -'register_button' is defined below.
			add_filter( 'mce_buttons', 'register_button' );
		}
	}
}
add_action( 'init', 'add_editor_button' );


//Add button to the button array.
function register_button( $buttons ) {
	//Use PHP 'array_push' function to add the columnThird button to the $buttons array
	array_push( $buttons, "shortcodebutton" );

	//Return buttons array to TinyMCE
	return $buttons;
}

//Add custom plugin to TinyMCE - returns associative array which contains link to JS file. The JS file will contain your plugin when created in the following step.
function add_custom( $plugin_array ) {
	$plugin_array['shortcodebutton'] = LISTING_DIR . 'js/editor.js';

	return $plugin_array;
}

// Quote
if ( ! function_exists( "post_quote" ) ) {
	function post_quote( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'color' => '#c7081b'
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/quote",
			array(
				"color"   => $color,
				"content" => $content
			)
		);
	}
}
add_shortcode( 'quote', 'post_quote' );

// inventory shortcode
if ( ! function_exists( "inventory_display" ) ) {
	function inventory_display( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'layout'        => 'wide_fullwidth',
			'hide_elements' => ''
		), $atts ) );

		wp_enqueue_script( 'isotope' );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/inventory",
            array(
                "layout"        => $layout,
                "hide_elements" => $hide_elements,
                "atts"          => $atts
            )
        );
	}
}
add_shortcode( "inventory_display", "inventory_display" );

// lists
if ( ! function_exists( "item_list" ) ) {
	function item_list( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'       => 'arrows',
			'extra_class' => ''
		), $atts ) );

		if ( isset( $style ) && ! empty( $style ) ) {
			$GLOBALS['list_icon_style'] = $style;
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/list",
			array(
				"style"         => $style,
                "extra_class"   => $extra_class,
				"content"       => $content
			)
		);
	}
}
add_shortcode( 'list', 'item_list' );

if ( ! function_exists( "list_item" ) ) {
	function list_item( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'icon' => ''
		), $atts ) );

		if ( isset( $icon ) && ! empty( $icon ) ) {
			$the_icon    = $icon;
			$custom_icon = true;
		} elseif ( isset( $GLOBALS['list_icon_style'] ) && $GLOBALS['list_icon_style'] == "arrows" ) {
			$the_icon = "fa fa-angle-right";
		} else {
			$the_icon = "fa fa-check";
		}

		if ( isset( $GLOBALS['list_icon_style'] ) && ! empty( $GLOBALS['list_icon_style'] ) && $GLOBALS['list_icon_style'] == "arrows" ) {
			$the_icon = "<span class=\"red_box" . ( isset( $custom_icon ) ? " custom_icon" : "" ) . "\"><i class='" . sanitize_html_classes($the_icon) . "'></i></span>";
		} else {
			$the_icon = "<span" . ( isset( $custom_icon ) ? " class='custom_icon'" : "" ) . "><i class='" . sanitize_html_classes($the_icon) . "'></i></span>";
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/list_item",
			array(
				"the_icon"      => $the_icon,
				"content"       => $content
			)
		);
	}
}
add_shortcode( 'list_item', 'list_item' );

// Dropcaps
if ( ! function_exists( "dropcaps" ) ) {
	function dropcaps( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"color"       => "#000",
            "extra_class" => ""
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/dropcaps",
			array(
				"content"       => $content,
                "extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( 'dropcaps', 'dropcaps' );

// Parallax Section
if ( ! function_exists( "parallax_section" ) ) {
	function parallax_section( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"title"         => "",
			"velocity"      => "-.3",
			"offset"        => "0",
			"image"         => "",
			"overlay_color" => "rgba(255, 255, 255, .65)",
			"text_color"    => "#FFFFFF",
			'temp_height'   => '',
			'extra_class'   => ''
		), $atts ) );

		global $Listing_Template;

		wp_enqueue_script( 'parallax' );

		$image = wp_get_attachment_image_src( $image, 'full' );

		$return = $Listing_Template->locate_template( "shortcodes/parallax",
			array(
				"content"       => $content,
                "temp_height"   => $temp_height,
                "velocity"      => $velocity,
                "offset"        => $offset,
                "image"         => $image,
                "overlay_color" => $overlay_color,
                "text_color"    => $text_color,
                "title"         => $title,
				"extra_class"   => $extra_class
			)
		);

		return ( function_exists( "wpb_js_remove_wpautop" ) ? wpb_js_remove_wpautop( $return ) : $return );
	}
}
add_shortcode( "parallax_section", "parallax_section" );

// Animated Numbers
if ( ! function_exists( "animated_numbers" ) ) {
	function animated_numbers( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"icon"              => "",
			"number"            => "",
			"before_number"     => "",
			"after_number"      => "",
			"alignment"         => "",
			"separator_value"   => ",",
			'extra_class'       => ''
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/animated_numbers",
			array(
				"content"           => $content,
                "icon"              => $icon,
                "alignment"         => $alignment,
                "before_number"     => $before_number,
                "number"            => $number,
                "separator_value"   => $separator_value,
                "after_number"      => $after_number,
				"extra_class"       => $extra_class
			)
		);
	}
}
add_shortcode( "animated_numbers", "animated_numbers" );

// Progress bars
if ( ! function_exists( "progress_bar" ) ) {
	function progress_bar( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"color"       => "#c7081b",
			"filled"      => "100%",
			"striped"     => "no",
			"animated"    => "no",
			"class"       => "",
			'extra_class' => ''
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/progress_bar",
			array(
				"content"       => $content,
				"class"         => $class,
				"color"         => $color,
				"filled"        => $filled,
				"extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( 'progress_bar', 'progress_bar' );

// Testimonials
if ( ! function_exists( "testimonials" ) ) {
	function testimonials( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"slide"       => "horizontal",
			"speed"       => 500,
			"pager"       => "false",
			'extra_class' => ''
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/testimonial",
			array(
				"content"       => $content,
				"slide"         => $slide,
				"speed"         => $speed,
				"pager"         => $pager,
				"extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "testimonials", "testimonials" );

if ( ! function_exists( "testimonial_quote" ) ) {
	function testimonial_quote( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"name"  => "Theodore Isaac Rubin",
			"quote" => "Happiness does not come from doing easy work but from the afterglow of satisfaction that comes after the achievement of a difficult task that demanded our best."
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/testimonial_quote",
            array(
                "name"      => $name,
                "content"   => $content
            )
        );
	}
}
add_shortcode( "testimonial_quote", "testimonial_quote" );

// Recent Post Scroller
if ( ! function_exists( "recent_posts_scroller" ) ) {
	function recent_posts_scroller( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"number"      => 2,
			"speed"       => 500,
			"pager"       => "false",
			"posts"       => 4,
			'extra_class' => '',
			'category'    => ''
		), $atts ) );

		$rand = rand();

		wp_enqueue_script( 'bxslider' );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/recent_posts",
			array(
				"rand"          => $rand,
                "number"        => $number,
                "posts"         => $posts,
                "category"      => $category,
				"content"       => $content,
                "extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "recent_posts_scroller", "recent_posts_scroller" );

// Faqs
if ( ! function_exists( "frequently_asked_questions" ) ) {
	function frequently_asked_questions( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"categories"   => "",
			"all_category" => "yes",
			"sort_text"    => "Sort FAQ By:",
			'extra_class'  => '',
			'sort_element' => 'yes'
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/faq",
			array(
				"sort_element"  => $sort_element,
				"sort_text"     => $sort_text,
				"categories"    => $categories,
                "all_category"  => $all_category,
				"content"       => $content,
				"extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "faq", "frequently_asked_questions" );

if ( ! function_exists( "toggle_item" ) ) {
	function toggle_item( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"title"      => "Title",
			"categories" => " ",
			"state"      => "collapsed"
		), $atts ) );

		$id = random_string();

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/toggle",
			array(
                "id"            => $id,
				"title"         => $title,
				"categories"    => $categories,
				"state"         => $state,
				"content"       => $content
			)
		);
	}
}
add_shortcode( "toggle", "toggle_item" );

// Staff person
if ( ! function_exists( "person" ) ) {
	function person( $atts, $content ) {
		extract( shortcode_atts( array(
			"name"        => "William Dean",
			"position"    => "Cheif Executive / CEO",
			"phone"       => "",
			"cell_phone"  => "",
			"email"       => "",
			"img"         => home_url() . "/wp-content/uploads/2013/07/william-dean.png",
			"hoverimg"    => "",
			"layout"      => "3",
			"facebook"    => false,
			"twitter"     => false,
			"youtube"     => false,
			"vimeo"       => false,
			"linkedin"    => false,
			"rss"         => false,
			"flickr"      => false,
			"skype"       => false,
			"google"      => false,
			"pinterest"   => false,
			"instagram"   => false,
			"yelp"        => false,
			'extra_class' => ''
		), $atts ) );

		global $icons, $Listing_Template;

		wp_enqueue_script( 'jqueryfancybox' );

		$img      = wp_get_attachment_url( $img );
		$hoverimg = wp_get_attachment_url( $hoverimg );

		return $Listing_Template->locate_template( "shortcodes/person",
			array(
				"content"       => $content,
                "hoverimg"      => $hoverimg,
                "img"           => $img,
                "icons"         => $icons,
                "name"          => $name,
                "position"      => $position,
                "phone"         => $phone,
                "cell_phone"    => $cell_phone,
                "email"         => $email,
                "facebook"      => $facebook,
                "twitter"       => $twitter,
                "youtube"       => $youtube,
                "vimeo"         => $vimeo,
                "linkedin"      => $linkedin,
                "rss"           => $rss,
                "flickr"        => $flickr,
                "skype"         => $skype,
                "google"        => $google,
                "pinterest"     => $pinterest,
                "instagram"     => $instagram,
                "yelp"          => $yelp,
                "extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( 'person', 'person' );

if ( ! function_exists( "featured_panel" ) ) {
	function featured_panel( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"title"           => "Featured Service",
			"icon"            => "",
			"hover_icon"      => "",
			"modal"           => false,
			"popover"         => false,
			"placement"       => "right",
			"popover_content" => "",
			"image_link"      => "",
			'extra_class'     => ''
		), $atts ) );

		$alt = get_post_meta( $icon, "_wp_attachment_image_alt", true );

		$icon       = wp_get_attachment_image_src( $icon );
		$hover_icon = wp_get_attachment_image_src( $hover_icon );

		if ( function_exists( "vc_build_link" ) ) {
			$image_link = vc_build_link( $image_link );
			$image_link = $image_link['url'];
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/featured_panel",
			array(
				"content"       => $content,
                "title"         => $title,
                "image_link"    => $image_link,
                "icon"          => $icon,
                "hover_icon"    => $hover_icon,
                "alt"           => $alt,
                "extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "featured_panel", "featured_panel" );

if ( ! function_exists( "detailed_panel" ) ) {
	function detailed_panel( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"title"       => "",
			"icon"        => "icon-wrench",
			'extra_class' => '',
			'link'        => '',
			'image'       => ''
		), $atts ) );

		$target = $href = "";

		if ( ! empty( $link ) ) {
			if ( function_exists( "vc_build_link" ) ) {
				$link = vc_build_link( $link );

				$href   = $link['url'];
				$target = $link['target'];
			} else {
				$href = $link;
			}
		}

		if ( isset( $image ) && ! empty( $image ) ) {
			$alt   = get_post_meta( $image, "_wp_attachment_image_alt", true );
			$image = wp_get_attachment_image_src( $image );

			$icon = "<img src='" . esc_url( $image[0] ) . "' alt=\"" . esc_attr( $alt ) . "\">";
		} else {
			$icon = "<i class='" . sanitize_html_classes( $icon ) . "'></i>";
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/detailed_panel",
			array(
				"content"       => $content,
				"title"         => $title,
                "icon"          => $icon,
                "target"        => $target,
                "href"          => $href,
                "extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "detailed_panel", "detailed_panel" );

// Featured Brands
if ( ! function_exists( "featured_brands" ) ) {
	function featured_brands( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'       => '',
			'extra_class' => ''
		), $atts ) );

		wp_enqueue_script( 'bxslider' );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/featured_brands",
			array(
				"content"       => $content,
				"title"         => $title,
				"extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "featured_brands", "featured_brands" );

if ( ! function_exists( "brand_logo" ) ) {
	function brand_logo( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"img"             => "",
			"hoverimg"        => "",
			"title"           => "",
			"link"            => "#"
		), $atts ) );

		$target = "";

		if ( function_exists( "vc_build_link" ) ) {
			$link   = vc_build_link( $link );
			$target = ( isset( $link['target'] ) && ! empty( $link['target'] ) ? $link['target'] : "" );
			$link   = $link['url'];
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/brand_logo",
			array(
				"content"       => $content,
				"title"         => $title,
                "img"           => $img,
                "link"          => $link,
                "hoverimg"      => $hoverimg,
                "target"        => $target
			)
		);
	}
}
add_shortcode( "brand_logo", "brand_logo" );

// Portfolio
if ( ! function_exists( "automotive_portfolio" ) ) {
	function automotive_portfolio( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"categories"   => "",
			"type"         => "details",
			"portfolio"    => 40,
			"columns"      => 3,
			"all_category" => "yes",
			"auto_resize"  => "yes",
			"sort_text"    => "Sort Portfolio By:",
			'extra_class'  => '',
			'sort_element' => 'yes',
			'order_by'     => 'ASC'
		), $atts ) );

		global $Listing_Template;

		wp_enqueue_script( 'mixit' );
		wp_enqueue_script( 'jqueryfancybox' );

		switch ( $columns ) {
			case 1:
				$class    = 12;
				$length   = 245;
				$img_size = array( 570, 296, true );
				break;

			case 2:
				$class    = 6;
				$length   = 245;
				$img_size = array( 570, 296, true );
				break;

			case 3:
				$class    = 4;
				$length   = 155;
				$img_size = array( 570, 296, true );
				break;

			case 4:
				$class    = 3;
				$length   = 115;
				$img_size = array( 570, 296, true );
				break;
		}

		return $Listing_Template->locate_template( "shortcodes/portfolio",
			array(
				"categories"    => $categories,
				"type"          => $type,
				"portfolio"     => $portfolio,
				"columns"       => $columns,
				"all_category"  => $all_category,
				"auto_resize"   => $auto_resize,
                "sort_text"     => $sort_text,
                "sort_element"  => $sort_element,
                "order_by"      => $order_by,
                "class"         => $class,
                "length"        => $length,
                "img_size"      => $img_size,
                "extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "portfolio", "automotive_portfolio" );

// Alert
if ( ! function_exists( "alert_shortcode" ) ) {
	function alert_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"type"        => "info",
			"close"       => "no",
			'extra_class' => ''
		), $atts ) );

		if ( $type == 0 ) {
			$type = "danger";
		} elseif ( $type == 1 ) {
			$type = "success";
		} elseif ( $type == 2 ) {
			$type = "info";
		} else {
			$type = "warning";
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/alert",
			array(
                "content"       => $content,
				"type"          => $type,
				"close"         => $close,
				"extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "alert", "alert_shortcode" );

// pricing table
if ( ! function_exists( "pricing_table" ) ) {
	function pricing_table( $atts, $content ) {
		extract( shortcode_atts( array(
			"title"       => "Standard",
			"price"       => "19.99",
			"often"       => "",
			"button"      => "Sign Up Now",
			"link"        => "#",
			'extra_class' => '',
			'header_color' => ''
		), $atts ) );

		$link = ( function_exists( "vc_build_link" ) ? vc_build_link( $link ) : $link );
		$link = ( function_exists( "vc_build_link" ) ? $link['url'] : $link );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/pricing_table",
			array(
				"content"       => $content,
				"title"         => $title,
                "price"         => $price,
				"often"         => $often,
                "button"        => $button,
                "link"          => $link,
                "header_color"  => $header_color,
				"extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "pricing_table", "pricing_table" );

if ( ! function_exists( "pricing_option" ) ) {
	function pricing_option( $atts, $content = null ) {
		extract( shortcode_atts( array(), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/pricing_option",
			array(
				"content" => $content
			)
		);
	}
}
add_shortcode( "pricing_option", "pricing_option" );

if ( ! function_exists( "featured_icon_box" ) ) {
	function featured_icon_box( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"icon"        => "fa fa-dashboard",
			"title"       => "",
			'extra_class' => ''
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/featured_icon_box",
			array(
				"content"       => $content,
                "icon"          => $icon,
                "title"         => $title,
                "extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "featured_icon_box", "featured_icon_box" );

if ( ! function_exists( "bolded" ) ) {
	function bolded( $atts, $content = null ) {

	    global $Listing_Template;

	    return $Listing_Template->locate_template( "shortcodes/bolded",
            array(
                "content" => $content
            )
        );
	}
}
add_shortcode( "bolded", "bolded" );

// Search box
if ( ! function_exists( "search_inventory_box" ) ) {
	function search_inventory_box( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"column_1"    => '',
			"column_2"    => '',
			"min_max"     => '',
			"page_id"     => '',
			'button_text' => __( 'Find My New Vehicle', 'listings' ),
			'extra_class' => '',
			'prefix_text' => '',
			'term_form'   => 'singular'
		), $atts ) );

		if ( function_exists( "vc_build_link" ) ) {
			$page_id = vc_build_link( $page_id );
			$page_id = $page_id['url'];
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/search_inventory_box",
			array(
				"column_1"      => $column_1,
                "column_2"      => $column_2,
                "min_max"       => $min_max,
                "page_id"       => $page_id,
                "button_text"   => $button_text,
                "extra_class"   => $extra_class,
                "prefix_text"   => $prefix_text,
                "term_form"     => $term_form
			)
		);
	}
}
add_shortcode( "search_inventory_box", "search_inventory_box" );

// Vehicle Scroller
if ( ! function_exists( "vehicle_scroller_shortcode" ) ) {
	function vehicle_scroller_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"title"       => "",
			"description" => "",
			"sort"        => "",
			"listings"    => "",
			'extra_class' => '',
			"limit"       => "1",
			"autoscroll"  => "false"
		), $atts ) );

		wp_enqueue_script( 'bxslider' );

		$other_options = array();

		if ( $autoscroll == "true" ) {
			$other_options['autoscroll'] = "true";
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/vehicle_scroller",
			array(
				"title"         => $title,
				"description"   => $description,
				"sort"          => $sort,
				"listings"      => $listings,
				"extra_class"   => $extra_class,
				"limit"         => $limit,
                "other_options" => $other_options,
				"autoscroll"    => $autoscroll,
                "atts"          => $atts
			)
		);
	}
}
add_shortcode( "vehicle_scroller", "vehicle_scroller_shortcode" );

// icon w/ title
if ( ! function_exists( "icon_title" ) ) {
	function icon_title( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'       => '',
			'icon'        => 'fa fa-dashboard',
			'extra_class' => '',
			'link'        => '#'
		), $atts ) );

		$target = "";

		if ( function_exists( "vc_build_link" ) ) {
			$link = vc_build_link( $link );
			$target = ( isset( $link['target'] ) && ! empty( $link['target'] ) ? $link['target'] : "" );
			$link = $link['url'];
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/icon_title",
			array(
				"title"     => $title,
				"icon"      => $icon,
				"link"      => $link,
                "target"    => $target
			)
		);
	}
}
add_shortcode( "icon_title", "icon_title" );

// Button
if ( ! function_exists( "auto_button_shortcode" ) ) {
	function auto_button_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"color"           => false,
			"color_2"         => false,
			"border"          => false,
			"hover_color"     => false,
			"modal"           => false,
			"popover"         => false,
			"placement"       => "right",
			"title"           => "",
			"popover_content" => "",
			"size"            => "",
			'extra_class'     => '',
			"href"            => '',
			"simple_link"     => false,
			"target"          => false
		), $atts ) );

		global $Listing_Template;

		if ( function_exists( "vc_build_link" ) && $simple_link === false ) {
			$link   = vc_build_link( $href );
			$target = ( isset( $link['target'] ) && ! empty( $link['target'] ) ? $link['target'] : "" );
			$link   = $link['url'];
		} else {
			$link = ( isset( $href ) && ! empty( $href ) ? $href : "" );
		}

		return $Listing_Template->locate_template( "shortcodes/button",
			array(
				"link"              => $link,
				"target"            => $target,
				"extra_class"       => $extra_class,
				"size"              => $size,
                "color"             => $color,
                "hover_color"       => $hover_color,
                "modal"             => $modal,
                "popover"           => $popover,
                "placement"         => $placement,
                "title"             => $title,
                "popover_content"   => $popover_content,
                "content"           => $content
			)
		);
	}
}
add_shortcode( "button", "auto_button_shortcode" );

// flipping card
if ( ! function_exists( "flipping_card" ) ) {
	function flipping_card( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'image'       => '',
			'larger_img'  => '',
			'title'       => '',
			'link'        => '',
			'extra_class' => '',
			'card_link'   => '',
			'alt'         => ''
		), $atts ) );

		wp_enqueue_script( 'jqueryfancybox' );

		$target = "";

		if ( function_exists( "vc_build_link" ) ) {
			$link   = vc_build_link( $link );
			$target = ( isset( $link['target'] ) && ! empty( $link['target'] ) ? $link['target'] : "" );
			$link   = $link['url'];


			$card_link = vc_build_link( $card_link );
			$card_link = $card_link['url'];


			$alt        = get_post_meta( $image, "_wp_attachment_image_alt", true );
			$image      = wp_get_attachment_url( $image );
			$larger_img = wp_get_attachment_url( $larger_img );
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/flipping_card",
			array(
				"image"         => $image,
				"larger_img"    => $larger_img,
                "title"         => $title,
				"link"          => $link,
				"target"        => $target,
                "extra_class"   => $extra_class,
                "card_link"     => $card_link,
                "alt"           => $alt
			)
		);
	}
}
add_shortcode( "flipping_card", "flipping_card" );

// contact form
if ( ! function_exists( "auto_contact_form" ) ) {
	function auto_contact_form( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'name'        => __( "Name  (Required)", "listings" ),
			'email'       => __( "Email  (Required)", "listings" ),
			'message'     => __( "Your Message", "listings" ),
			'button'      => __( "Send Message", "listings" ),
			'extra_class' => ''
		), $atts ) );

		wp_enqueue_script( 'contact_form' );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/contact_form",
			array(
				"name"          => $name,
				"email"         => $email,
				"message"       => $message,
				"button"        => $button,
				"extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "auto_contact_form", "auto_contact_form" );

// hours table
if ( ! function_exists( "hours_table" ) ) {
	function hours_table( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'mon'         => __( "Closed", "listings" ),
			'tue'         => __( "Closed", "listings" ),
			'wed'         => __( "Closed", "listings" ),
			'thu'         => __( "Closed", "listings" ),
			'fri'         => __( "Closed", "listings" ),
			'sat'         => __( "Closed", "listings" ),
			'sun'         => __( "Closed", "listings" ),
			'title'       => __( "Hours", "listings" ),
			'extra_class' => ''
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/hours_table",
			array(
				"mon"           => $mon,
				"tue"           => $tue,
				"wed"           => $wed,
				"thu"           => $thu,
				"fri"           => $fri,
                "sat"           => $sat,
                "sun"           => $sun,
                "title"         => $title,
				"extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "hours_table", "hours_table" );

// contact information
if ( ! function_exists( "auto_contact_information" ) ) {
	function auto_contact_information( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'company'     => '',
			'address'     => '',
			'phone'       => '',
			'email'       => '',
			'web'         => '',
			'fax'         => '',
			'extra_class' => ''
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/contact_information",
			array(
				"company"       => $company,
				"address"       => $address,
				"phone"         => $phone,
				"email"         => $email,
				"web"           => $web,
				"fax"           => $fax,
				"extra_class"   => $extra_class
			)
		);
	}
}
add_shortcode( "auto_contact_information", "auto_contact_information" );

// google map
if ( ! function_exists( "auto_google_map" ) ) {
	function auto_google_map( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'longitude'           => '-79.38',
			'latitude'            => '43.65',
			'zoom'                => '7',
			'height'              => '390',
			'map_style'           => '',
			'scrolling'           => 'true',
			'extra_class'         => '',
			'parallax_disabled'   => '',
			'scrolling_disabled'  => '',
			'info_window_content' => '',
			'directions_button'   => '',
			'directions_text'     => 'Get Directions',
            'map_type'            => 'roadmap'
		), $atts ) );

		wp_enqueue_script( 'google-maps' );

		if ( base64_encode( base64_decode( $map_style ) ) === $map_style ) {
			$map_style = urldecode( html_entity_decode( base64_decode( $map_style ) ) );
		}

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/google_map",
            array(
                "longitude"             => $longitude,
                "latitude"              => $latitude,
                "zoom"                  => $zoom,
                "height"                => $height,
                "map_style"             => $map_style,
                "scrolling"             => $scrolling,
                "extra_class"           => $extra_class,
                "parallax_disabled"     => $parallax_disabled,
                "scrolling_disabled"    => $scrolling_disabled,
                "info_window_content"   => $info_window_content,
                "directions_button"     => $directions_button,
                "directions_text"       => $directions_text,
                "map_type"              => $map_type
            )
        );
	}
}
add_shortcode( "auto_google_map", "auto_google_map" );

// Modal Window
if ( ! function_exists( "modal_window" ) ) {
	function modal_window( $atts, $content = null ) {
		extract( shortcode_atts( array(
			"title" => "",
			"id"    => ""
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/modal",
            array(
                "content"   => $content,
                "title"     => $title,
                "id"        => $id
            )
        );
	}
}
add_shortcode( "modal", "modal_window" );

// Tabs
if ( ! function_exists( "tabs" ) ) {
	function tabs( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'       => '',
			'extra_class' => ''
		), $atts ) );
		$GLOBALS['auto_tab_count'] = 0;

		do_shortcode( $content );

		global $Listing_Template;

		$return = $Listing_Template->locate_template( "shortcodes/tabs",
            array(
                "title"         => $title,
                "content"       => $content,
                "extra_class"   => $extra_class
            )
        );

		$GLOBALS['auto_tab_count'] = 0;
		$GLOBALS['auto_tabs']      = array();

		return $return;
	}
}
add_shortcode( 'tabs', 'tabs' );

// Single tab
if ( ! function_exists( "single_tab" ) ) {
	function single_tab( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title' => ''
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/tab",
            array(
                "title"     => $title,
                "content"   => $content
            )
        );
	}
}
add_shortcode( 'tab', 'single_tab' );

// Video
if ( ! function_exists( "auto_video" ) ) {
	function auto_video( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'url'    => 'http://www.youtube.com/watch?v=3f7l-Z4NF70',
			'width'  => 560,
			'height' => 315,
			'vq'     => 'hd720'
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/video",
            array(
                "url"       => $url,
                "width"     => $width,
                "height"    => $height
            )
        );
	}
}
add_shortcode( 'auto_video', 'auto_video' );

// heading
if ( ! function_exists( "heading_shortcode" ) ) {
	function heading_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'heading' => 'h1'
		), $atts ) );

		global $Listing_Template;

		return $Listing_Template->locate_template( "shortcodes/heading",
			array(
				"heading"   => $heading,
				"content"   => $content
			)
		);
	}
}
add_shortcode( "heading", "heading_shortcode" );

// Clearfix
if ( ! function_exists( "clear_both" ) ) {
	function clear_both( $atts, $content = null ) {
		return "<div class='clearfix'></div>";
	}
}
add_shortcode( "clear", "clear_both" );

// Line break
if ( ! function_exists( "line_break" ) ) {
	function line_break( $atts, $content = null ) {
		return "<br />";
	}
}
add_shortcode( "br", "line_break" );

if ( ! function_exists( "car_comparison_sc" ) ) {
	function car_comparison_sc( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'car_ids' => ''
		), $atts ) );

		global $Listing_Template, $lwp_options;

		$singular_vehicle_text = strtolower( (isset($lwp_options['vehicle_singular_form']) && !empty($lwp_options['vehicle_singular_form']) ? $lwp_options['vehicle_singular_form'] : "vehicle") );
		$plural_vehicle_text   = strtolower( (isset($lwp_options['vehicle_plural_form']) && !empty($lwp_options['vehicle_plural_form']) ? $lwp_options['vehicle_plural_form'] : "vehicles") );

		if ( empty( $car_ids ) ) {
			$cookie = ( isset( $_COOKIE['compare_vehicles'] ) && ! empty( $_COOKIE['compare_vehicles'] ) ? $_COOKIE['compare_vehicles'] : "" );
		} else {
			$cookie = $car_ids;
		}

		ob_start();

		if ( isset( $cookie ) && ! empty( $cookie ) ) {
			$cookie = htmlspecialchars( urldecode( $cookie ) );
			$cookie = array_filter( explode( ",", $cookie ) );
			$total  = count( $cookie );

			if ( $total <= 1 ) {
				echo sprintf(__( "You must have more than 1 %s selected to compare it.", "listings" ), $singular_vehicle_text);
			} else {
				switch ( $total ) {
					case 2:
						$class = "6";
						break;

					case 3:
						$class = "4";
						break;

					case 4:
						$class = "3";
						break;
				}

				echo "<div class='row total_" . $total . "'>";

				if ( $total >= 5 ) {
					echo sprintf(__( "Maximum 4 %s", "listings" ), $plural_vehicle_text);
				} else {
					foreach ( $cookie as $car ) {
						echo $Listing_Template->locate_template("car_comparison", array("car" => $car, "class" => $class));
					}
				}
				echo "</div>";
			}
		} else {
			echo sprintf( __( "You have no %s selected", "listings" ), $plural_vehicle_text);
		}

		$return = ob_get_clean();

		return $return;
	}
}
add_shortcode( "car_comparison", "car_comparison_sc" );

//********************************************
//	Shortcode Generator
//***********************************************************
function shortcode_dialog() {
	$shortcodes = array(
		"columns"  => "columns",
		"elements" => array(
			"button"  => "Button",
			"heading" => "Heading"
		),
		"other"    => array(
			"testimonials"                 => "Testimonials",
			"progress_bar"                 => "Progress Bar",
			"dropcaps"                     => "Dropcaps",
			"list"                         => "List",
			"tooltip"                      => "Tooltip",
			"quote"                        => "Quote",
			"portfolio"                    => "Portfolio",
			"alert"                        => "Alert",
			"search_inventory_box"         => "Inventory Search Box",
			"vehicle_scroller"             => "Vehicle Scroller",
			"modal"                        => "Modal Window",
			"tabs"                         => "Tabs",
			"auto_video"                   => "Video",
			"insert-clear"                 => "Clear Fix",
			"insert-br"                    => "Line Break",
			"pricing_table"                => "Pricing Table",
			"faq"                          => "FAQ",
			"featured_brands"              => "Featured Brands",
			"insert-recent_posts_scroller" => "Recent Posts"
		),
		"icons"    => "icons"

	);

	echo "<div id='shortcode-modal' style='display: none;'>";
	echo "<ul class='shortcode_list'>";

	ksort_deep( $shortcodes );

	// icons
	$icons = array(
		"columns"  => "fa-columns",
		"elements" => "fa-code",
		"icons"    => "fa-picture-o",
		"other"    => "fa-wrench"
	);

	$child_icons = array(
		"quote"                        => "fa-quote-left",
		"alert"                        => "fa-warning",
		"list"                         => "fa-list",
		"dropcaps"                     => "fa-text-height",
		"vehicle_scroller"             => "fa-truck",
		"progress_bar"                 => "fa-tasks",
		"search_inventory_box"         => "fa-search",
		"portfolio"                    => "fa-folder-open-o",
		"modal"                        => "fa-list-alt",
		"testimonials"                 => "fa-comments-o",
		"button"                       => "fa-certificate",
		"featured_icon_boxes"          => "fa-th-large",
		"tabs"                         => "fa-folder",
		"tooltip"                      => "fa-info",
		"auto_video"                   => "fa-youtube-play",
		"insert-br"                    => "fa-level-down",
		"insert-clear"                 => "fa-sort-amount-asc",
		"pricing_table"                => "fa-usd",
		"faq"                          => "fa-question-circle",
		"featured_brands"              => "fa-html5",
		"heading"                      => "fa-font",
		"insert-car_comparison"        => "fa-reorder",
		"insert-listings"              => "fa-list-alt",
		"insert-recent_posts_scroller" => "fa-indent"
	);

	foreach ( $shortcodes as $key => $shortcode ) {
		echo "<li>" . ( isset( $icons[ $key ] ) ? "<i class='fa " . $icons[ $key ] . "'></i>" : "" ) . " <a href='#' data-title='" . $key . "'>" . ucwords( $key ) . "</a>";
		if ( is_array( $shortcode ) ) {
			echo "<ul class='child_shortcodes'>";
			foreach ( $shortcode as $key => $code ) {
				echo "<li>" . ( isset( $child_icons[ $key ] ) ? "<i class='fa " . $child_icons[ $key ] . "'></i>" : "" ) . " <a href='#' data-shortcode='" . $key . "'>" . $code . "</a></li>";
			}
			echo "</ul>";
		}
		echo "</li>";
	}
	echo "</ul>";

	echo "<div class='shortcode_generator'>";

	echo "</div>";
	echo "<div class='column_generator'>";

	echo "</div>";
	echo "</div>";
}
add_action( 'admin_footer', 'shortcode_dialog' );

function generate_shortcode() {
	$form = array();
	switch ( $_POST['shortcode'] ) {
		case "progress_bar":
			$form['color']    = "color_picker";
			$form['filled']   = "text";
			$form['content']  = "text";
			$form['striped']  = array( "select", array( "on" => "On", "off" => "Off" ) );
			$form['animated'] = array( "select", array( "on" => "On", "off" => "Off" ) );
			break;

		case "dropcaps":
			$form['size'] = array( "size", "px", "5", "250" );
			break;

		case "list":
			$form['style']                = array(
				"select",
				array( "arrows" => "arrows", "checkboxes" => "checkboxes" )
			);
			$form['number_of_list_items'] = array( "number", "list_item", "icon" );
			break;

		case "tooltip":
			$form['title']     = "text";
			$form['placement'] = array(
				"select",
				array( "top" => "top", "right" => "right", "bottom" => "bottom", "left" => "left" )
			);
			$form['content']   = "text";
			$form['html']      = array( "select", array( "false" => "Off", "true" => "On" ) );
			break;

		case "quote":
			$form['color'] = "color_picker";
			break;

		case "testimonials":
			$form['number_of_testimonial_quote'] = array( "number", "testimonial_quote", "name" );
			break;

		case "portfolio":
			$portfolios = get_terms( "portfolio_in" );
			$categories = get_terms( "project-type" );

			$form['categories'] = array( "select", $categories, "multi" );
			$form['portfolio']  = array( "select", $portfolios );
			$form['type']       = array( "select", array( "details" => "details", "classic" => "classic" ) );
			$form['columns']    = array( "select", array( 2 => 2, 3 => 3, 4 => 4 ) );
			break;

		case "alert":
			$form['type'] = array( "select", array( "error", "success", "warning", "info" ) );
			break;

		case "featured_icon_boxes":
			$form['featured_icon_box'] = array( "number", "featured_icon_box", "title,icon" );
			break;

		case "search_inventory_box":
			$all_pages = get_pages();
			$pages     = array();

			foreach ( $all_pages as $page ) {
				$pages[ $page->ID ] = $page->post_title;
			}

			$form['page'] = array( "select", $pages );
			break;

		case "vehicle_scroller":
			$all_listings = get_posts( array( 'post_type' => 'listings' ) );
			$listings     = array();

			foreach ( $all_listings as $single_listing ) {
				$listings[ $single_listing->ID ] = $single_listing->post_title;
			}

			$form['title']       = "text";
			$form['description'] = "text";
			$form['sort']        = array(
				"select",
				array( "newest" => "newest", "oldest" => "oldest", "similar" => "similar" )
			);
			$form['listings']    = array( "select", array_filter( $listings ), "multi" );
			break;

		case "button":
			$form['content']        = "text";
			$form['color']          = "color_picker";
			$form['hover_color']    = "color_picker";
			$form['href']           = "text";
			$form['target']         = array("select",
				array(
					"_self"    => __("Open in this tab", "listings"),
					"_blank"   => __("Open in a new tab", "listings"),
					"_parent"  => __("Open in parent tab", "listings")
				)
			);
			break;

		case "heading":
			$form['heading'] = array(
				"select",
				array(
					"h1" => "Heading 1 (&lt;h1>)",
					"h2" => "Heading 2 (&lt;h2>)",
					"h3" => "Heading 3 (&lt;h3>)",
					"h4" => "Heading 4 (&lt;h4>)",
					"h5" => "Heading 5 (&lt;h5>)",
					"h6" => "Heading 6 (&lt;h6>)"
				)
			);
			$form['content'] = "text";
			break;

		case "modal":
			$form['id']      = "text";
			$form['title']   = "text";
			$form['content'] = "text";
			break;

		case "tabs":
			$form['number_of_tabs'] = array( "number", "tab", "title" );
			break;

		case "auto_video":
			$form['url']    = "text";
			$form['width']  = "text";
			$form['height'] = "text";
			break;

		case "pricing_table":
			$form['title']             = "text";
			$form['price']             = "text";
			$form['button']            = "text";
			$form['link']              = "text";
			$form['number_of_options'] = array( "number", "pricing_option", "" );
			break;

		case "faq":
			$form['categories']      = "text";
			$form['number_of_items'] = array( "number", "toggle", "title,categories" );
			break;

		case "featured_brands":
			$form['number_of_brands'] = array( "number", "brand_logo", "img,hoverimg" );
			break;

		default:
			$form['column_content'] = array( "column_content", $_POST['shortcode'] );
			break;
	}

	process_form( $form, $_POST['shortcode'] );

	die;
}
add_action( "wp_ajax_generate_shortcode", "generate_shortcode" );
add_action( "wp_ajax_nopriv_generate_shortcode", "generate_shortcode" );

function process_form( $form, $shortcode ) { ?>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			$('.color-picker').wpColorPicker();

			if ($("input[name='color']").length > 1) {
				i = 1;
				$("input[name='color']").each(function (index, element) {
					var name = $(this).data('name');

					if (name) {
						$(this).attr('name', name);
					} else {
						$(this).attr('name', 'color_' + i);
						i++;
					}
				});
			}
			$('.ui-dialog-title').html("<?php echo str_replace("_", " ", ucwords($shortcode)); ?>");
			//$('div.ui-dialog-titlebar.ui-widget-header.ui-corner-all.ui-helper-clearfix').append('<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only shortcode_back" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon ui-icon-circle-triangle-w"></span></button>');
			$("#generateShortcode").one("click", function () {
				var shortcode_name = "<?php echo $shortcode; ?>";

				shortcode = "[" + shortcode_name;
				var content = false;
				var no_closing = false;
				var is_html = false;
				var first = shortcode_name.substr(0, 1);

				if (shortcode_name == "hours_of_operation") {
					shortcode = shortcode + "]<br>";
				}

				if(shortcode_name == "button"){
					shortcode = shortcode + " simple_link='true'";
				}

				if ($("#generateShortcode").hasClass('slider_gen')) {
					var add_shortcode = "";
					$("#shortcode_options :input").not(".title, .title_toggle, .ajax_created").each(function (index, element) {
						var name = $(this).attr('name');
						var value = $(this).val();

						add_shortcode += " " + name + "='" + value + "'";

					});

					shortcode += add_shortcode + "]<br>";
				}

				if ($.isNumeric(first)) {
					is_html = true;
					switch (shortcode_name.substr(2, shortcode_name.length)) {
						case "full":
							var span = 12;
							break;
						case "halfs":
							var span = 6;
							break;
						case "thirds":
							var span = 4;
							break;
						case "fourths":
							var span = 3;
							break;
						case "seconds":
							var span = 2;
					}

					shortcode = "<div class='width row-fluid'>";
					$("#shortcode_options :input").not(".title, .title_toggle").each(function (index, element) {
						var value = $(this).val();
						if ($(".title_toggle:checkbox:checked").length > 0) {
							var string = String($(this).classes());
							var heading = $("select." + string + " option:selected").text();

							shortcode = shortcode + "<div class='span" + span + "'><" + heading + ">" + $("input." + string).val() + "</" + heading + ">" + value + "</div>";
						} else {
							shortcode = shortcode + "<div class='span" + span + "'>" + value + "</div>";
						}
					});
					shortcode = shortcode + "</div>";
				}

				// using the slider to generate shortcode
				if ($("#generateShortcode").hasClass('slider_gen')) {
					$(".ajax_form_slider table").each(function (index, element) {
						var useloop = $(this).data('useloop');
						var content = '';

						shortcode += "[" + useloop + " ";

						$(this).find(":input").each(function (index2, element2) {
							var name = $(this).attr('name');
							var value = $(this).val();

							if (name == "content") {
								content = value;
							} else {
								shortcode += name + "='" + value + "' ";
							}
						});

						shortcode += "]" + (content != "" ? content : "") + "[/" + useloop + "]<br />";
					});

					shortcode += "[/" + shortcode_name + "]<br />";
					is_html = true;
				} else {
					$("#shortcode_options :input").not(".wp-picker-clear").each(function (index, element) {
						var name = $(this).attr("name");
						var loop = $(this).data('loop');

						if (name == "hours_of_operation") {
							var value = 1;
							var field_value = $(this).val();
						} else {
							var value = $(this).val();
							var field_value = null;
						}

						if (!is_html) {

							if (loop) {
								var loop_attr = $(this).data('loopattr');

								if (loop_attr) {
									var attributes = loop_attr.split(",");

									if (field_value == "icon") {
										var value = 2;
									}

									for (var i = 0; i < value; i++) {

										if (name != "hours_of_operation") {
											shortcode = (i == 0 ? shortcode + "]<br />" : shortcode) + "[" + loop;
										} else {
											shortcode = shortcode + "[" + loop;
										}

										if (field_value != "hours") {

											for (var ii = 0; ii < attributes.length; ii++) {
												if (name == "hours_of_operation" && attributes[ii] == "type") {
													shortcode = shortcode + " " + attributes[ii] + "='" + field_value + "'";
												} else {
													shortcode = shortcode + " " + attributes[ii] + "=''";
												}
											}

										} else {
											shortcode = shortcode + " type='hours'";
										}

										if (name == "hours_of_operation") {
											shortcode = shortcode + "]<br />";
										} else if (field_value != "hours" && name == "hours_of_operation") {
										} else {
											shortcode = shortcode + "] [/" + loop + "]<br />";
										}
									}
								} else {
									for (var i = 0; i < value; i++) {
										shortcode = (i == 0 ? shortcode + "]<br />" : shortcode) + "[" + loop + "] [/" + loop + "]<br />";
									}
								}

								no_closing = true;
							} else if (name != "content") {
								shortcode = shortcode + " " + name + "='" + value + "'";
							} else {
								content = value;
							}
						}
					});
				}

				getContent = tinyMCE.activeEditor.selection.getContent();

				if (!is_html) {
					shortcode = (no_closing !== true ? shortcode + "]" : shortcode) + (content !== false ? content : getContent) + "[/" + shortcode_name + "]";
				}

				//$("#shortcode-modal").dialog("close");
				$("body").css({overflow: 'auto'});
				$("#shortcode-modal").dialog().dialog("destroy");
				$("#shortcode-modal .shortcode_generator, #shortcode-modal .shortcode_list ul.child_shortcodes").hide();
				$("#shortcode-modal .shortcode_list").show();

				//tinyMCE.execInstanceCommand('content', "mceInsertContent", false, shortcode);
				tinyMCE.execCommand('mceInsertContent', false, shortcode);
				return false;
			});

			$(".shortcode_slider").each(function (index, element) {
				var id = $(this).data('id');
				var minimum = $(this).data('min');
				var maximum = $(this).data('max');
				var units = $(this).data('unit');

				$(this).slider({
					min: minimum,
					max: maximum,
					slide: function (event, ui) {
						$("#" + id).val(ui.value + units);
					}
				});
			});

			function toTitleCase(str) {
				return str.replace(/\w\S*/g, function (txt) {
					return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
				});
			}

			function makeid() {
				var text = "";
				var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

				for (var i = 0; i < 7; i++)
					text += possible.charAt(Math.floor(Math.random() * possible.length));

				return text;
			}

			function stringFill3(x, n) {
				var s = '';
				for (; ;) {
					var random_data = makeid();

					if (n & 1) s += x.replace("EASTEREGG", random_data);
					n >>= 1;
					if (n) x += x.replace("EASTEREGG", random_data);
					else break;
				}
				return s;
			}

			if ($("#number_of_slider").length) {
				var name = $("#number_of_slider").data('name');
				var loop = $("#number_of_slider").data('loop');
				var loopattr = $("#number_of_slider").data('loopattr');

				$("#generateShortcode").addClass('slider_gen');

				$("#number_of_slider").slider({
					min: 0,
					max: 10,
					slide: function (event, ui) {
						var generated_html = "";

						//$("input[name='" + name + "']").val( ui.value );
						$(".slider_number").text(ui.value);

						var generated_html = stringFill3($(".hidden_form").html(), ui.value);

						$(".ajax_form_slider").html(generated_html);
					}
				});

				//$("input[name='" + name + "']").val( $("#number_of_slider").slider( "value" ) );
				$(".slider_number").text($("#number_of_slider").slider("value"));
			}
		});
	</script>

	<?php
	echo "<table border='0' id='shortcode_options'>";

	$has_toolpop = array( "button", "featured_icon_box", "brand_logo", "featured_panel" );

	foreach ( $form as $key => $item ) {
		if ( $key == "hours_of_operation_item" ) {
			$select_menu = "<select name='hours_of_operation' data-loop='hours_of_operation_item' data-loopattr='type,title,icon'>";
			$select_menu .= "<option value='icon'>2 " . __( "Icons", "listings" ) . "</option>";
			$select_menu .= "<option value='hours'>" . __( "Hours", "listings" ) . "</option>";
			$select_menu .= "</select>";

			echo "<tr><td style='width: 100px;'>" . __( "First Group", "listings" ) . ": </td><td>" . $select_menu . "</td></tr>";
			echo "<tr><td style='width: 100px;'>" . __( "Second Group", "listings" ) . ": </td><td>" . $select_menu . "</td></tr>";
			echo "<tr><td style='width: 100px;'>" . __( "Third Group", "listings" ) . ": </td><td>" . $select_menu . "</td></tr>";
		} else {
			echo "<tr><td class='spacer'></td></tr>";
			echo "<tr><td style='width: 100px; vertical-align: top'>";
			if ( $key == "html" ) {
				$label = __( "HTML in Tooltip", "listings" );
			} elseif ( $key == "filled" ) {
				$label = $key . " (%)";
			} elseif( $key == "href"){
				$label = __("Link", "listings");
			} else {
				$label = $key;
			}
			echo str_replace( "_", " ", ucwords( $label ) );
			echo ": " . ( $item[0] == "number" ? "<span class='slider_number'>0</span>" : "" ) . ( $item[0] == "column_content" ? "<br><br> Titles: <input type='checkbox' class='title_toggle'>" : "" ) . "</td></tr><tr><td>";

			switch ( $item ) {
				case "color_picker":
					echo "<input type=\"text\" value=\"#c7081b\" class=\"color-picker\" name=\"color\" data-name=\"" . $key . "\" />";
					break;

				case "text":
					echo "<input type=\"text\" name=\"" . $key . "\" value=\"\" />";
					break;

				case "icon":
					$random_string = random_string();
					echo "<span class='button sc_icon_selector' data-code='" . $random_string . "'>Icon: </span>";
					break;

				default:
					switch ( $item[0] ) {
						case "size":
							$id = random_string();
							echo "<div data-unit=\"" . $item[1] . "\" data-min=\"" . $item[2] . "\" data-max=\"" . $item[3] . "\" data-id=\"" . $id . "\" class=\"shortcode_slider\"></div>";
							echo "<input type=\"text\" name=\"" . $key . "\" value=\"" . $item[2] . $item[1] . "\" id=\"" . $id . "\" />";
							break;

						case "number":
							echo "<div id='number_of_slider' data-name='" . $key . "' data-loop=\"" . $item[1] . "\" " . ( isset( $item[2] ) && ! empty( $item[2] ) ? "data-loopattr=\"" . $item[2] . "\"" : "" ) . "></div>";
							//echo "<input type=\"text\" name=\"" . $key . "\" />";
							echo "<br>";

							echo "<div class='hidden_form'>";
							$atts = explode( ",", $item[2] );


							echo "<div class='shortcode_boxed_item' data-label='" . ucwords( str_replace( "_", " ", $item[1] ) ) . "'>";
							echo "<span class='hidden_click_event'></span>";
							echo "<table border='0' data-useloop='" . $item[1] . "'>";
							if ( ! empty( $item[2] ) ) {
								$i = 0;
								foreach ( $atts as $attr ) {
									if ( $attr == "img" || $attr == "image" || $attr == "hoverimg" ) {
										$images = get_all_media_images();
										$input  = "<select name='" . $attr . "' class='ajax_created'>";
										$input .= "<option value=''>" . __( "None", "listings" ) . "</option>";
										foreach ( $images as $image ) {
											$input .= "<option value='" . $image . "'>" . $image . "</option>\n";
										}
										$input .= "</select>";
									} elseif ( $attr == "icon" ) {
										$input = "<span class='button sc_icon_selector' data-code='EASTEREGG'>" . __( "Icon", "listings" ) . ": </span>";

									} else {
										$input = "<input type='text' name='" . $attr . "' class='ajax_created'>";
									}

									echo "<tr><td>" . ucwords( str_replace( "_", " ", $attr ) ) . ": </td><td> " . $input . " " . ( $i == 0 ? "<i class='fa fa-collapse-o shrink no_custom'></i>" : "" ) . "</td></tr>";
									$i ++;
								}
							}
							echo( $item[1] != "brand_logo" ? "<tr><td>" . __( "Content", "listings" ) . ": </td><td> <textarea name='content' class='ajax_created'></textarea> " . ( empty( $item[2] ) ? "<i class='fa fa-collapse-o shrink no_custom'></i>" : "" ) . "</td></tr>" : "" );
							echo "</table>";
							echo "</div>";

							echo "</div>";

							echo "<div class='ajax_form_slider'></div>";
							break;

						case "column_content":
							$number = $item[1][0];
							$i      = 1;

							while ( $i <= $number ) {
								echo "<textarea name=\"column\" style=\"width: 100%;\">" . __( "Content for column", "listings" ) . " " . $i . "</textarea><br>";
								$i ++;
							}
							break;

						case "select":
							$new_item = array_values( $item[1] );

							echo "<select name='" . $key . "'" . ( $key == "style" ? " data-parentattr='" . $key . "'" : "" ) . ( isset( $item[2] ) && $item[2] == "multi" ? " multiple='multiple' class='multi-select" . ( $key == "categories" ? " categories" : "" ) . "'" : "" ) . ">";

							if ( is_object( $new_item[0] ) ) {
								foreach ( $new_item as $option ) {
									echo "<option value='" . ( $key == "categories" ? $option->name : $option->term_id ) . "'>" . $option->name . "</option>";
								}
							} else {
								foreach ( $item[1] as $key => $option ) {
									echo "<option value='" . $key . "'>" . ucwords( $option ) . "</option>";
								}
							}

							echo "</select>";
							break;
					}
					break;
			}
			echo "</td></tr>";
		}
	}

	echo "</table>";

	echo "<button id=\"generateShortcode\" class=\"button btn\" style=\"bottom: 12px; position: relative;\">" . __( "Generate Shortcode", "listings" ) . "</button>";

	if ( in_array( $_POST['shortcode'], $has_toolpop ) ) {
		echo "<span class='generateModal button btn'>" . __( "Link to modal", "listings" ) . "</span> <span class='generatePopover button btn'>" . __( "Add a popover", "listings" ) . "</span>";
	}


	echo "<div id='sc_icon_selector_dialog' style='display:none;' title='" . __( "Icons", "listings" ) . "'>";
	echo "<input type='text' class='icon_search' style='width: 98%;' placeholder='" . __( "Search Icons", "listings" ) . "' /><br />";

	$default_fontello  = get_option( 'default_fontello_font' );
	$fontawesome_icons = get_fontawesome_icons();

	echo "<h2>" . __( "Font Awesome", "listings" ) . "</h2>";
	foreach ( $fontawesome_icons as $key => $match ) {
		echo "<i class='" . $key . " fa'></i>";
	}

}

function generate_icons() {
	echo "<input type='text' class='icon_search' style='width: 98%;' placeholder='" . __( "Search Icons", "listings" ) . "' /><br />";

	$default_fontello  = get_option( 'default_fontello_font' );
	$fontawesome_icons = get_fontawesome_icons();

	echo "<h2>" . __( "Font Awesome", "listings" ) . "</h2>";
	foreach ( $fontawesome_icons as $key => $match ) {
		echo "<i class='" . $key . " fa'></i>";
	}
	die;
}
add_action( "wp_ajax_generate_icons", "generate_icons" );
add_action( "wp_ajax_nopriv_generate_icons", "generate_icons" );

function customize_icon() { ?>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			$("#shortcode-modal").dialog("widget").animate({
				width: '500px',
				height: '450px'
			}, {
				duration: 500,
				step: function () {
					$("#shortcode-modal").dialog("option", "position", "center");
				},
				complete: function () {
					var new_height = $(".shortcode_generator").height();
					$("#shortcode-modal").height((new_height + 213));
				}
			});

			$('.color-picker').wpColorPicker({
				change: function (event, ui) {
					$("i.preview").css('color', ui.color.toString());
				}
			});

			$(".shortcode_slider").each(function (index, element) {
				var id = $(this).data('id');
				var minimum = $(this).data('min');
				var maximum = $(this).data('max');
				var units = $(this).data('unit');
				var value = $(this).data('value');

				$(this).slider({
					min: minimum,
					max: maximum,
					value: value,
					slide: function (event, ui) {
						$("#" + id).val(ui.value + units);
						$("i.preview").css("font-size", ui.value);
					}
				});
			});

			$(".insert_effect").click(function () {
				if ($(".insert_effect").is(":checked")) {
					$("i.preview").addClass('threed-icon');
				} else {
					$("i.preview").removeClass('threed-icon');
				}

			});

			$(".insert_spin").click(function () {
				if ($(".insert_spin").is(":checked")) {
					$("i.preview").addClass('fa-spin');
				} else {
					$("i.preview").removeClass('fa-spin');
				}

			});

			$(document).one("click", "#generateShortcode", function () {
				var size = $("i.preview").css('font-size');
				var color = $("i.preview").css('color');
				var clas = $("i.preview").attr('class').replace("preview", "");
				var icon = $("i.preview").data('icon');

				if ($("#insert_class").is(":checked")) {
					var icon_html = icon.replace("icon-", "");
				} else {
					var icon_html = "<i class='" + clas + "' style='color: " + color + "; font-size: " + size + ";'>&nbsp;</i>";
				}

				$("body").css({overflow: 'auto'});
				$("#shortcode-modal").dialog("close");

				//tinyMCE.execInstanceCommand('content', "mceInsertContent", false, icon_html);

				tinyMCE.execCommand('mceInsertContent', false, icon_html);
				return false;
			});
		});
	</script>
	<?php
	if ( strstr( $_POST['icon'], "fontello" ) ) {
		$default_fontello = get_option( 'default_fontello_font' );
		echo "<style type='text/css'>";
		echo "i.fontello { font-family: " . $default_fontello . "; }";
		echo "</style>";
	}

	echo "<i class='" . $_POST['icon'] . " preview' data-icon=\"" . $_POST['icon'] . "\"></i><br>";

	echo "<table border='0'>";

	echo "<tr><td>" . __( "Color", "listings" ) . ": </td><td><input type=\"text\" value=\"#000\" class=\"color-picker\" name=\"color\" /></td></tr>";

	echo "<tr><td>" . __( "Size", "listings" ) . ": </td><td><input type=\"text\" name=\"size\" value=\"18px\" id=\"icon-slider\" /></td></tr>";
	echo "<tr><td colspan='2'><div data-unit=\"px\" data-min=\"1\" data-max=\"100\" data-id=\"icon-slider\" data-value=\"18\" class=\"shortcode_slider\" style=\"width: 280px\"></div></td></tr>";

	echo "<tr><td colspan='2'><input type='checkbox' class='insert_effect' id='insert_effect'> <label for='insert_effect'>" . __( "Add 3-D effect", "listings" ) . "</label></td></tr>";
	echo "<tr><td colspan='2'><input type='checkbox' class='insert_spin' id='insert_spin'> <label for='insert_spin'>" . __( "Add spin effect", "listings" ) . "</label></td></tr>";

	echo "<tr><td colspan='2'><input type='checkbox' class='insert_class' id='insert_class'> <label for='insert_class'>" . __( "Insert icon as code for shortcode", "listings" ) . "</label></td></tr>";

	echo "</table>";

	echo "<button id=\"generateShortcode\" class=\"button btn\">" . __( "Generate Shortcode", "listings" ) . "</button>";

	die;
}
add_action( "wp_ajax_customize_icon", "customize_icon" );
add_action( "wp_ajax_nopriv_customize_icon", "customize_icon" );

//********************************************
//	Visual Composer Params
//***********************************************************
include( "vc.php" );

//********************************************
//	Framework
//***********************************************************
function automotive_add_shortcodes($existing_options){
    $existing_options[] = array(
        'title'      => __( "Automotive Plugin", "themesuite" ),
        'shortcodes' => array(

            array(
                'name'   => 'inventory_display',
                'title'  => __( "Inventory", "themesuite" ),
                'fields' => array(
                )
            )
        )
	);

    return $existing_options;
}
//add_filter('theme_shortcodes', 'automotive_add_shortcodes');