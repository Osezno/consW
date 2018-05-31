<?php
global $lwp_options, $Listing, $slider_thumbnails;

//********************************************
//	Language Variables
//***********************************************************
$sold_text          = __( "Sold", "listings" );
$none_text          = __( "None", "listings" );
$view_details_text  = __( "View Details", "listings" );
$view_video_text    = __( "View Video", "listings" );

$listing            = get_post( $id );
$post_meta          = $Listing->get_listing_meta($id);

$listing_options    = ( isset( $post_meta['listing_options'] ) && ! empty( $post_meta['listing_options'] ) ? $post_meta['listing_options'] : array() );
$is_sold            = ( isset( $post_meta['car_sold']) && $post_meta['car_sold'] == 1 ? true : false );
if(get_post_status($listing->ID) == 'publish'){
if ( $layout == "boxed_fullwidth" ) {
	echo "<div class=\"col-lg-3 col-md-4 col-sm-6 col-xs-12\">";
} elseif ( $layout == "boxed_left" ) {
	echo "<div class=\"col-lg-4 col-md-6 col-sm-6 col-xs-12\">";
} elseif ( $layout == "boxed_right" ) {
	echo "<div class=\"col-lg-4 col-md-6 col-sm-6 col-xs-12\">";
}

// determine image
$image_src = $Listing->get_single_listing_image($id);

// get youtube id
if ( isset( $listing_options['video'] ) && ! empty( $listing_options['video'] ) ) {
	$video_details = $Listing->get_video_id($listing_options['video']);
	$is_different  = ($video_details[0] != "youtube" ? $video_details[0] : false);
	$video_id      = $video_details[1];
}

$is_custom_price_text = ( ( isset( $lwp_options['price_text_replacement'] ) && ! empty( $lwp_options['price_text_replacement'] ) && $lwp_options['price_text_all_listings'] == 0 ) ||
                        ( isset( $lwp_options['price_text_all_listings'] ) && $lwp_options['price_text_all_listings'] == 1 && empty( $listing_options['price']['value'] )) ? true : false);

// determine if checked
if ( isset( $_COOKIE['compare_vehicles'] ) && ! empty( $_COOKIE['compare_vehicles'] ) ) {
	$compare_vehicles = explode( ",", urldecode( $_COOKIE['compare_vehicles'] ) );
} ?>

	<div
		class="inventory clearfix margin-bottom-20 styled_input <?php echo( isset( $post_meta['car_sold'] ) && $post_meta['car_sold'] == 1 ? "car_sold" : "" );
		echo ( empty( $listing_options['price']['value'] ) && $is_custom_price_text && empty($lwp_options['price_text_replacement']) ? " no_price" : "" ); ?>">
		<?php if ( isset( $lwp_options['car_comparison'] ) && $lwp_options['car_comparison'] ) { ?>
			<input type="checkbox" class="checkbox compare_vehicle" id="vehicle_<?php echo $id; ?>"
			       data-id="<?php echo $id; ?>"<?php echo( isset( $compare_vehicles ) && in_array( $id, $compare_vehicles ) ? " checked='checked'" : "" ); ?> />
			<label for="vehicle_<?php echo $id; ?>"></label>
		<?php } ?>

		<?php $Listing->get_listing_badge_html($listing_options, $post_meta); ?>

		<a class="inventory<?php echo( isset( $listing_options['custom_badge'] ) && ! empty( $listing_options['custom_badge'] ) ? " has_badge" : "" ); ?>"
		   href="<?php echo get_permalink( $id ); ?>">
			
			<img src="<?php echo $image_src; ?>" class="preview"
			     alt="<?php _e( "preview", "listings" ); ?>"
                 width="<?php echo $slider_thumbnails['listing']['width']; ?>"
                 height="<?php echo $slider_thumbnails['listing']['height']; ?>"
                <?php echo( isset( $lwp_options['thumbnail_slideshow'] ) && $lwp_options['thumbnail_slideshow'] == 1 ? 'data-id="' . $id . '"' : "" ); ?>>
<?php //
		if ( ( isset( $listing_options['price']['value'] ) && ! empty( $listing_options['price']['value'] ) ) || ( isset( $lwp_options['price_text_replacement'] ) && ! empty( $lwp_options['price_text_replacement'] ) ) ) {
			$original = ( isset( $listing_options['price']['original'] ) && ! empty( $listing_options['price']['original'] ) ? $listing_options['price']['original'] : "" );

			if ( ( $is_sold && ( ! isset( $lwp_options['hide_sold_price'] ) || empty( $lwp_options['hide_sold_price'] ) ) ) || ! $is_sold ) { ?>

				<div
					class="price<?php echo( $is_custom_price_text ? " custom_message price_replacement" : '' ); ?>">
					<?php if ( $is_custom_price_text ) { ?>
						<?php echo do_shortcode( $lwp_options['price_text_replacement'] ); ?>
					<?php } else { ?>
						
						<div
							class="figure"><?php echo $Listing->format_currency( $listing_options['price']['value'] ); ?> USD
							<br></div>
						
					<?php } ?>
				</div>
			<?php }
		} ?>
<div class="title2" style="margin-bottom:0px;padding-left: 5px;"><?php echo $listing->post_title; ?></div>

			<?php
			if(isset($lwp_options['vehicle_overview_listings']) && $lwp_options['vehicle_overview_listings'] == 1){
                $visual_composer_used = get_post_meta($id, "_wpb_vc_js_status", true);

				$limit        = (isset($lwp_options['vehicle_overview_listings_limit']) && !empty($lwp_options['vehicle_overview_listings_limit']) ? $lwp_options['vehicle_overview_listings_limit'] : 250);
		        $ellipsis     = (isset($lwp_options['vehicle_overview_ellipsis']) ? $lwp_options['vehicle_overview_ellipsis'] : "[...]");
				$stripp       = "<br><p><b><u><i><span><a><img>";

				$vehicle_excerpt  = get_post_field("post_excerpt", $id);
				$vehicle_overview = get_post_field("post_content", $id);

				$vehicle_desc     = (!empty($vehicle_excerpt) ? $vehicle_excerpt : $vehicle_overview);

                if($visual_composer_used){
	                $post_content = preg_replace( '/\[[^\]]+\]/', '', $vehicle_desc );
	                $post_content = substr(strip_tags($post_content, $stripp), 0, $limit) . " " . (strlen(strip_tags($post_content, $stripp)) > $limit ? $ellipsis : "");
                } else {
	                $post_content = substr(strip_tags($vehicle_desc, $stripp), 0, $limit) . " " . (strlen(strip_tags($vehicle_desc, $stripp)) > $limit ? $ellipsis : "");
                }

				echo "<p class=\"vehicle_overview\">" . strip_tags($post_content) . "</p>";
			} else {
				$listing_details = $Listing->get_use_on_listing_categories();
				
				$use_on_listing_values = array();
				
				foreach($listing_details as $detail){
					$slug  = $detail['slug'];
					$value = ( isset( $post_meta[ $slug ] ) && ! empty( $post_meta[ $slug ] ) ? $post_meta[ $slug ] : "" );

					if ( empty( $value ) && isset( $detail['is_number'] ) && $detail['is_number'] == 1 ) {
						$value = 0;
					} elseif ( empty( $value ) ) {
						$value = $none_text;
					}

					if(!empty($value) && $value != __("None", "listings")) {
						$use_on_listing_values[ $slug ] = array("singular" => $detail['singular'], "value" => $value);
					}
				}

				if ( count( $use_on_listing_values ) > 5 ) {
					$first_details  = array_slice( $use_on_listing_values, 0, 5, true );
					$second_details = array_slice( $use_on_listing_values, 5, count( $use_on_listing_values ), true );
				} else {
					$single_table  = true;
					$first_details = $use_on_listing_values;
				}

				
			} ?>
<?php 
$k = 0;
$B = [];
foreach ( $first_details as $slug => $detail)
{
 $B[$k] =  html_entity_decode( $detail['value'] ) ;
 $k++;
  
}
echo  '<div class="Model"><h5 style="margin-top: 0px;margin-bottom:0px;color:#ff6029;font-family:oswald;padding-left: 5px;">';
echo $B[3];	
echo '</h5></div>';
echo  '<div class="Marca"><h6 style="margin-bottom:0px;color:#ff6029;font-family:oswald; padding-left: 5px;"><strong>Brand:&nbsp;</strong>';
echo $B[0];
echo '</h4></div>';
echo  '<div class="bottomI"><div class="year"> <p class="Byear" style="margin-bottom:0px;color:#ff6029;text-align: center;"><strong>Year:&nbsp;</strong>';
echo $B[2];
echo '</p></div>
<div class="Hour"> <p class="Bhour" style="margin-bottom:0px;color:#ff6029; text-align: center;"><strong>Hours:&nbsp;</strong>';
echo $B[1];	
		
?>
</p>  
							</div>
			<div class="view-details gradient_button"> <?php echo $view_details_text; ?> </div>
			</div><div class="clearfix"></div>
		</a>

		

		<?php
		if ( isset( $lwp_options['vehicle_history']['url'] ) && ! empty( $lwp_options['vehicle_history']['url'] ) && isset( $post_meta['verified'] ) ) {
			if ( isset( $lwp_options['carfax_linker']['url'] ) && ! empty( $lwp_options['carfax_linker']['url'] ) && isset( $lwp_options['carfax_linker']['category'] ) && ! empty( $lwp_options['carfax_linker']['category'] ) ) {
				$url = str_replace( "{vin}", $post_meta[ $lwp_options['carfax_linker']['category'] ], $lwp_options['carfax_linker']['url'] );
				echo "<a href='" . $url . "' target='_blank'>";
			}
			?>
			<img src="<?php echo $lwp_options['vehicle_history']['url']; ?>"
			     alt="<?php echo( isset( $lwp_options['vehicle_history_label'] ) && ! empty( $lwp_options['vehicle_history_label'] ) ? $lwp_options['vehicle_history_label'] : "" ); ?>"
			     class="carfax"/>
			<?php
			if ( isset( $lwp_options['carfax_linker']['url'] ) && ! empty( $lwp_options['carfax_linker']['url'] ) && isset( $lwp_options['carfax_linker']['category'] ) && ! empty( $lwp_options['carfax_linker']['category'] ) ) {
				echo "</a>";
			}
		} ?>

		<?php if ( isset( $video_id ) && ! empty( $video_id ) ) { ?>

			<?php if( isset( $is_different ) && $is_different == "self_hosted"){
				$random_div_id = random_string();

				echo "<div id='" . $random_div_id . "' style='display: none;'>" . do_shortcode("[video width=\"600\" height=\"480\" mp4=\"" . $video_id . "\"]") . "</div>";
			} ?>
			
		<?php } ?>
	</div>

<?php

if ( $layout == "boxed_fullwidth" || $layout == "boxed_left" || $layout == "boxed_right" ) {
	echo "</div>";
}
}