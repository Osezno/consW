<?php
//********************************************
//	Automotive Inventory Shortcode
//***********************************************************

global $lwp_options, $Listing, $Listing_Template;

if(isset($hide_elements) && !empty($hide_elements)){
	$hide_elements = explode(",", $hide_elements);
}

$hide_select_view       = (is_array($hide_elements) && in_array("select_view", $hide_elements) ? "true" : "false");
$hide_sortby            = (is_array($hide_elements) && in_array("sortby_dropdown", $hide_elements) ? "true" : "false");
$hide_dropdown_filters  = (is_array($hide_elements) && in_array("dropdown_filters", $hide_elements) ? "true" : "false");
$hide_vehicles_matching = (is_array($hide_elements) && in_array("vehicles_matching", $hide_elements) ? "true" : "false");
$hide_reset_button      = (is_array($hide_elements) && in_array("reset_button", $hide_elements) ? "true" : "false");

// determine filters
$categories = $Listing->get_listing_categories();
$filterby   = ( isset( $_GET ) && ! empty( $_GET ) ? $_GET : array( "post_status" => array('publish')) );

if ( ! empty( $categories ) ) {
	foreach ( $categories as $key => $category ) {
		$safe = $category['slug'];
		$safe = ( $safe == "year" ? "yr" : $safe );

		if ( isset( $atts[ $safe ] ) && ! empty( $atts[ $safe ] ) && ! isset( $filterby[ $safe ] ) && $atts[ $safe ] != "none" ) {
			$filterby[ $safe ] = $atts[ $safe ];
		}
	}
}

// set sold
if ( isset( $atts['sold_only'] ) && ! empty( $atts['sold_only'] ) ) {
	$filterby['sold_only'] = $atts['sold_only'];
}

// newest arrivals
if ( isset( $atts['arrivals'] ) && ! empty( $atts['arrivals'] ) ) {
	$filterby['arrivals'] = $atts['arrivals'];
}

$Listing->current_listing_categories($filterby);
$Listing->set_current_query_info($filterby);

$listings = $Listing->current_query_info['listings'];

echo $Listing_Template->locate_template( "listing_view",
	array(
		"layout"                 => $layout,
		"fake_get"               => $filterby,
		"hide_select_view"       => $hide_select_view,
		"hide_vehicles_matching" => $hide_vehicles_matching
	)
);
echo $Listing_Template->locate_template( "listing_filter_sort",
	array(
		"fake_get"               => $filterby,
		"sold_dependancies"      => ( isset( $atts['sold_only'] ) && ! empty( $atts['sold_only'] ) ? true : false ),
		"hide_sortby"            => $hide_sortby,
		"hide_dropdown_filters"  => $hide_dropdown_filters,
		"hide_reset_button"      => $hide_reset_button
	)
);

$container = car_listing_container( $layout );

echo "<div class='row generate_new'>" ;

if ( $layout == "boxed_left" ) {
	echo "<div class=\" col-md-3  left-sidebar side-content listing-sidebar\">";
	dynamic_sidebar( "listing_sidebar" );
	echo "</div>";
} elseif ( $layout == "boxed_right" ) {
	echo "<div class=\"inventory-sidebar col-md-3 side-content listing-sidebar\">";
	dynamic_sidebar( "listing_sidebar" );
	echo "</div>";
} elseif ( $layout == "wide_left" ) {
	echo "<div class=\" col-md-3 col-lg-pull-9 col-md-pull-9 left-sidebar side-content listing-sidebar\">";
	dynamic_sidebar( "listing_sidebar" );
	echo "</div>";
} elseif ( $layout == "wide_right" ) {
	echo "<div class=\"inventory-sidebar col-md-3 side-content listing-sidebar\">";
	dynamic_sidebar( "listing_sidebar" );
	echo "</div>";
}

echo $container['start'];
if(!empty($listings)) {
	foreach ( $listings as $listing ) {
if(get_post_status($listing->ID) == 'publish'){
		echo $Listing_Template->locate_template( "inventory_listing",
			array(
				"id"     => $listing->ID,
				"layout" => $layout,
                               
			)
		);
}

	}
} else {
	echo do_shortcode('[alert type="2" close="No"]' . __("No listings found", "listings") . '[/alert]') . "<div class='clearfix'></div>";
}
echo "<div class=\"clearfix\"></div>";
echo $container['end'];



echo bottom_page_box( $layout, false, $filterby );
echo "</div>";

wp_reset_query();

echo "<div id='preview_slideshow'></div>";

echo "<div class='clearfix'></div>";
echo listing_youtube_video();