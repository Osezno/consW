<?php

// simple installer
function automotive_demo_content_installer(){
    error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

    $nonce = (isset($_POST['nonce']) && !empty($_POST['nonce']) ? $_POST['nonce'] : "");

    if(empty($nonce) || !wp_verify_nonce($nonce, "automotive_installer")){

        echo "Nonce is invalid";

    } else {

	    // import step 1 || Automotive XML
	    if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) && $_POST['action'] == "automotive_demo_content_installer" && $_POST['step'] == 1 ) {
		    include_once( "importer/xml_importer.php" );

		    $args = array(
			    'file'        => LISTING_HOME . 'demo_content/1.xml',
			    'map_user_id' => 1
		    );


		    ob_start();
		    auto_import( $args );
		    $return = ob_get_clean();

		    echo( empty( $return ) ? "success" : "some_wrong<!--" . $return . "-->" );
	    }

	    // import step 2 || Automotive XML
	    if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) && $_POST['action'] == "automotive_demo_content_installer" && $_POST['step'] == 2 ) {
		    include_once( "importer/xml_importer.php" );

		    $args = array(
			    'file'        => LISTING_HOME . 'demo_content/2.xml',
			    'map_user_id' => 1
		    );


		    ob_start();
		    auto_import( $args );
		    $return = ob_get_clean();

		    echo( empty( $return ) ? "success" : "some_wrong<!--" . $return . "-->" );
	    }

	    // import step 3 || Automotive XML
	    if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) && $_POST['action'] == "automotive_demo_content_installer" && $_POST['step'] == 3 ) {
		    include_once( "importer/xml_importer.php" );

		    $args = array(
			    'file'        => LISTING_HOME . 'demo_content/3.xml',
			    'map_user_id' => 1
		    );


		    ob_start();
		    auto_import( $args );
		    $return = ob_get_clean();

		    echo( empty( $return ) ? "success" : "some_wrong<!--" . $return . "-->" );
	    }

	    // import step 4 || Automotive XML
	    if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) && $_POST['action'] == "automotive_demo_content_installer" && $_POST['step'] == 4 ) {
		    include_once( "importer/xml_importer.php" );

		    $args = array(
			    'file'        => LISTING_HOME . 'demo_content/4.xml',
			    'map_user_id' => 1
		    );


		    ob_start();
		    auto_import( $args );
		    $return = ob_get_clean();

		    echo( empty( $return ) ? "success" : "some_wrong<!--" . $return . "-->" );
	    }

	    // import step 5 || Automotive XML
	    if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) && $_POST['action'] == "automotive_demo_content_installer" && $_POST['step'] == 5 ) {
		    include_once( "importer/xml_importer.php" );

		    $args = array(
			    'file'        => LISTING_HOME . 'demo_content/5.xml',
			    'map_user_id' => 1
		    );


		    ob_start();
		    auto_import( $args );
		    $return = ob_get_clean();

		    echo( empty( $return ) ? "success" : "some_wrong<!--" . $return . "-->" );
	    }

	    // import step 6 || Listing Panel Options
	    if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) && $_POST['action'] == "automotive_demo_content_installer" && $_POST['step'] == 6 ) {
		    global $wpdb;

		    // import listing categories first
		    $demo_content = unserialize( 'a:18:{s:4:"year";a:7:{s:8:"singular";s:4:"Year";s:6:"plural";s:5:"Years";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:1:"=";s:5:"terms";a:6:{i:2014;s:4:"2014";i:2013;s:4:"2013";i:2012;s:4:"2012";i:2010;s:4:"2010";i:2009;s:4:"2009";i:2015;s:4:"2015";}s:4:"slug";s:4:"year";}s:4:"make";a:7:{s:8:"singular";s:4:"Make";s:6:"plural";s:5:"Makes";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:1:"=";s:5:"terms";a:1:{s:7:"porsche";s:7:"Porsche";}s:4:"slug";s:4:"make";}s:5:"model";a:7:{s:8:"singular";s:5:"Model";s:6:"plural";s:6:"Models";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:1:"=";s:5:"terms";a:5:{s:7:"carrera";s:7:"Carrera";s:3:"gts";s:3:"GTS";s:7:"cayenne";s:7:"Cayenne";s:7:"boxster";s:7:"Boxster";s:5:"macan";s:5:"Macan";}s:4:"slug";s:5:"model";}s:10:"body-style";a:7:{s:8:"singular";s:10:"Body Style";s:6:"plural";s:11:"Body Styles";s:10:"filterable";s:1:"1";s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:3:{s:11:"convertible";s:11:"Convertible";s:5:"sedan";s:5:"Sedan";s:22:"sports-utility-vehicle";s:22:"Sports Utility Vehicle";}s:4:"slug";s:10:"body-style";}s:7:"mileage";a:8:{s:8:"singular";s:7:"Mileage";s:6:"plural";s:8:"Mileages";s:10:"filterable";s:1:"1";s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:4:"&lt;";s:5:"terms";a:10:{i:10000;s:5:"10000";i:20000;s:5:"20000";i:30000;s:5:"30000";i:40000;s:5:"40000";i:50000;s:5:"50000";i:60000;s:5:"60000";i:70000;s:5:"70000";i:80000;s:5:"80000";i:90000;s:5:"90000";i:100000;s:6:"100000";}s:4:"slug";s:7:"mileage";s:9:"is_number";i:1;}s:12:"transmission";a:7:{s:8:"singular";s:12:"Transmission";s:6:"plural";s:13:"Transmissions";s:10:"filterable";s:1:"1";s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:8:{s:14:"6-speed-manual";s:14:"6-Speed Manual";s:17:"5-speed-automatic";s:17:"5-Speed Automatic";s:17:"8-speed-automatic";s:17:"8-Speed Automatic";s:17:"6-speed-semi-auto";s:17:"6-Speed Semi-Auto";s:17:"6-speed-automatic";s:17:"6-Speed Automatic";s:14:"5-speed-manual";s:14:"5-Speed Manual";s:17:"8-speed-tiptronic";s:17:"8-Speed Tiptronic";s:11:"7-speed-pdk";s:11:"7-Speed PDK";}s:4:"slug";s:12:"transmission";}s:12:"fuel-economy";a:8:{s:8:"singular";s:12:"Fuel Economy";s:6:"plural";s:14:"Fuel Economies";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:4:"&lt;";s:5:"terms";a:5:{i:10;s:2:"10";i:20;s:2:"20";i:30;s:2:"30";i:40;s:2:"40";i:50;s:2:"50";}s:4:"slug";s:12:"fuel-economy";s:9:"is_number";i:1;}s:9:"condition";a:7:{s:8:"singular";s:9:"Condition";s:6:"plural";s:10:"Conditions";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:1:"=";s:5:"terms";a:3:{s:9:"brand-new";s:9:"Brand New";s:13:"slightly-used";s:13:"Slightly Used";s:4:"used";s:4:"Used";}s:4:"slug";s:9:"condition";}s:8:"location";a:7:{s:8:"singular";s:8:"Location";s:6:"plural";s:9:"Locations";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:1:"=";s:5:"terms";a:1:{s:7:"toronto";s:7:"Toronto";}s:4:"slug";s:8:"location";}s:5:"price";a:10:{s:8:"singular";s:5:"Price";s:6:"plural";s:6:"Prices";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:4:"&lt;";s:8:"currency";s:1:"1";s:10:"link_value";s:5:"price";s:5:"terms";a:10:{i:10000;s:5:"10000";i:20000;s:5:"20000";i:30000;s:5:"30000";i:40000;s:5:"40000";i:50000;s:5:"50000";i:60000;s:5:"60000";i:70000;s:5:"70000";i:80000;s:5:"80000";i:90000;s:5:"90000";i:100000;s:6:"100000";}s:4:"slug";s:5:"price";s:9:"is_number";i:1;}s:10:"drivetrain";a:7:{s:8:"singular";s:10:"Drivetrain";s:6:"plural";s:11:"Drivetrains";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:4:{s:3:"awd";s:3:"AWD";s:3:"rwd";s:3:"RWD";s:3:"4wd";s:3:"4WD";s:14:"drivetrain-rwd";s:14:"Drivetrain RWD";}s:4:"slug";s:10:"drivetrain";}s:6:"engine";a:7:{s:8:"singular";s:6:"Engine";s:6:"plural";s:7:"Engines";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:9:{s:7:"3-6l-v6";s:7:"3.6L V6";s:17:"4-8l-v8-automatic";s:17:"4.8L V8 Automatic";s:13:"4-8l-v8-turbo";s:13:"4.8L V8 Turbo";s:7:"4-8l-v8";s:7:"4.8L V8";s:7:"3-8l-v6";s:7:"3.8L V6";s:18:"2-9l-mid-engine-v6";s:18:"2.9L Mid-Engine V6";s:18:"3-4l-mid-engine-v6";s:18:"3.4L Mid-Engine V6";s:14:"3-0l-v6-diesel";s:14:"3.0L V6 Diesel";s:13:"3-0l-v6-turbo";s:13:"3.0L V6 Turbo";}s:4:"slug";s:6:"engine";}s:14:"exterior-color";a:7:{s:8:"singular";s:14:"Exterior Color";s:6:"plural";s:15:"Exterior Colors";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:10:{s:13:"racing-yellow";s:13:"Racing Yellow";s:23:"rhodium-silver-metallic";s:23:"Rhodium Silver Metallic";s:16:"peridot-metallic";s:16:"Peridot Metallic";s:17:"ruby-red-metallic";s:17:"Ruby Red Metallic";s:5:"white";s:5:"White";s:18:"aqua-blue-metallic";s:18:"Aqua Blue Metallic";s:23:"chestnut-brown-metallic";s:23:"Chestnut Brown Metallic";s:10:"guards-red";s:10:"Guards Red";s:18:"dark-blue-metallic";s:18:"Dark Blue Metallic";s:18:"lime-gold-metallic";s:18:"Lime Gold Metallic";}s:4:"slug";s:14:"exterior-color";}s:14:"interior-color";a:7:{s:8:"singular";s:14:"Interior Color";s:6:"plural";s:15:"Interior Colors";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:8:{s:14:"interior-color";s:14:"Interior Color";s:10:"agate-grey";s:10:"Agate Grey";s:15:"alcantara-black";s:15:"Alcantara Black";s:11:"marsala-red";s:11:"Marsala Red";s:5:"black";s:5:"Black";s:13:"platinum-grey";s:13:"Platinum Grey";s:11:"luxor-beige";s:11:"Luxor Beige";s:19:"black-titanium-blue";s:21:"Black / Titanium Blue";}s:4:"slug";s:14:"interior-color";}s:3:"mpg";a:8:{s:8:"singular";s:3:"MPG";s:6:"plural";s:3:"MPG";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:10:"link_value";s:3:"mpg";s:5:"terms";a:7:{s:14:"19-city-27-hwy";s:16:"19 city / 27 hwy";s:14:"16-city-24-hwy";s:16:"16 city / 24 hwy";s:14:"15-city-21-hwy";s:16:"15 city / 21 hwy";s:14:"18-city-26-hwy";s:16:"18 city / 26 hwy";s:14:"20-city-30-hwy";s:16:"20 city / 30 hwy";s:14:"20-city-28-hwy";s:16:"20 City / 28 Hwy";s:14:"19-city-29-hwy";s:16:"19 city / 29 hwy";}s:4:"slug";s:3:"mpg";}s:12:"stock-number";a:7:{s:8:"singular";s:12:"Stock Number";s:6:"plural";s:13:"Stock Numbers";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:12:{i:590388;s:6:"590388";i:590524;s:6:"590524";i:590512;s:6:"590512";i:590499;s:6:"590499";i:590435;s:6:"590435";i:590421;s:6:"590421";i:590476;s:6:"590476";i:590271;s:6:"590271";i:590497;s:6:"590497";i:16115;s:5:"16115";i:590124;s:6:"590124";i:590562;s:6:"590562";}s:4:"slug";s:12:"stock-number";}s:10:"vin-number";a:7:{s:8:"singular";s:10:"VIN Number";s:6:"plural";s:11:"VIN Numbers";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:11:{s:17:"wp0cb2a92cs376450";s:17:"WP0CB2A92CS376450";s:17:"wp0ab2a74al092462";s:17:"WP0AB2A74AL092462";s:17:"wp1ad29p09la73659";s:17:"WP1AD29P09LA73659";s:17:"wp0ab2a74al079264";s:17:"WP0AB2A74AL079264";s:17:"wp0cb2a92cs754706";s:17:"WP0CB2A92CS754706";s:17:"wp0ca2a96as740274";s:17:"WP0CA2A96AS740274";s:17:"wp0ab2a74al060306";s:17:"WP0AB2A74AL060306";s:17:"wp1ad29p09la65818";s:17:"WP1AD29P09LA65818";s:17:"wp0ab2e81ek190171";s:17:"WP0AB2E81EK190171";s:17:"wp0cb2a92cs377324";s:17:"WP0CB2A92CS377324";s:17:"wp0ct2a92cs326491";s:17:"WP0CT2A92CS326491";}s:4:"slug";s:10:"vin-number";}s:7:"options";a:1:{s:5:"terms";a:40:{s:23:"adaptive-cruise-control";s:23:"Adaptive Cruise Control";s:7:"airbags";s:7:"Airbags";s:16:"air-conditioning";s:16:"Air Conditioning";s:12:"alarm-system";s:12:"Alarm System";s:21:"anti-theft-protection";s:21:"Anti-theft Protection";s:15:"audio-interface";s:15:"Audio Interface";s:25:"automatic-climate-control";s:25:"Automatic Climate Control";s:20:"automatic-headlights";s:20:"Automatic Headlights";s:15:"auto-start-stop";s:15:"Auto Start/Stop";s:19:"bi-xenon-headlights";s:19:"Bi-Xenon Headlights";s:18:"bluetoothr-handset";s:19:"Bluetooth® Handset";s:20:"boser-surround-sound";s:21:"BOSE® Surround Sound";s:25:"burmesterr-surround-sound";s:26:"Burmester® Surround Sound";s:18:"cd-dvd-autochanger";s:18:"CD/DVD Autochanger";s:9:"cdr-audio";s:9:"CDR Audio";s:14:"cruise-control";s:14:"Cruise Control";s:21:"direct-fuel-injection";s:21:"Direct Fuel Injection";s:22:"electric-parking-brake";s:22:"Electric Parking Brake";s:10:"floor-mats";s:10:"Floor Mats";s:18:"garage-door-opener";s:18:"Garage Door Opener";s:15:"leather-package";s:15:"Leather Package";s:25:"locking-rear-differential";s:25:"Locking Rear Differential";s:20:"luggage-compartments";s:20:"Luggage Compartments";s:19:"manual-transmission";s:19:"Manual Transmission";s:17:"navigation-module";s:17:"Navigation Module";s:15:"online-services";s:15:"Online Services";s:10:"parkassist";s:10:"ParkAssist";s:21:"porsche-communication";s:21:"Porsche Communication";s:14:"power-steering";s:14:"Power Steering";s:16:"reversing-camera";s:16:"Reversing Camera";s:20:"roll-over-protection";s:20:"Roll-over Protection";s:12:"seat-heating";s:12:"Seat Heating";s:16:"seat-ventilation";s:16:"Seat Ventilation";s:18:"sound-package-plus";s:18:"Sound Package Plus";s:20:"sport-chrono-package";s:20:"Sport Chrono Package";s:22:"steering-wheel-heating";s:22:"Steering Wheel Heating";s:24:"tire-pressure-monitoring";s:24:"Tire Pressure Monitoring";s:25:"universal-audio-interface";s:25:"Universal Audio Interface";s:20:"voice-control-system";s:20:"Voice Control System";s:14:"wind-deflector";s:14:"Wind Deflector";}}}' );
		    $update       = update_option( "listing_categories", $demo_content );

		    $theme_panel_options = 'a:99:{s:8:"last_tab";s:0:"";s:21:"vehicle_history_label";s:7:"Carfax2";s:15:"vehicle_history";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:21:"fuel_efficiency_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:23:"inventory_primary_title";s:17:"Inventory Listing";s:25:"inventory_secondary_title";s:46:"Powerful Inventory Marketing, Fully Integrated";s:14:"inventory_page";s:0:"";s:17:"inventory_no_sold";s:0:"";s:15:"comparison_page";s:0:"";s:15:"listings_amount";s:1:"0";s:10:"sale_value";s:0:"";s:13:"tax_label_box";s:14:"Plus Sales Tax";s:14:"tax_label_page";s:22:"Plus Taxes & Licensing";s:14:"car_comparison";s:1:"1";s:24:"inventory_listing_toggle";s:1:"1";s:19:"thumbnail_slideshow";s:1:"1";s:21:"additional_categories";a:3:{i:0;s:9:"Certified";i:1;s:15:"CARFAX Verified";i:2;s:9:"Brand New";}s:15:"currency_symbol";s:0:"";s:18:"currency_placement";s:1:"1";s:18:"currency_separator";s:0:"";s:13:"email_success";s:19:"The email was sent.";s:13:"email_failure";s:23:"The email was not sent.";s:10:"email_spam";s:52:"The email you are trying to send is considered spam.";s:14:"friend_subject";s:42:"{name} wants you to check this vehicle out";s:13:"friend_layout";s:60:"I want you check this vehicle out {table} Message: {message}";s:8:"drive_to";s:0:"";s:13:"drive_subject";s:28:"Scheduled Test Drive Request";s:12:"drive_layout";s:45:"Information
    
    {table}
    
    Vehicle: {link}";s:7:"info_to";s:0:"";s:12:"info_subject";s:19:"Information Request";s:11:"info_layout";s:53:"Request Information
    
    {table}
    
    Vehicle: {link}";s:8:"trade_to";s:0:"";s:13:"trade_subject";s:18:"Trade-In Appraisal";s:12:"trade_layout";s:28:"{table}
    
    Vehicle: {link}";s:8:"offer_to";s:0:"";s:13:"offer_subject";s:5:"Offer";s:12:"offer_layout";s:28:"{table}
    
    Vehicle: {link}";s:27:"request_info_form_shortcode";s:0:"";s:34:"schedule_test_drive_form_shortcode";s:0:"";s:25:"make_offer_form_shortcode";s:0:"";s:22:"tradein_form_shortcode";s:0:"";s:27:"email_friend_form_shortcode";s:0:"";s:17:"recaptcha_enabled";s:1:"1";s:20:"recaptcha_public_key";s:0:"";s:21:"recaptcha_private_key";s:0:"";s:20:"fuel_efficiency_show";s:1:"1";s:20:"fuel_efficiency_text";s:95:"Actual rating will vary with options, driving conditions, driving habits and vehicle condition.";s:17:"social_icons_show";s:1:"1";s:21:"display_vehicle_video";s:1:"1";s:15:"calculator_show";s:1:"1";s:23:"calculator_down_payment";s:4:"1000";s:15:"calculator_rate";s:1:"7";s:15:"calculator_loan";s:1:"5";s:20:"recent_vehicles_show";s:1:"1";s:21:"recent_vehicles_title";s:15:"Recent Vehicles";s:20:"recent_vehicles_desc";s:93:"Browse through the vast selection of vehicles that have recently been added to our inventory.";s:21:"recent_vehicles_limit";s:2:"10";s:21:"previous_vehicle_show";s:1:"1";s:22:"previous_vehicle_label";s:12:"Prev Vehicle";s:17:"request_more_show";s:1:"1";s:18:"request_more_label";s:17:"Request More Info";s:18:"schedule_test_show";s:1:"1";s:19:"schedule_test_label";s:19:"Schedule Test Drive";s:15:"make_offer_show";s:1:"1";s:16:"make_offer_label";s:13:"Make an Offer";s:12:"tradein_show";s:1:"1";s:13:"tradein_label";s:18:"Trade-In Appraisal";s:17:"pdf_brochure_show";s:1:"1";s:18:"pdf_brochure_label";s:12:"PDF Brochure";s:18:"print_vehicle_show";s:1:"1";s:19:"print_vehicle_label";s:18:"Print this Vehicle";s:17:"email_friend_show";s:1:"1";s:18:"email_friend_label";s:17:"Email to a Friend";s:17:"next_vehicle_show";s:1:"1";s:18:"next_vehicle_label";s:12:"Next Vehicle";s:9:"first_tab";s:16:"Vehicle Overview";s:10:"second_tab";s:18:"Features & Options";s:9:"third_tab";s:24:"Technical Specifications";s:10:"fourth_tab";s:16:"Vehicle Location";s:9:"fifth_tab";s:14:"Other Comments";s:16:"listing_comments";s:0:"";s:21:"job_description_title";s:15:"Job Description";s:21:"project_details_title";s:16:"Projects Details";s:22:"related_projects_title";s:16:"Related Projects";s:17:"default_value_lat";s:9:"43.653226";s:18:"default_value_long";s:11:"-79.3831843";s:18:"default_value_zoom";s:2:"10";s:19:"default_value_price";s:5:"Price";s:18:"default_value_city";s:4:"City";s:17:"default_value_hwy";s:7:"Highway";s:15:"edmunds_api_key";s:0:"";s:18:"edmunds_api_secret";s:0:"";s:17:"mailchimp_api_key";s:0:"";s:14:"twitter_switch";s:0:"";s:12:"consumer_key";s:0:"";s:19:"secret_consumer_key";s:0:"";s:12:"access_token";s:0:"";s:19:"secret_access_token";s:0:"";s:30:"import-demo-listing-categories";i:1;}';

		    $query = $wpdb->query( "UPDATE " . $wpdb->prefix . "options SET option_value = '" . $theme_panel_options . "' WHERE option_name = 'listing_wp';" );

		    echo( $query === false ? "some_wrong" : "success" );
	    }

	    // import step 7 || Theme Panel Options
	    if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) && $_POST['action'] == "automotive_demo_content_installer" && $_POST['step'] == 7 ) {
		    global $wpdb;

		    $listing_panel_options = 'a:134:{s:8:"last_tab";s:0:"";s:7:"favicon";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:11:"body_layout";s:1:"1";s:15:"default_sidebar";s:4:"none";s:16:"boxed_background";a:7:{s:16:"background-color";s:0:"";s:17:"background-repeat";s:0:"";s:15:"background-size";s:0:"";s:21:"background-attachment";s:0:"";s:19:"background-position";s:0:"";s:16:"background-image";s:0:"";s:5:"media";a:4:{s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}}s:20:"social_share_buttons";s:1:"1";s:19:"featured_image_blog";s:1:"0";s:13:"images_border";s:1:"1";s:16:"google_analytics";s:0:"";s:22:"tracking_code_position";s:0:"";s:15:"custom_sidebars";a:1:{i:0;s:9:"Sidebar 1";}s:11:"theme_check";s:1:"1";s:14:"responsiveness";s:1:"1";s:6:"retina";s:1:"1";s:9:"logo_text";s:10:"Automotive";s:19:"logo_text_secondary";s:8:"Template";s:10:"logo_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:18:"logo_customization";s:0:"";s:15:"logo_dimensions";a:3:{s:5:"width";s:4:"65px";s:6:"height";s:4:"65px";s:5:"units";s:2:"px";}s:11:"logo_margin";a:5:{s:10:"margin-top";s:1:"0";s:12:"margin-right";s:1:"0";s:13:"margin-bottom";s:1:"0";s:11:"margin-left";s:1:"0";s:5:"units";s:2:"px";}s:9:"logo_link";s:1:"1";s:20:"default_header_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:22:"no_header_area_default";s:0:"";s:14:"toolbar_shadow";s:1:"1";s:13:"header_shadow";s:1:"1";s:18:"toolbar_login_show";s:1:"1";s:13:"toolbar_login";s:5:"Login";s:18:"toolbar_login_link";s:0:"";s:21:"toolbar_language_show";s:1:"1";s:17:"toolbar_languages";s:9:"Languages";s:19:"toolbar_search_show";s:1:"1";s:14:"toolbar_search";s:6:"Search";s:18:"toolbar_phone_show";s:1:"1";s:13:"toolbar_phone";s:14:"1-800-567-0123";s:20:"toolbar_address_show";s:1:"1";s:15:"toolbar_address";s:41:"107 SUNSET BLVD., BEVERLY HILLS, CA 90210";s:20:"toolbar_address_link";s:3:"156";s:10:"header_top";s:1:"1";s:13:"header_resize";s:1:"1";s:20:"header_resize_mobile";s:1:"1";s:26:"push_mobile_slideshow_down";s:1:"1";s:28:"mobile_slideshow_down_amount";s:2:"98";s:24:"breadcrumb_functionality";s:1:"1";s:16:"breadcrumb_style";s:1:"1";s:16:"woocommerce_cart";s:1:"1";s:21:"woocommerce_cart_link";s:0:"";s:18:"languages_dropdown";s:1:"0";s:17:"footer_logo_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:11:"footer_text";s:46:"Powered by {wp-link}. Built with {theme-link}.";s:12:"footer_icons";s:1:"1";s:11:"footer_menu";s:1:"1";s:14:"footer_widgets";s:1:"1";s:11:"footer_logo";s:1:"1";s:16:"footer_copyright";s:1:"1";s:20:"social_network_links";a:2:{s:7:"enabled";a:11:{s:7:"placebo";s:7:"placebo";s:8:"facebook";s:8:"Facebook";s:7:"twitter";s:7:"Twitter";s:7:"youtube";s:7:"Youtube";s:5:"vimeo";s:5:"Vimeo";s:8:"linkedin";s:8:"Linkedin";s:3:"rss";s:3:"RSS";s:6:"flickr";s:6:"Flickr";s:5:"skype";s:5:"Skype";s:6:"google";s:6:"Google";s:9:"pinterest";s:9:"Pinterest";}s:8:"disabled";a:3:{s:7:"placebo";s:7:"placebo";s:9:"instagram";s:9:"Instagram";s:4:"yelp";s:4:"Yelp";}}s:12:"facebook_url";s:1:"#";s:11:"twitter_url";s:1:"#";s:11:"youtube_url";s:1:"#";s:9:"vimeo_url";s:1:"#";s:12:"linkedin_url";s:1:"#";s:7:"rss_url";s:1:"#";s:10:"flickr_url";s:1:"#";s:9:"skype_url";s:1:"#";s:10:"google_url";s:1:"#";s:13:"pinterest_url";s:1:"#";s:13:"instagram_url";s:0:"";s:8:"yelp_url";s:0:"";s:13:"contact_email";s:0:"";s:13:"primary_color";s:7:"#c7081b";s:14:"css_link_color";a:3:{s:7:"regular";s:7:"#c7081b";s:5:"hover";s:7:"#c7081b";s:6:"active";s:7:"#c7081b";}s:21:"css_footer_link_color";a:3:{s:7:"regular";s:7:"#BEBEBE";s:5:"hover";s:4:"#999";s:6:"active";s:4:"#999";}s:19:"redux-scheme-select";s:7:"Default";s:18:"theme_color_scheme";a:23:{s:17:"color_scheme_name";s:7:"Default";s:11:"site-header";a:9:{s:2:"id";s:11:"site-header";s:5:"title";s:17:"Header Background";s:5:"color";s:7:"#000000";s:5:"alpha";d:0.65000000000000002220446049250313080847263336181640625;s:8:"selector";s:146:"header, .dropdown .dropdown-menu li.dropdown .dropdown-menu, header .navbar-nav.pull-right>li>.dropdown-menu, header .navbar-nav>li>.dropdown-menu";s:4:"mode";s:16:"background-color";s:9:"important";b:0;s:5:"group";s:6:"Header";s:4:"rgba";s:16:"rgba(0,0,0,0.65)";}s:13:"toolbar-color";a:9:{s:2:"id";s:13:"toolbar-color";s:5:"title";s:12:"Toolbar Text";s:5:"color";s:7:"#929596";s:5:"alpha";i:1;s:8:"selector";s:80:".toolbar ul li a, .toolbar .search_box, header .toolbar button, .toolbar ul li i";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:6:"Header";s:4:"rgba";s:19:"rgba(146,149,150,1)";}s:18:"toolbar-background";a:9:{s:2:"id";s:18:"toolbar-background";s:5:"title";s:18:"Toolbar Background";s:5:"color";s:7:"#000000";s:5:"alpha";d:0.200000000000000011102230246251565404236316680908203125;s:8:"selector";s:8:".toolbar";s:4:"mode";s:16:"background-color";s:9:"important";b:0;s:5:"group";s:6:"Header";s:4:"rgba";s:15:"rgba(0,0,0,0.2)";}s:17:"header-menu-color";a:9:{s:2:"id";s:17:"header-menu-color";s:5:"title";s:22:"Header menu text color";s:5:"color";s:7:"#FFFFFF";s:5:"alpha";i:1;s:8:"selector";s:270:".bottom-header .navbar-default .navbar-nav>.active>a, header .bottom-header .navbar-default .navbar-nav>li>a, .navbar .navbar-nav li .dropdown-menu>li>a, .dropdown .dropdown-menu li.dropdown .dropdown-menu>li>a, body .navbar-default .navbar-nav .open .dropdown-menu>li>a";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:6:"Header";s:4:"rgba";s:19:"rgba(255,255,255,1)";}s:18:"header-menu-active";a:9:{s:2:"id";s:18:"header-menu-active";s:5:"title";s:23:"Header menu active item";s:5:"color";s:7:"#c7081b";s:5:"alpha";i:1;s:8:"selector";s:85:"header .bottom-header .navbar-default .navbar-nav>.active>a, .dropdown-menu>.active>a";s:4:"mode";s:27:"background,background-color";s:9:"important";b:0;s:5:"group";s:6:"Header";s:4:"rgba";s:16:"rgba(199,8,27,1)";}s:17:"header-menu-hover";a:9:{s:2:"id";s:17:"header-menu-hover";s:5:"title";s:22:"Header menu hover item";s:5:"color";s:7:"#c7081b";s:5:"alpha";i:1;s:8:"selector";s:176:".bottom-header .navbar-default .navbar-nav> li:hover> a, .bottom-header .navbar-default .navbar-nav>.active>a:hover, .dropdown-menu>li>a:hover, .dropdown-menu>li.active>a:hover";s:4:"mode";s:27:"background,background-color";s:9:"important";b:0;s:5:"group";s:6:"Header";s:4:"rgba";s:16:"rgba(199,8,27,1)";}s:19:"toolbar-color-hover";a:9:{s:2:"id";s:19:"toolbar-color-hover";s:5:"title";s:18:"Toolbar Hover Text";s:5:"color";s:4:"#FFF";s:5:"alpha";i:1;s:8:"selector";s:125:".left-none li:hover a, .right-none li:hover a, .left-none li:hover input, .left-none li:hover i.fa, .right-none li:hover i.fa";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:6:"Header";s:4:"rgba";s:19:"rgba(255,255,255,1)";}s:23:"header-menu-hover-color";a:9:{s:2:"id";s:23:"header-menu-hover-color";s:5:"title";s:28:"Header menu hover text color";s:5:"color";s:7:"#FFFFFF";s:5:"alpha";i:1;s:8:"selector";s:240:"header .bottom-header .navbar-default .navbar-nav>li:hover>a, .navbar .navbar-nav li .dropdown-menu>li:hover>a, .dropdown .dropdown-menu li.dropdown .dropdown-menu>li:hover>a, body .navbar-default .navbar-nav .open .dropdown-menu>li:hover>a";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:6:"Header";s:4:"rgba";s:19:"rgba(255,255,255,1)";}s:30:"header-menu-active-hover-color";a:9:{s:2:"id";s:30:"header-menu-active-hover-color";s:5:"title";s:35:"Header menu active hover text color";s:5:"color";s:7:"#FFFFFF";s:5:"alpha";i:1;s:8:"selector";s:65:"header .bottom-header .navbar-default .navbar-nav .active:hover>a";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:6:"Header";s:4:"rgba";s:19:"rgba(255,255,255,1)";}s:20:"secondary-background";a:9:{s:2:"id";s:20:"secondary-background";s:5:"title";s:20:"Secondary Background";s:5:"color";s:7:"#000000";s:5:"alpha";i:1;s:8:"selector";s:17:"#secondary-banner";s:4:"mode";s:16:"background-color";s:9:"important";b:0;s:5:"group";s:9:"Secondary";s:4:"rgba";s:13:"rgba(0,0,0,1)";}s:14:"secondary-text";a:9:{s:2:"id";s:14:"secondary-text";s:5:"title";s:14:"Secondary Text";s:5:"color";s:7:"#FFFFFF";s:5:"alpha";i:1;s:8:"selector";s:61:"#secondary-banner, #secondary-banner h1, #secondary-banner h4";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:9:"Secondary";s:4:"rgba";s:19:"rgba(255,255,255,1)";}s:10:"breadcrumb";a:9:{s:2:"id";s:10:"breadcrumb";s:5:"title";s:15:"Breadcrumb Text";s:5:"color";s:7:"#FFFFFF";s:5:"alpha";i:1;s:8:"selector";s:58:".breadcrumb li, .breadcrumb li a, .breadcrumb>li+li:before";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:9:"Secondary";s:4:"rgba";s:19:"rgba(255,255,255,1)";}s:21:"secondary-text-shadow";a:9:{s:2:"id";s:21:"secondary-text-shadow";s:5:"title";s:21:"Secondary Text Shadow";s:5:"color";s:4:"#000";s:5:"alpha";i:1;s:8:"selector";s:17:"#secondary-banner";s:4:"mode";s:11:"text-shadow";s:9:"important";b:0;s:5:"group";s:9:"Secondary";s:4:"rgba";s:13:"rgba(0,0,0,1)";}s:15:"body-background";a:9:{s:2:"id";s:15:"body-background";s:5:"title";s:15:"Body Background";s:5:"color";s:7:"#FFFFFF";s:5:"alpha";i:1;s:8:"selector";s:47:"section.content, .car-block-wrap, .welcome-wrap";s:4:"mode";s:16:"background-color";s:9:"important";b:0;s:5:"group";s:4:"Body";s:4:"rgba";s:19:"rgba(255,255,255,1)";}s:21:"body-background-input";a:9:{s:2:"id";s:21:"body-background-input";s:5:"title";s:21:"Body Background Input";s:5:"color";s:7:"#FFFFFF";s:5:"alpha";i:1;s:8:"selector";s:216:"body input, body select, body textarea, body input[type=text], body textarea[name=message], body input[type=email], input.form-control, input[type=search], .side-content .financing_calculator table tr td input.number";s:4:"mode";s:16:"background-color";s:9:"important";b:0;s:5:"group";s:4:"Body";s:4:"rgba";s:19:"rgba(255,255,255,1)";}s:16:"body-color-input";a:9:{s:2:"id";s:16:"body-color-input";s:5:"title";s:15:"Body Input Text";s:5:"color";s:7:"#2D2D2D";s:5:"alpha";i:1;s:8:"selector";s:184:"body input, body select, body textarea, input.form-control, select.form-control, textarea.form-control, input[type=search], .side-content .financing_calculator table tr td input.number";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:4:"Body";s:4:"rgba";s:16:"rgba(45,45,45,1)";}s:26:"inventory-background-input";a:9:{s:2:"id";s:26:"inventory-background-input";s:5:"title";s:29:"Inventory Dropdown Background";s:5:"color";s:7:"#F7F7F7";s:5:"alpha";i:1;s:8:"selector";s:42:".sbHolder, .sbOptions, .sbOptions li:hover";s:4:"mode";s:16:"background-color";s:9:"important";b:0;s:5:"group";s:4:"Body";s:4:"rgba";s:19:"rgba(247,247,247,1)";}s:21:"inventory-color-input";a:9:{s:2:"id";s:21:"inventory-color-input";s:5:"title";s:23:"Inventory Dropdown Text";s:5:"color";s:4:"#333";s:5:"alpha";i:1;s:8:"selector";s:123:".sbHolder, .sbOptions, a.sbSelector:link, a.sbSelector:visited, a.sbSelector:hover, .sbOptions a:link, .sbOptions a:visited";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:4:"Body";s:4:"rgba";s:16:"rgba(51,51,51,1)";}s:17:"footer-background";a:9:{s:2:"id";s:17:"footer-background";s:5:"title";s:17:"Footer Background";s:5:"color";s:7:"#3D3D3D";s:5:"alpha";i:1;s:8:"selector";s:6:"footer";s:4:"mode";s:16:"background-color";s:9:"important";b:0;s:5:"group";s:6:"Footer";s:4:"rgba";s:16:"rgba(61,61,61,1)";}s:11:"footer-text";a:9:{s:2:"id";s:11:"footer-text";s:5:"title";s:11:"Footer Text";s:5:"color";s:7:"#FFFFFF";s:5:"alpha";i:1;s:8:"selector";s:71:"footer, footer p, footer .textwidget, footer p, footer li, footer table";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:6:"Footer";s:4:"rgba";s:19:"rgba(255,255,255,1)";}s:24:"bottom-footer-background";a:9:{s:2:"id";s:24:"bottom-footer-background";s:5:"title";s:24:"Bottom Footer Background";s:5:"color";s:7:"#2F2F2F";s:5:"alpha";i:1;s:8:"selector";s:15:".copyright-wrap";s:4:"mode";s:16:"background-color";s:9:"important";b:0;s:5:"group";s:13:"Bottom Footer";s:4:"rgba";s:16:"rgba(47,47,47,1)";}s:18:"bottom-footer-text";a:9:{s:2:"id";s:18:"bottom-footer-text";s:5:"title";s:18:"Bottom Footer Text";s:5:"color";s:7:"#FFFFFF";s:5:"alpha";i:1;s:8:"selector";s:34:".copyright-wrap, .copyright-wrap p";s:4:"mode";s:5:"color";s:9:"important";b:0;s:5:"group";s:13:"Bottom Footer";s:4:"rgba";s:19:"rgba(255,255,255,1)";}}s:9:"body_font";a:9:{s:11:"font-family";s:9:"Open Sans";s:12:"font-options";s:0:"";s:6:"google";s:1:"1";s:11:"font-weight";s:3:"400";s:10:"font-style";s:0:"";s:7:"subsets";s:0:"";s:9:"font-size";s:4:"14px";s:11:"line-height";s:4:"24px";s:5:"color";s:7:"#2D2D2D";}s:13:"logo_top_font";a:10:{s:11:"font-family";s:10:"Yellowtail";s:12:"font-options";s:0:"";s:6:"google";s:1:"1";s:11:"font-weight";s:3:"400";s:10:"font-style";s:0:"";s:7:"subsets";s:5:"latin";s:10:"text-align";s:0:"";s:9:"font-size";s:4:"40px";s:11:"line-height";s:4:"20px";s:5:"color";s:4:"#FFF";}s:16:"logo_bottom_font";a:10:{s:11:"font-family";s:9:"Open Sans";s:12:"font-options";s:0:"";s:6:"google";s:1:"1";s:11:"font-weight";s:3:"400";s:10:"font-style";s:0:"";s:7:"subsets";s:0:"";s:10:"text-align";s:0:"";s:9:"font-size";s:4:"12px";s:11:"line-height";s:4:"20px";s:5:"color";s:4:"#FFF";}s:14:"main_menu_font";a:7:{s:11:"font-family";s:9:"Open Sans";s:12:"font-options";s:0:"";s:6:"google";s:1:"1";s:11:"font-weight";s:3:"700";s:10:"font-style";s:0:"";s:7:"subsets";s:0:"";s:9:"font-size";s:4:"14px";}s:18:"main_dropdown_font";a:7:{s:11:"font-family";s:9:"Open Sans";s:12:"font-options";s:0:"";s:6:"google";s:1:"1";s:11:"font-weight";s:3:"600";s:10:"font-style";s:0:"";s:7:"subsets";s:0:"";s:9:"font-size";s:4:"13px";}s:20:"main_menu_breakpoint";s:3:"767";s:10:"custom_css";s:0:"";s:9:"custom_js";s:0:"";s:7:"h1_font";a:9:{s:11:"font-family";s:9:"Open Sans";s:12:"font-options";s:0:"";s:6:"google";s:1:"1";s:11:"font-weight";s:3:"400";s:10:"font-style";s:0:"";s:7:"subsets";s:0:"";s:9:"font-size";s:4:"72px";s:11:"line-height";s:4:"80px";s:5:"color";s:7:"#2D2D2D";}s:7:"h2_font";a:9:{s:11:"font-family";s:9:"Open Sans";s:12:"font-options";s:0:"";s:6:"google";s:1:"1";s:11:"font-weight";s:3:"600";s:10:"font-style";s:0:"";s:7:"subsets";s:0:"";s:9:"font-size";s:4:"32px";s:11:"line-height";s:4:"32px";s:5:"color";s:7:"#2D2D2D";}s:7:"h3_font";a:9:{s:11:"font-family";s:9:"Open Sans";s:12:"font-options";s:0:"";s:6:"google";s:4:"true";s:11:"font-weight";s:3:"800";s:10:"font-style";s:0:"";s:7:"subsets";s:0:"";s:9:"font-size";s:4:"22px";s:11:"line-height";s:4:"22px";s:5:"color";s:7:"#c7081b";}s:7:"h4_font";a:9:{s:11:"font-family";s:9:"Open Sans";s:12:"font-options";s:0:"";s:6:"google";s:1:"1";s:11:"font-weight";s:3:"400";s:10:"font-style";s:0:"";s:7:"subsets";s:0:"";s:9:"font-size";s:4:"24px";s:11:"line-height";s:4:"26px";s:5:"color";s:7:"#C7081B";}s:7:"h5_font";a:9:{s:11:"font-family";s:9:"Open Sans";s:12:"font-options";s:0:"";s:6:"google";s:1:"1";s:11:"font-weight";s:3:"400";s:10:"font-style";s:0:"";s:7:"subsets";s:0:"";s:9:"font-size";s:4:"20px";s:11:"line-height";s:4:"22px";s:5:"color";s:7:"#2D2D2D";}s:7:"h6_font";a:9:{s:11:"font-family";s:9:"Open Sans";s:12:"font-options";s:0:"";s:6:"google";s:1:"1";s:11:"font-weight";s:3:"400";s:10:"font-style";s:0:"";s:7:"subsets";s:0:"";s:9:"font-size";s:4:"16px";s:11:"line-height";s:4:"17px";s:5:"color";s:7:"#2D2D2D";}s:18:"blog_primary_title";s:0:"";s:20:"blog_secondary_title";s:0:"";s:17:"blog_post_details";s:1:"1";s:15:"listing_display";s:0:"";s:21:"fourohfour_page_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:21:"fourohfour_page_title";s:26:"Error 404: File not found.";s:31:"fourohfour_page_secondary_title";s:66:"That being said, we will give you an amazing deal for the trouble.";s:26:"fourohfour_page_breadcrumb";s:3:"404";s:23:"fourohfour_page_sidebar";s:0:"";s:32:"fourohfour_page_sidebar_position";s:0:"";s:17:"search_page_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:17:"search_page_title";s:6:"Search";s:27:"search_page_secondary_title";s:27:"Search results for: {query}";s:22:"search_page_breadcrumb";s:23:"Search results: {query}";s:19:"search_page_sidebar";s:0:"";s:28:"search_page_sidebar_position";s:0:"";s:19:"category_page_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:19:"category_page_title";s:17:"Category: {query}";s:29:"category_page_secondary_title";s:24:"Posts related to {query}";s:24:"category_page_breadcrumb";s:17:"Category: {query}";s:21:"category_page_sidebar";s:0:"";s:30:"category_page_sidebar_position";s:0:"";s:14:"tag_page_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:14:"tag_page_title";s:12:"Tag: {query}";s:24:"tag_page_secondary_title";s:24:"Posts related to {query}";s:19:"tag_page_breadcrumb";s:12:"Tag: {query}";s:16:"tag_page_sidebar";s:0:"";s:25:"tag_page_sidebar_position";s:0:"";s:23:"woo_category_page_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:23:"woo_category_page_title";s:7:"{query}";s:33:"woo_category_page_secondary_title";s:0:"";s:28:"woo_category_page_breadcrumb";s:7:"{query}";s:25:"woo_category_page_sidebar";s:0:"";s:34:"woo_category_page_sidebar_position";s:0:"";s:18:"woo_tag_page_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:18:"woo_tag_page_title";s:7:"{query}";s:28:"woo_tag_page_secondary_title";s:0:"";s:23:"woo_tag_page_breadcrumb";s:7:"{query}";s:20:"woo_tag_page_sidebar";s:0:"";s:29:"woo_tag_page_sidebar_position";s:0:"";s:19:"woo_shop_page_image";a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}s:19:"woo_shop_page_title";s:4:"Shop";s:29:"woo_shop_page_secondary_title";s:0:"";s:21:"woo_shop_page_sidebar";s:0:"";s:30:"woo_shop_page_sidebar_position";s:0:"";s:16:"themeforest_name";s:0:"";s:15:"themeforest_api";s:0:"";}';

		    $query = $wpdb->query( "UPDATE " . $wpdb->prefix . "options SET option_value = '" . $listing_panel_options . "' WHERE option_name = 'automotive_wp';" );

		    echo( $query === false ? "some_wrong" : "success" );
	    }

	    // import step 9 || Revolution Slider
	    if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) && $_POST['action'] == "automotive_demo_content_installer" && $_POST['step'] == 9 ) {

		    $slider = new RevSlider();

		    ob_start();
            $return = $slider->importSliderFromPost( true, true, LISTING_HOME . "demo_content/homepage_slideshow.zip" );
            ob_get_clean();

		    echo( isset($return['success']) && $return['success'] ? "success" : "some_wrong" );
	    }

	    // import step 8 || Set Options, Menus & Widgets
	    if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) && $_POST['action'] == "automotive_demo_content_installer" && $_POST['step'] == 8 ) {

		    // update front/blog page
		    $home_id = get_page_by_title( "Home" );
		    $blog_id = get_page_by_title( "Blog" );

		    update_option( "page_on_front", $home_id->ID );
		    update_option( "page_for_posts", $blog_id->ID );
		    update_option( "show_on_front", "page" );


		    // set menus (header-menu|footer-menu)
		    $main_term    = get_term_by( 'name', 'Main Menu', 'nav_menu' );
		    $main_menu_id = $main_term->term_id;

		    $footer_term    = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
		    $footer_menu_id = $footer_term->term_id;

		    $locations                = get_theme_mod( 'nav_menu_locations' );
		    $locations['header-menu'] = $main_menu_id;
		    $locations['mobile-menu'] = $main_menu_id;
		    $locations['footer-menu'] = $footer_menu_id;

		    set_theme_mod( 'nav_menu_locations', $locations );

		    // Set Widgets (listing_sidebar|default-footer|blog-widget)
		    $widgets = array(
			    "listing_sidebar" => array(
				    "filter-listings-widget" => array( 'title'        => 'Search Our Inventory',
				                                       'year'         => 1,
				                                       'make'         => 1,
				                                       'model'        => 1,
				                                       'body-style'   => 1,
				                                       'mileage'      => 1,
				                                       'transmission' => 1,
				                                       'fuel-economy' => 1,
				                                       'condition'    => 1,
				                                       'location'     => 1,
				                                       'price'        => 1
				    ),
				    "single-filter-widget"   => array( 'title' => 'Year', 'number' => 10, 'filter' => 'year' ),
				    "loan-calculator-widget" => array( 'title' => 'Financing Calculator' )
			    ),
			    "default-footer"  => array(
				    "mail-chimp-widget"   => array(
					    'title'       => 'Newsletter',
					    'description' => 'By subscribing to our company newsletter you will always be up-to-date on our latest promotions, deals and vehicle inventory!'
				    ),
				    "twitter-feed-widget" => array( 'title'    => 'Twitter Feed',
				                                    'username' => 'themesuite',
				                                    'tweets'   => 2
				    ),
				    "contact-us-widget"   => array( 'title'   => 'Contact Us',
				                                    'phone'   => '1-800-123-4567',
				                                    'address' => '1234 Street Name, City Name, AB 12345',
				                                    'email'   => 'sales@company.com'
				    )
			    ),
			    "blog-widget"     => array(
				    "recent-posts"              => array( 'title'     => 'Recent Posts',
				                                          'number'    => 5,
				                                          'show_date' => false
				    ),
				    "search"                    => array( 'title' => 'Search' ),
				    "archives"                  => array( 'title' => 'Archives', 'count' => 0, 'dropdown' => 0 ),
				    "categories"                => array(
					    'title'        => 'Categories',
					    'count'        => 0,
					    'hierarchical' => 0,
					    'dropdown'     => 0
				    ),
				    "meta"                      => array( 'title' => 'Meta' ),
				    "list-items-widget"         => array(
					    'title'  => 'Why Choose Us?',
					    'style'  => 'checkboxes',
					    'fields' => 'list_item=Fully+responsive+and+ready+for+mobile&list_item=Integrated+inventory+management&list_item=Simple+to+use+and+easy+to+customize&list_item=Search+engine+optimized+(SEO+ready)&list_item=Slider+Revolution+%26+Visual+Composer&list_item=Tons+of+shortcodes+for+easy+add-ons&list_item=Fully+featured+Option+Panel+for+setup&list_item=Backed+by+dedicated+support+staff'
				    ),
				    "testimonial-slider-widget" => array( 'title'  => 'Testimonials',
				                                          'fields' => 'testimonial_name_1=Theodore+Isaac+Rubin&testimonial_text_1=Happiness+does+not+come+from+doing+easy+work+but+from+the+afterglow+of+satisfaction+that+comes+after+the+achievement+of+a+difficult+task+that+demanded+our+best.&testimonial_name_2=Theodore+Isaac+Rubin&testimonial_text_2=Happiness+does+not+come+from+doing+easy+work+but+from+the+afterglow+of+satisfaction+that+comes+after+the+achievement+of+a+difficult+task+that+demanded+our+best.'
				    )
			    ),
                "single_listing_sidebar"    => array(
                    "listing-info-table"        => array( ),
                    "listing-woo-integration"   => array( ),
                    "listing-fuel-efficiency"   => array( ),
                    "listing-video"             => array( ),
                    "listing-social-icons"      => array( ),
                    "loan-calculator-widget"    => array(
                        "title" => "Financing Calculator"
                    )
                )
		    );

		    $active_widgets = array();
		    $widget_options = array();
		    $counter        = 1;

		    // run through each widget area
		    foreach ( $widgets as $widget_area => $area_widgets ) {

			    // run through each widget
			    foreach ( $area_widgets as $widget_title => $the_widget ) {
				    $active_widgets[ $widget_area ][] = $widget_title . "-" . $counter;

				    // add options
				    $widget_options[ "widget_" . $widget_title ][] = array( $counter, $the_widget );

				    $counter ++;
			    }

		    }

		    // Now save the active_widgets array.
		    update_option( 'sidebars_widgets', $active_widgets );

		    // update the widget details
		    foreach ( $widget_options as $widget_name => $widget ) {
			    $the_widget_options = array();

			    foreach ( $widget as $id => $options ) {
				    $the_widget_options[ $options[0] ] = $options[1];
			    }

			    update_option( $widget_name, $the_widget_options );
		    }


		    $listing_options    = get_option( "listing_wp" );
		    $automotive_options = get_option( "automotive_wp" );

		    // update fuel efficiency icon
		    $fuel_attached_image = wp_get_attachment_image_src( 920 );
		    $fuel_icon_array     = array(
			    "url"       => $fuel_attached_image[0],
			    "id"        => 920,
			    "height"    => $fuel_attached_image[2],
			    "width"     => $fuel_attached_image[1],
			    "thumbnail" => ""
		    );

		    $listing_options["fuel_efficiency_image"] = $fuel_icon_array;

		    // set sort by categories
		    $sort_by_categories = array(
			    "enabled"  => array(
				    "placebo" => "placebo",
				    "year"    => "Year",
				    "price"   => "Price"
			    ),
			    "disabled" => array(
				    "placebo"        => "placebo",
				    "model"          => "Model",
				    "body-style"     => "Body Style",
				    "mileage"        => "Mileage",
				    "transmission"   => "Transmission",
				    "fuel-economy"   => "Fuel Economy",
				    "condition"      => "Condition",
				    "location"       => "Location",
				    "drivetrain"     => "Drivetrain",
				    "engine"         => "Engine",
				    "exterior-color" => "Exterior Color",
				    "interior-color" => "Interior Color",
				    "mpg"            => "MPG",
				    "stock-number"   => "Stock Number",
				    "vin-number"     => "VIN Number",
				    "random"         => "Random",
				    "date"           => "Date",
				    "title"          => "Title",

			    )
		    );

		    $listing_options['sortby_categories'] = $sort_by_categories;

		    // adding $ to price
		    $listing_options['currency_symbol'] = '$';

		    // adding default header image
		    $header_attached_image = wp_get_attachment_image_src( 907, 'full' );
		    $default_header_array  = array(
			    "url"       => $header_attached_image[0],
			    "id"        => 907,
			    "height"    => $header_attached_image[2],
			    "width"     => $header_attached_image[1],
			    "thumbnail" => ""
		    );

		    $automotive_options["default_header_image"] = $default_header_array;


		    update_option( "listing_wp", $listing_options );
		    update_option( "automotive_wp", $automotive_options );
		    update_option( "hide_install_message", true );

		    echo "success";
	    }
    }

    die;
}
add_action("wp_ajax_automotive_demo_content_installer", "automotive_demo_content_installer");


// installer message
function automotive_installer_message() {
    $hide_install_message = get_option( "hide_install_message" );

    if( $hide_install_message != true ){
	    // move demo files into place if missing

	    /* Check Demo Content Files Exist */
	    $demo_content_files = array("1.xml", "2.xml", "3.xml", "4.xml", "5.xml", "homepage_slideshow.zip", "listing_panel_options.json", "theme_panel_options.json");
	    $missing_files      = array();

	    foreach($demo_content_files as $demo_file){
		    if(!file_exists(LISTING_HOME . "demo_content/" . $demo_file)){
			    $missing_files[] = $demo_file;

			    // only if bk file exists
			    if(file_exists(LISTING_HOME . "demo_content/bk/" . $demo_file)){
				    $copied = copy(LISTING_HOME . "demo_content/bk/" . $demo_file, LISTING_HOME . "demo_content/" . $demo_file);

				    if($copied && ($key = array_search($demo_file, $missing_files)) !== false){
					    unset($missing_files[$key]);
				    }
			    }
		    }
	    }

	    if(!empty($missing_files)){
		    $missing_files = implode(",", $missing_files);
		    $missing_files = rtrim($missing_files, ",");
	    }
	    ?>
        <div class="update-nag automotive_one_click_installer_nag" style="display: block;" data-nonce="<?php echo wp_create_nonce("automotive_installer"); ?>">
            <span style="float: right;"><a href="<?php echo esc_url( add_query_arg("hide_install_message", "true") ); ?>"><i class="fa fa-times"></i></a></span>

            <p><?php _e( 'Would you like to run the Automotive one click installer to install the demo content?', 'listings' ); ?></p>

	        <div class="requirement_list">
		        <table>
		        <?php
		        $good_icon = "<i class='fa fa-check'></i>";
		        $bad_icon  = "<i class='fa fa-close'></i>";

		        /* Max Post Size */
		        $post_max_size      = ini_get('post_max_size');
		        $post_max_size_byte = wp_convert_hr_to_bytes($post_max_size);

		        echo "<tr><td>" . __("Max. Post Size", "listings") . ":</td><td>" . ($post_max_size_byte < 33554432 ? $bad_icon : $good_icon) . __('Currently:', 'listings').' '.$post_max_size . ' ' . ($post_max_size_byte < 33554432 ? '<b>' . __('Recommended 32M', 'listings') . '</b>' : "") . "</td></tr>";

		        /* Memory Limit */
		        $mem_limit      = ini_get('memory_limit');
		        $mem_limit_byte = wp_convert_hr_to_bytes($mem_limit);

		        echo "<tr><td>" . __("Memory Limit", "listings") . ":</td><td>" . ($mem_limit_byte < 268435456 ? $bad_icon : $good_icon) . __('Currently:', 'listings').' '.$mem_limit . ' ' . ($mem_limit_byte < 268435456 ? '<b>' . __('Recommended 256M', 'listings') : "") . '</b>' . "</td></tr>";

		        /* Max Execution Time */
		        $max_execution_time = ini_get('max_execution_time');

		        echo "<tr><td>" . __("Max. Execution Time", "listings") . ":</td><td>" . ($max_execution_time < 30 ? $bad_icon : $good_icon) . __('Currently:', 'listings').' '.$max_execution_time . 's ' . ($max_execution_time < 30 ? '<b>' . __('Recommended 30 seconds', 'listings') : "") . '</b>' . "</td></tr>";

		        echo "<tr><td>" . __("Demo Content Files", "listings") . ":</td><td>" . (!empty($missing_files) ? $bad_icon : $good_icon) . (!empty($missing_files) ?  __("Missing files", "listings") . ": ".$missing_files : __("Files Exist", "listings")) . "</td></tr>";
		        
		        echo "<tr><td>" . __("Multibyte Module", "listings") . ":</td><td>" . (!function_exists( "mb_strtolower" ) ? $bad_icon : $good_icon) . (!function_exists( "mb_strtolower" ) ?  __("Multibyte Module is missing, please enable this in your panel or speak with your web host to enable it.", "listings") : __("Multibyte Module Exists", "listings")) . "</td></tr>";
		        ?>
		        </table>
	        </div>

	        <p><?php _e("Please note if any of the above recommendations aren't met you may not be able to fully import the demo content", "listings"); ?>. <a href="http://support.themesuite.com/kb/faq.php?id=8" target="_blank"><?php _e("Don't know how to increase these values?", "listings"); ?></a> </p>

            <p><a href="<?php echo esc_url( add_query_arg("install_automotive_demo", "true") ); ?>"><button class='button button-primary install_automotive_demo_content'><?php _e("Install Demo Content", "listings"); ?></button></a> <i class="fa fa-cog fa-spin loading_icon_spinner" style="display: none; font-size: 22px;"></i></p>

            <div id="progressbar" style="display: none;"><div></div></div>
            <span class="progress_steps" style="display: none; text-align: center;"><?php _e("Step", "listings"); ?> <span class="current_step">0</span> / <span class="total_steps">9</span></span>

            <div style="display: none;" class="import_complete">
                <?php _e("Congratulations! Your site is now ready!"); ?>
            </div>
        </div>
        <?php
    }
}

// Only with automotive theme
$theme = wp_get_theme();
if($theme->Name == "Automotive" || $theme->Name == "Automotive Child Theme"){
    add_action( 'admin_notices', 'automotive_installer_message' );
}
?>