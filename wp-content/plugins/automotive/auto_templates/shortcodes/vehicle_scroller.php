<?php
//********************************************
//	Automotive Vehicle Scroller Shortcode
//***********************************************************

global $Listing;

// categories
$categories = $Listing->get_listing_categories();

if ( ! empty( $categories ) ) {
	foreach ( $categories as $key => $category ) {
		$safe = $category['slug'];
		$safe = ( $safe == "year" ? "yr" : $safe );

		if ( isset( $atts[ $safe ] ) && ! empty( $atts[ $safe ] ) && ! isset( $filterby[ $safe ] ) && $atts[ $safe ] != "none" ) {
			$other_options['categories'][ $safe ] = $atts[ $safe ];
		}
	}
}



echo vehicle_scroller( $title, $description, $limit, $sort, $listings, $other_options );