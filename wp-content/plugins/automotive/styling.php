<?php

//********************************************
//	Listing Styles
//***********************************************************
function listing_styles(){
	global $Listing, $post;

	wp_enqueue_style( 'chosen', CSS_DIR . "chosen.css");
	wp_enqueue_style( 'font_awesome', CSS_DIR . "font-awesome.css");

	if(isset($post->ID)) {
		$page_slideshow = get_post_meta( $post->ID, "page_slideshow", true );
	}
	$additional_gfonts  = array();

	// enqueue fonts if used in [auto_card] shortcode
	if(!empty($page_slideshow) && $page_slideshow != "none"){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// make sure rev slider is still active
		if(is_plugin_active( 'revslider/revslider.php' )){
			global $wpdb;

			// get slider id
			$slider_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM " . $wpdb->prefix . "revslider_sliders WHERE alias=%s", $page_slideshow
				)
			);

			// get slides
			if(!empty($slider_id)) {
				$slider_slides = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT layers FROM " . $wpdb->prefix . "revslider_slides WHERE slider_id=%d", $slider_id
					)
				);

				if(!empty($slider_slides)){
					$rev_slider_templates = get_option('automotive_rev_slider_templates');

					foreach($slider_slides as $slide){
						$slide_layers = json_decode($slide->layers);

						// find layer shortcode
						if(!empty($slide_layers)) {
							foreach ( $slide_layers as $slide_layer ) {
								preg_match( "/\[auto_card id\='(.*)' template\='(.*)'\]/", $slide_layer->text, $output_array );

								if ( isset( $output_array[2] ) && ! empty( $rev_slider_templates[ $output_array[2] ] ) ) {
									$template = $rev_slider_templates[ $output_array[2] ];

									// add google font to array
									if ( isset( $template['options']['rev_font_family'] ) && ! empty( $template['options']['rev_font_family'] ) ) {
										$additional_gfonts[] = urlencode( $template['options']['rev_font_family'] );
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	// Google Web Fonts
	wp_register_style( 'google-web-font-automotive', (is_ssl() ? "https" : "http") . "://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800,400italic" . (!empty($additional_gfonts) ? "|" . implode("|", $additional_gfonts) : ""));
	wp_enqueue_style( 'google-web-font-automotive' );	

	// Font Awesome
	wp_register_style( 'font-awesomemin', CSS_DIR . "font-awesome.min.css");
	wp_enqueue_style( 'font-awesomemin' );	
	
	// dequeue Visual Composers
	wp_dequeue_style( "font_awesome" );

	wp_register_style( 'jquery', CSS_DIR . "jquery-ui.css");
	wp_enqueue_style( 'jquery' );
	
	if(!is_category() && !is_tag() && !is_search()){
		$id = get_queried_object_id();
		$object = get_post( $id );
		
		if(isset($object->post_content)){
			if(has_shortcode($object->post_content, "testimonials") || has_shortcode($object->post_content, "featured_brands") || is_active_widget( false, false, "testimonial-slider-widget", true )){
				wp_register_style( 'testimonials', CSS_DIR . "jquery.bxslider.css");
				wp_enqueue_style( 'testimonials' );
			}
		}
	}
	
	// bootstrap 3
	wp_register_style( 'bootstrap', CSS_DIR . "bootstrap.min.css");
	wp_enqueue_style( 'bootstrap' );
	
	
	// Animate.css
	wp_register_style( 'css-animate', CSS_DIR . "animate.min.css");
	wp_enqueue_style( 'css-animate' );
	
	// Flexslider
	wp_register_style( 'flexslider', CSS_DIR . "flexslider.css");
	wp_enqueue_style( 'flexslider' );

	// SelectBox
	wp_register_style( 'jqueryselectbox', CSS_DIR . "jquery.selectbox.css");
	wp_enqueue_style( 'jqueryselectbox' );
	
	// Print Style
	if(is_singular('listings')){
		wp_enqueue_style( 'print-style',  CSS_DIR . "print.css", array(), false, "print" );
	}
	
	// Time Picker
	// wp_register_style( 'date-picker', CSS_DIR . "jquery.timepicker.css"); 
	// wp_enqueue_style( 'date-picker' );
	
	wp_enqueue_style( 'listing_style', CSS_DIR . "listing_style.css", ( wp_style_is("bootstrap") ? array("bootstrap", "jqueryselectbox") : array("jqueryselectbox")) );

	// custom badge colors
	$custom_css = "";

	if(!empty($Listing->lwp_options['custom_badges']) && $Listing->lwp_options['custom_badges']){
		$custom_badge_names  = array_values(array_filter($Listing->lwp_options['custom_badges']['name']));
		$custom_badge_colors = array_values(array_filter($Listing->lwp_options['custom_badges']['color']));
		$custom_badge_font   = array_values(array_filter($Listing->lwp_options['custom_badges']['font']));

		foreach($custom_badge_names as $badge_i => $badge_name){
			$badge_name = $Listing->numbers_to_text($badge_name);

			$custom_css .= ".angled_badge." . $Listing->slugify($badge_name) . ":before { border-color: rgba(0, 0, 0, 0) " . $custom_badge_colors[$badge_i] . " rgba(0, 0, 0, 0); }\n";
			$custom_css .= ".listing-slider .angled_badge." . $Listing->slugify($badge_name) . ":before { border-color: " . $custom_badge_colors[$badge_i] . " rgba(0, 0, 0, 0); }\n";
			$custom_css .= ".listing-slider .angled_badge." . $Listing->slugify($badge_name) . " span, .angled_badge." . $Listing->slugify($badge_name) . " span { color: " . $custom_badge_font[$badge_i] . "; }\n\n";
		}
	}

	wp_add_inline_style("listing_style", $custom_css);
	
	// Social likes
	wp_register_style( 'social-likes', CSS_DIR . "social-likes.css");
	// wp_enqueue_style('social-likes');

	// Mobile
	wp_register_style( 'listing_mobile', CSS_DIR . "mobile.css");
	wp_enqueue_style( 'listing_mobile' );

	// Form Styles
	wp_register_style( 'form-style', CSS_DIR . "form-style.css");
	//wp_enqueue_style( 'form-style' );

	// FancyBox
	wp_register_style( 'jqueryfancybox', CSS_DIR . "jquery.fancybox.css");
	wp_enqueue_style( 'jqueryfancybox' );

	// Shortcodes style
	wp_register_style( 'listing_shortcodes', CSS_DIR . 'shortcodes.css');
	wp_enqueue_style( 'listing_shortcodes' );

	// ThemeSuite
	wp_register_style( 'ts', CSS_DIR . "ts.css"); 
	wp_enqueue_style( 'ts' );
}
add_action('wp_enqueue_scripts', 'listing_styles');

//********************************************
//	Listing Scripts
//***********************************************************
function listing_scripts(){
	global $lwp_options, $post;
	
	wp_enqueue_script( 'jquery' );	
	wp_enqueue_script( 'jquery_ui', JS_DIR . 'jquery-ui-1.10.3.custom.min.js' );
	wp_enqueue_script( 'listing_js', JS_DIR . "listing.js", array(), false, true);
	
	$array = array( 
		'ajaxurl'       => admin_url( 'admin-ajax.php' ), 
		'current_url'   => get_permalink( get_queried_object_id() ),
		'permalink_set' => (get_option('permalink_structure') ? 'true' : 'false')
	);
	
	if(isset($lwp_options['comparison_page']) && !empty($lwp_options['comparison_page'])){
		$array['compare'] = get_permalink( $lwp_options['comparison_page'] );
	}
	
	
	$id = get_queried_object_id();
	$object = (is_page($id) ? get_page( $id ) : get_post( $id ));
	
	if( isset($object->post_content) && has_shortcode( $object->post_content, 'listings' ) ){
		$array['on_listing_page'] = "true";
	}	
	
	if(is_singular('listings')){
		global $slider_thumbnails;
		
		$array['listing_id'] = $post->ID;
		$array['slider_thumb_width'] = $slider_thumbnails['width'];
	}

	if(is_single() || is_page()){
		$array['post_id'] = $post->ID;
	}
	
	// recaptcha public key
	if($lwp_options['recaptcha_enabled'] == 1 && isset($lwp_options['recaptcha_public_key'])){
		$array['recaptcha_public'] = $lwp_options['recaptcha_public_key'];
		$array['template_url']     = get_template_directory_uri();
	}

	// vehicle terms
	$array['singular_vehicles']  = (isset($lwp_options['vehicle_singular_form']) && !empty($lwp_options['vehicle_singular_form']) ? $lwp_options['vehicle_singular_form'] : __('Vehicle', 'listings') );
	$array['plural_vehicles']    = (isset($lwp_options['vehicle_plural_form']) && !empty($lwp_options['vehicle_plural_form']) ? $lwp_options['vehicle_plural_form'] : __('Vehicles', 'listings') );
	$array['compare_vehicles']   = __("Compare", "listings");

	$array['currency_symbol']    = (isset($lwp_options['currency_symbol']) && !empty($lwp_options['currency_symbol']) ? $lwp_options['currency_symbol'] : "$");
	$array['currency_separator'] = (isset($lwp_options['currency_separator']) && !empty($lwp_options['currency_separator']) ? $lwp_options['currency_separator'] : ".");

	$array['google_maps_api']    = (isset($lwp_options['google_maps_api']) && !empty($lwp_options['google_maps_api']) ? $lwp_options['google_maps_api'] : "");

	$array['email_success']      = (isset($lwp_options['email_success']) && !empty($lwp_options['email_success']) ? $lwp_options['email_success'] : __("The email was sent.", "listings"));

	// SSL
	if(is_ssl()){
		$array['is_ssl'] = is_ssl();
	}
	
	wp_localize_script( 'listing_js', 'listing_ajax', $array); 
	wp_enqueue_script( 'chosen_js',  JS_DIR . "chosen.jquery.min.js", array(), false, true );

	// Cookie
	wp_enqueue_script( 'listing_cookie', JS_DIR . "jquery.cookie.js", array(), false, true);
	
	wp_register_script( 'google-maps', "https://maps.googleapis.com/maps/api/js?key" . (isset($lwp_options['google_maps_api']) && !empty($lwp_options['google_maps_api']) ? "=" . $lwp_options['google_maps_api'] : "") . "&sensor=false", array(), false, true);
	//wp_enqueue_script( 'google-maps' );

	// fancybox
	wp_register_script( 'jqueryfancybox', JS_DIR . "jquery.fancybox.js", array(), false, true);
	//wp_enqueue_script( 'jqueryfancybox' );
	 
	// Flex Slider
	wp_register_script( 'flex-slider', JS_DIR . "jquery.flexslider-min.js", array(), false, true);
	//wp_enqueue_script( 'flex-slider' );
	if(is_singular('listings') || is_singular('listings_portfolio')){
		wp_dequeue_script("jqueryflexslider");
		wp_dequeue_script("jquery.flexslider");

		wp_enqueue_script( 'flex-slider' );
		wp_enqueue_script( 'jqueryfancybox' );
	}
		
	// bootstrap tooltip
	wp_register_script( 'bootstrap', JS_DIR . "bootstrap.js", array(), false, true);
	wp_enqueue_script( 'bootstrap' );
	
	// select box
	wp_register_script( 'jqueryselectbox-02', JS_DIR . "jquery.selectbox-0.2.js", array(), false, true);
	wp_enqueue_script( 'jqueryselectbox-02' );
	
	// bxslider
	wp_register_script( 'bxslider', JS_DIR . "jquery.bxslider.min.js", array(), false, true);
	//wp_enqueue_script( 'bxslider' );
	
	/*if(!is_category() && !is_tag() && !is_search()){
		if(isset($object->post_content)){	
			if(has_shortcode($object->post_content, "testimonials") || has_shortcode($object->post_content, "featured_brands") || is_active_widget( false, false, "testimonial-slider-widget", true )){

			}
		}
	}*/

	wp_enqueue_script( 'bxslider' );
	
	// if mixitup
	wp_register_script( 'mixit', JS_DIR . "jquery.mixitup.min.js", array(), false, true);
	//wp_enqueue_script( 'mixit' );
	
	// Parallax
	wp_register_script( 'parallax', JS_DIR . "jquery.parallax.js", array(), false, true);
	//wp_enqueue_script( 'parallax' );

	// social-likes
	wp_register_script( 'social-likes', JS_DIR . "social-likes.min.js", array(), false, true);

//	if(is_single('listings')){
		wp_enqueue_script( 'social-likes' );
//	}

	// Inview
	wp_register_script( 'inview', JS_DIR . "jquery.inview.min.js", array(), false, true);
	wp_enqueue_script( 'inview' );

	// Twitter
    wp_register_script('twitter_tweet', JS_DIR . 'twitter/jquery.tweet.js', array('jquery'), '1.0.0');
    //wp_enqueue_script('twitter_tweet');
		
    wp_register_script('twitter_feed', JS_DIR . 'twitter/twitter_feed.js', array('jquery'), '1.0.0');
    //wp_enqueue_script('twitter_feed');

    wp_register_script('isotope', JS_DIR . 'jquery.isotope.js', array(), false, true);

    if(is_page_template("layouts/boxed-fullwidth.php") || is_page_template("layouts/boxed-sidebar-left.php") || is_page_template("layouts/boxed-sidebar-right.php") || 
       is_page_template("layouts/wide-fullwidth.php")  || is_page_template("layouts/wide-sidebar-left.php")  || is_page_template("layouts/wide-sidebar-right.php")){
		wp_enqueue_script( 'isotope' );
		wp_enqueue_script( 'inview' );
	}

	// Contact Form
	wp_register_script( 'contact_form', JS_DIR . "contact_form.js", array(), false, true);
	//wp_enqueue_script( 'contact_form' );
	
	// jsPDF
	wp_register_script( 'jspdf', JS_DIR . "jspdf.min.js", array(), false, true);

	if(is_singular('listings')){
		wp_enqueue_script( 'jspdf' );

		$path    = LISTING_HOME . '/js/pdf';
		$files = scandir($path);
		
		foreach($files as $file){
			if($file != "." && $file != ".." && $file != ".DS_Store"){
				wp_register_script( $file, JS_DIR . "pdf/" . $file, array(), false, true);
				wp_enqueue_script( $file );
			}
		}
	}

	// captcha
	if($lwp_options['recaptcha_enabled'] == 1 && isset($lwp_options['recaptcha_script_enabled']) && $lwp_options['recaptcha_script_enabled']){
		// AJAX Captcha
		//wp_register_script( 'recaptcha', "http://www.google.com/recaptcha/api/js/recaptcha_ajax.js", array(), false, true);
		wp_register_script( 'recaptcha', "https://www.google.com/recaptcha/api.js", array(), false, true);
		wp_enqueue_script( 'recaptcha' );
	}
}
add_action('wp_enqueue_scripts', 'listing_scripts');

//********************************************
//	Admin Listing Styles
//***********************************************************
function admin_listing_styles(){	
	wp_enqueue_style( 'listing_admin', LISTING_DIR . "css/admin.css");
	wp_enqueue_style( 'listing_jquery', LISTING_DIR . "css/jquery-ui.css");
	
	// Font Awesome
	wp_register_style( 'font-awesome', LISTING_DIR . "css/font-awesome.css");
	wp_enqueue_style( 'font-awesome' );
		
	wp_register_style( 'animate', LISTING_DIR . "css/animate.min.css");
	wp_enqueue_style( 'animate' );
	
	wp_register_style( 'multi-select', LISTING_DIR . "css/multi-select.css");
	wp_enqueue_style( 'multi-select' );
	
	wp_enqueue_style( 'wp-color-picker' );
}

//********************************************
//	Admin Listing Scripts
//***********************************************************
function admin_listing_scripts($hook_suffix){
	global $post, $lwp_options;
	
	if(is_admin()){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-progressbar' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'wp-color-picker' );
		
		wp_enqueue_media();

		$array = array( 
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'listing_dir'   => LISTING_DIR,
			'sold_listings' => (isset($_GET['sold_listings']) && !empty($_GET['sold_listings']) && in_array($_GET['sold_listings'], array(1,2)) ? $_GET['sold_listings'] : "false")
		);

		$queried_object = get_queried_object_id();
		if(isset($queried_object) && !empty($queried_object)) {
			$array['current_url'] = get_permalink( get_queried_object_id() );
		}
		
		if(isset($post->ID) && !empty($post->ID)){
			$array['post_id'] = $post->ID;
		}

		$allowed_pages = array(
			"index.php",
			"edit.php",
			"post.php",
			"edit-comments.php",
			"widgets.php",
			"upload.php",
			"themes.php",
			"plugins.php",
			"users.php",
			"tools.php",
			"options-general.php",
			"post-new.php",
			"admin.php",
			"toplevel_page_listing_wp",
			"toplevel_page_automotive_wp",
			"visual-composer_page_vc-welcome"
		);

		if(in_array($hook_suffix, $allowed_pages) || strpos($hook_suffix, 'listings_page') !== false){
			wp_enqueue_script( 'listing_admin', LISTING_DIR . "js/admin.js");
			wp_localize_script( 'listing_admin', 'myAjax', $array);

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script( 'admin_toggle', LISTING_DIR . "js/toggle.min.js");
			wp_enqueue_style( 'admin_toggle_style', LISTING_DIR . "css/toggle.css");
			
			wp_register_script( 'google-maps', "https://maps.googleapis.com/maps/api/js?key" . (isset($lwp_options['google_maps_api']) && !empty($lwp_options['google_maps_api']) ? "=" . $lwp_options['google_maps_api'] : ""));
			wp_enqueue_script( 'google-maps' );
			// wp_register_script( $handle, $src, $deps, $ver, $in_footer );
			wp_register_script( 'multiselect', LISTING_DIR . "js/jquery.multiselect.min.js", array("jquery-ui-widget"));
			wp_enqueue_script( 'multiselect' );
			
			wp_register_script( 'bootstrap-tooltip', LISTING_DIR . "js/bootstrap-tooltip.js");
			wp_enqueue_script( 'bootstrap-tooltip' );

            wp_register_script( 'chosen-dropdown', LISTING_DIR . "js/chosen.jquery.min.js");
            wp_enqueue_script( 'chosen-dropdown' );

            wp_register_script( 'alphanum', LISTING_DIR . "js/jquery.alphanum.js");
            wp_enqueue_script( 'alphanum' );

			wp_enqueue_script( 'listing_cookie', JS_DIR . "jquery.cookie.js", array(), false, true);
		}
	}
}


add_action( 'admin_enqueue_scripts', 'admin_listing_styles' );
add_action( 'admin_enqueue_scripts', 'admin_listing_scripts' );