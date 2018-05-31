<?php
//********************************************
//	Automotive Recent Listings Widget
//***********************************************************

global $lwp_options, $Listing;

echo $before_widget;
echo $before_title . $title . $after_title;


$listings = get_posts(
	array(
		"post_type"         => "listings",
		"posts_per_page"    => $number,
		"orderby"           => "post_date",
		"order"             => "DESC"
	)
);

if(!empty($listings)){
	foreach($listings as $listing){
		$options   = unserialize(get_post_meta($listing->ID, "listing_options", true));
		$image_src = $Listing->get_single_listing_image($listing->ID);

		echo '<div class="car-block recent_car">         
    			            <div class="car-block-bottom">
    			            	<div class="img-flex"> 
    				            	<a href="' . get_permalink($listing->ID) . '">
    				            		<span class="align-center"><i class="fa fa-2x fa-plus-square-o"></i></span>
    				            	</a> 
    				            	<img src="' . $image_src . '" alt="" class="img-responsive">
    				            </div>
    			                <h6><strong>' . $listing->post_title . '</strong></h6>
    			                ' . (!empty($options['short_desc']) ? '<h6>' . $options['short_desc']. '</h6>' : '') . '
    			                <h5>' . $Listing->format_currency($options['price']['value']) . '</h5>
    			            </div>
    			        </div>';
	}
}

echo $after_widget;