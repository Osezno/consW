<?php
global $post, $lwp_options, $Listing, $slider_thumbnails;

wp_enqueue_script( 'google-maps' );
wp_enqueue_script( 'bxslider' );

wp_enqueue_style( 'social-likes' );

//********************************************
//	Language Variables
//***********************************************************
$no_features_text       = __( "There are no features available", "listings" );
$sold_text              = __("Sold", "listings");
$no_location_text       = __("No location available", "listings");
$none_text              = __("None", "listings");


$post_meta       = $Listing->get_listing_meta($post->ID);
$listing_options = (isset($post_meta['listing_options']) && !empty($post_meta['listing_options']) ? $post_meta['listing_options'] : "");
$gallery_images  = (isset($post_meta['gallery_images']) && !empty($post_meta['gallery_images']) ? $post_meta['gallery_images'] : "");
$multi_options   = (isset($post_meta['multi_options']) && !empty($post_meta['multi_options']) ? $post_meta['multi_options'] : "");
$location        = (isset($post_meta['location_map']) && !empty($post_meta['location_map']) ? $post_meta['location_map'] : "");

$is_sold         = (isset($post_meta['car_sold']) && $post_meta['car_sold'] == 1 ? true : false);

$multi_text      = $multi_pdf = "";
if(isset($multi_options) && !empty($multi_options)) {
	natcasesort( $multi_options );

	if ( ! empty( $multi_options ) ) {
		foreach ( $multi_options as $option ) {
			$multi_text .= "<li><i class=\"fa-li fa fa-check\"></i> " . stripslashes($option) . "</li>";

			// create comma separated list for pdf
			$multi_pdf .= htmlspecialchars( $option ) . ", ";
		}
	}

	$multi_pdf = rtrim( $multi_pdf, ", " );
} else {
	$multi_text .= "<li>" . $no_features_text . "</li>";
	$multi_pdf .= $no_features_text;
} ?>

 <div class="desktop" style="width:100%;    margin-top: 87px;position:relative;"><img src="http://consignurironnow.com/wp-content/uploads/2017/08/inventsingle.png" width="100%">
<div class="invetSingle" style="position: absolute;top: 1%;
    left: 0%;
    /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#00528e+3,00528e+21,002038+100 */
background: #00528e; /* Old browsers */
background: -moz-linear-gradient(top, #00528e 3%, #00528e 21%, #002038 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, #00528e 3%,#00528e 21%,#002038 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, #00528e 3%,#00528e 21%,#002038 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00528e', endColorstr='#002038',GradientType=0 ); /* IE6-9 */
    width: 50%;
    height: 99%;
    padding: 50px;"><h2 class="titlee" style="color:#fff;text-transform:uppercase;">Nuestra experiencia y excelencia a posicionado.</h2>
<div id="preciok" class="col-lg-6 col-md-6 col-sm-12  xs-padding-none">

				<?php
				if(isset($lwp_options['show_vehicle_history_inventory']) && !empty($lwp_options['show_vehicle_history_inventory']) && $lwp_options['show_vehicle_history_inventory'] == true){
					if(isset($lwp_options['vehicle_history']['url']) && !empty($lwp_options['vehicle_history']['url']) && isset($post_meta['verified'])){
						if(isset($lwp_options['carfax_linker']['url']) && !empty($lwp_options['carfax_linker']['url']) && isset($lwp_options['carfax_linker']['category']) && !empty($lwp_options['carfax_linker']['category'])){
							$url = str_replace("{vin}", $post_meta[$lwp_options['carfax_linker']['category']], $lwp_options['carfax_linker']['url']);
							echo "<a href='" . $url . "' target='_blank'>";
						}
						?>
						<img src="<?php echo $lwp_options['vehicle_history']['url']; ?>" alt="<?php echo (isset($lwp_options['vehicle_history_label']) && !empty($lwp_options['vehicle_history_label']) ? $lwp_options['vehicle_history_label'] : ""); ?>" class="carfax_title" />
						<?php
						if(isset($lwp_options['carfax_linker']['url']) && !empty($lwp_options['carfax_linker']['url']) && isset($lwp_options['carfax_linker']['category']) && !empty($lwp_options['carfax_linker']['category'])){
							echo "</a>";
						}
					}
				} ?>

				<?php
				if(isset($listing_options['price']['value']) && !empty($listing_options['price']['value'])){
					$original = (isset($listing_options['price']['original']) && !empty($listing_options['price']['original']) ? $listing_options['price']['original'] : "");

					

					if(isset($lwp_options['price_text_replacement']) && !empty($lwp_options['price_text_replacement']) && $lwp_options['price_text_all_listings'] == 0){
						echo do_shortcode($lwp_options['price_text_replacement']);
					} else {

						if((isset($post_meta['car_sold']) && $post_meta['car_sold'] == 1) && (!isset($lwp_options['hide_sold_price']) || empty($lwp_options['hide_sold_price'])) || (isset($post_meta['car_sold']) && $post_meta['car_sold'] == 2)) {
							echo '<h2 style="color:#fff;    background: #f5612e;padding: 10px;border-radius:3px;">' . $Listing->format_currency( $listing_options['price']['value'] ) . ' USD </h2>';

							
						}
					}
				} elseif( (empty($listing_options['price']['value']) && isset($lwp_options['price_text_all_listings']) && $lwp_options['price_text_all_listings'] == 1 ) || (isset($lwp_options['price_text_replacement']) && !empty($lwp_options['price_text_replacement']) && $lwp_options['price_text_all_listings'] == 0) ){
					echo do_shortcode($lwp_options['price_text_replacement']);
				}

				if(isset($post_meta['car_sold']) && $post_meta['car_sold'] == 1){
					echo '<span class="sold_text' . ( ! isset( $listing_options['price']['value'] ) || empty( $listing_options['price']['value'] ) || (isset($lwp_options['hide_sold_price']) && !empty($lwp_options['hide_sold_price'])) ? ' no_price' : '' ) . '">' . $sold_text . '</span>';
				} ?>
			</div>
</div>
</div>
<div class="mobile col-lg-5 col-md-3 col-sm-3 text-right xs-padding-none" style="margin-top:100px;">

				<?php
				if(isset($lwp_options['show_vehicle_history_inventory']) && !empty($lwp_options['show_vehicle_history_inventory']) && $lwp_options['show_vehicle_history_inventory'] == true){
					if(isset($lwp_options['vehicle_history']['url']) && !empty($lwp_options['vehicle_history']['url']) && isset($post_meta['verified'])){
						if(isset($lwp_options['carfax_linker']['url']) && !empty($lwp_options['carfax_linker']['url']) && isset($lwp_options['carfax_linker']['category']) && !empty($lwp_options['carfax_linker']['category'])){
							$url = str_replace("{vin}", $post_meta[$lwp_options['carfax_linker']['category']], $lwp_options['carfax_linker']['url']);
							echo "<a href='" . $url . "' target='_blank'>";
						}
						?>
						<img src="<?php echo $lwp_options['vehicle_history']['url']; ?>" alt="<?php echo (isset($lwp_options['vehicle_history_label']) && !empty($lwp_options['vehicle_history_label']) ? $lwp_options['vehicle_history_label'] : ""); ?>" class="carfax_title" />
						<?php
						if(isset($lwp_options['carfax_linker']['url']) && !empty($lwp_options['carfax_linker']['url']) && isset($lwp_options['carfax_linker']['category']) && !empty($lwp_options['carfax_linker']['category'])){
							echo "</a>";
						}
					}
				} ?>

				<?php
				if(isset($listing_options['price']['value']) && !empty($listing_options['price']['value'])){
					$original = (isset($listing_options['price']['original']) && !empty($listing_options['price']['original']) ? $listing_options['price']['original'] : "");

					

					if(isset($lwp_options['price_text_replacement']) && !empty($lwp_options['price_text_replacement']) && $lwp_options['price_text_all_listings'] == 0){
						echo do_shortcode($lwp_options['price_text_replacement']);
					} else {

						if((isset($post_meta['car_sold']) && $post_meta['car_sold'] == 1) && (!isset($lwp_options['hide_sold_price']) || empty($lwp_options['hide_sold_price'])) || (isset($post_meta['car_sold']) && $post_meta['car_sold'] == 2)) {
							echo '<h2 style="color:#fff;    background: #f5612e;padding: 10px;border-radius:3px;">' . $Listing->format_currency( $listing_options['price']['value'] ) . ' USD </h2>';

							
						}
					}
				} elseif( (empty($listing_options['price']['value']) && isset($lwp_options['price_text_all_listings']) && $lwp_options['price_text_all_listings'] == 1 ) || (isset($lwp_options['price_text_replacement']) && !empty($lwp_options['price_text_replacement']) && $lwp_options['price_text_all_listings'] == 0) ){
					echo do_shortcode($lwp_options['price_text_replacement']);
				}

				if(isset($post_meta['car_sold']) && $post_meta['car_sold'] == 1){
					echo '<span class="sold_text' . ( ! isset( $listing_options['price']['value'] ) || empty( $listing_options['price']['value'] ) || (isset($lwp_options['hide_sold_price']) && !empty($lwp_options['hide_sold_price'])) ? ' no_price' : '' ) . '">' . $sold_text . '</span>';
				} ?>
			</div>
        <section class="content<?php echo (isset($no_header) && $no_header == "no_header" ? " push_down" : ""); ?>"     style="background-color: #fff;">
        	
			<div class="container" style="
    width: 100%;">
<div class="inner-page inventory-listing" itemscope itemtype="http://schema.org/Vehicle">
	
	
	<div class="row" style=" margin-top: 50px;">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 left-content padding-left-none">
			<!--OPEN OF SLIDER-->
			<?php
			$full_images = $thumb_images = "";

			if(!empty($gallery_images)){
				foreach($gallery_images as $gallery_image){
					$gallery_thumb  = $Listing->auto_image($gallery_image, "auto_thumb", true);
					$gallery_slider = $Listing->auto_image($gallery_image, "auto_slider", true);
					$full           = $Listing->auto_image($gallery_image, "full", true);

					$alt 			= get_post_meta($gallery_image, "_wp_attachment_image_alt", true);

					$full_images  .= "<li data-thumb=\"" . $gallery_thumb . "\"> <img src=\"" . $gallery_slider . "\" alt=\"" . $alt . "\" data-full-image=\"" . $full . "\" width=\"" . $slider_thumbnails['slider']['width'] . "\" height=\"" . $slider_thumbnails['slider']['height'] . "\" /> </li>\n";
					$thumb_images .= "<li data-thumb=\"" . $gallery_thumb . "\"> <a href=\"#\"><img src=\"" . $gallery_thumb . "\" alt=\"" . $alt . "\" /></a> </li>\n";
				}
			} elseif(empty($gallery_images[0]) && isset($lwp_options['not_found_image']['id']) && !empty($lwp_options['not_found_image']['id'])){
				$gallery_thumb  = $Listing->auto_image($lwp_options['not_found_image']['id'], "auto_thumb", true);
				$gallery_slider = $Listing->auto_image($lwp_options['not_found_image']['id'], "auto_slider", true);
				$full 			= wp_get_attachment_image_src($lwp_options['not_found_image']['id'], "full");
				$full 			= $full[0];
				$alt 			= get_post_meta($lwp_options['not_found_image']['id'], "_wp_attachment_image_alt", true);

				$full_images  .= "<li data-thumb=\"" . $gallery_thumb . "\"> <img src=\"" . $gallery_slider . "\" alt=\"" . $alt . "\" data-full-image=\"" . $full . "\" /> </li>\n";
				$thumb_images .= "<li data-thumb=\"" . $gallery_thumb . "\"> <a href=\"#\"><img src=\"" . $gallery_thumb . "\" alt=\"" . $alt . "\" /></a> </li>\n";
			} ?>
			<div class="listing-slider">
				<?php $Listing->get_listing_badge_html($listing_options, $post_meta); ?>
				<section class="slider home-banner">
					<div class="flexslider loading" id="home-slider-canvas">
						<ul class="slides">
							<?php echo (!empty($full_images) ? $full_images : ""); ?>
						</ul>
					</div>
				</section>
				<section class="home-slider-thumbs">
					<div class="flexslider" id="home-slider-thumbs">
						<ul class="slides">
							<?php echo (!empty($thumb_images) ? $thumb_images : ""); ?>
						</ul>
					</div>
				</section>
			</div>
			<!--CLOSE OF SLIDER-->
			<!--Slider End-->
			<div class="clearfix"></div>

<div class="margin-top-50" style="background-color: #e0dbdb;">
 <div class="car-info margin-bottom-50">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
				                <?php
				                $listing_categories = $Listing->get_listing_categories();

				                if(!empty($listing_categories)){
					                foreach($listing_categories as $key => $category){
						                $slug  = $category['slug'];
						                $value = (isset($post_meta[$slug]) && !empty($post_meta[$slug]) ? $post_meta[$slug] : "");

						                $category['link_value'] = (isset($category['link_value']) && !empty($category['link_value']) ? $category['link_value'] : "");

						                if(empty($post_meta[$slug]) && isset($category['is_number']) && $category['is_number'] == 1){
							                $post_meta[$slug] = 0;
						                } elseif(empty($post_meta[$slug])) {
							                $post_meta[$slug] = $none_text;
						                }

						                // price
						                if(isset($category['currency']) && $category['currency'] == 1){
							                $value = $Listing->format_currency($value);
						                }

						                if(!isset($category['hide_category']) || $category['hide_category'] == 0){
							                if($category['link_value'] == "mpg"){
								                if(isset($listing_options) && isset($listing_options['city_mpg']['value']) && isset($listing_options['highway_mpg']['value'])) {
									                echo( ( mb_strtolower( $value ) != "none" && ! empty( $value ) ) && $value != __( "None", "listings" ) ? "<tr class='listing_category_" . sanitize_html_class($category['slug']) . "'><td class='orangeTitle'>" . $category['singular'] . ": </td><td>" . $listing_options['city_mpg']['value'] . " " . $lwp_options['default_value_city'] . " / " . $listing_options['highway_mpg']['value'] . " " . $lwp_options['default_value_hwy'] . "</td></tr>" : "" );
								                }
							                } elseif(
								                ($category['link_value'] != "price") ||
								                ($category['link_value'] == "price" && $is_sold && (!isset($lwp_options['hide_sold_price']) || empty($lwp_options['hide_sold_price']))) ||
								                ($category['link_value'] == "price" && !$is_sold)
							                ){
								                echo( ( mb_strtolower( $value ) != "none" && ! empty( $value ) ) && $value != __( "None", "listings" ) ? "<tr class='listing_category_" . sanitize_html_class($category['slug']) . "'><td class='orangeTitle'>" . $category['singular'] . ": </td><td>" . html_entity_decode( $value ) . "</td></tr>\n" : "" );
							                }
						                }
					                }
				                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
</div>
			<div class="single-listing-tabs margin-top-50">
				<ul id="myTab" class="nav nav-tabs">
					<?php
					$first_tab 	= (isset($lwp_options['first_tab']) && !empty($lwp_options['first_tab']) ? $lwp_options['first_tab'] : "" );
					$second_tab = (isset($lwp_options['second_tab']) && !empty($lwp_options['second_tab']) ? $lwp_options['second_tab'] : "" );
					$third_tab 	= (isset($lwp_options['third_tab']) && !empty($lwp_options['third_tab']) ? $lwp_options['third_tab'] : "" );
					$fourth_tab = (isset($lwp_options['fourth_tab']) && !empty($lwp_options['fourth_tab']) ? $lwp_options['fourth_tab'] : "" );
					$fifth_tab 	= (isset($lwp_options['fifth_tab']) && !empty($lwp_options['fifth_tab']) ? $lwp_options['fifth_tab'] : "" ); ?>

					<?php echo (!empty($first_tab) ? '<li class="active"><a href="#vehicle" data-toggle="tab">' . $first_tab . '</a></li>' : ''); ?>
					<?php echo (!empty($second_tab) ? '<li><a href="#features" data-toggle="tab">' . $second_tab . '</a></li>' : ''); ?>
					<?php echo (!empty($third_tab) ? '<li><a href="#technical" data-toggle="tab">' . $third_tab . '</a></li>' : ''); ?>
					<?php echo (!empty($fourth_tab) ? '<li><a href="#location" data-toggle="tab">' . $fourth_tab . '</a></li>' : ''); ?>
					<?php echo (!empty($fifth_tab) ? '<li><a href="#comments" data-toggle="tab">' . $fifth_tab . '</a></li>' : ''); ?>
				</ul>
				<div id="myTabContent" class="tab-content margin-top-15 margin-bottom-20">
					<?php if(!empty($first_tab)){ ?>
						<div class="tab-pane fade in active" id="vehicle" itemprop="description">
							<?php the_content(); ?>
						</div>
					<?php } ?>

					<?php if(!empty($second_tab)){ ?>
						<div class="tab-pane fade" id="features">
							<ul class="fa-ul" data-list="<?php echo $multi_pdf; ?>">
								<?php echo $multi_text; ?>
							</ul>
						</div>
					<?php } ?>

					<?php if(!empty($third_tab)){ ?>
						<div class="tab-pane fade" id="technical">
							<?php
							if(isset($post_meta['technical_specifications']) && !empty($post_meta['technical_specifications'])){
								echo wpautop(do_shortcode($post_meta['technical_specifications']));
							}
							?>
						</div>
					<?php } ?>

					<?php if(!empty($fourth_tab)){ ?>
						<div class="tab-pane fade" id="location">
							<?php
							$latitude  = (isset($location['latitude']) && !empty($location['latitude']) ? $location['latitude'] : "");
							$longitude = (isset($location['longitude']) && !empty($location['longitude']) ? $location['longitude'] : "");
							$zoom      = (isset($location['zoom']) && !empty($location['zoom']) ? $location['zoom'] : 11);

							if(!empty($latitude) && !empty($longitude)){ ?>
								<div class='google_map_init contact' data-longitude='<?php echo $longitude; ?>' data-latitude='<?php echo $latitude; ?>' data-zoom='<?php echo $zoom; ?>' data-scroll="false" style="height: 350px;" data-parallax="false"></div>
							<?php } else { ?>
								<?php echo $no_location_text; ?>
							<?php } ?>
						</div>
					<?php } ?>

					<?php if(!empty($fifth_tab)){ ?>
						<div class="tab-pane fade" id="comments">
							<?php echo (isset($post_meta['other_comments']) && !empty($post_meta['other_comments']) ? wpautop(do_shortcode($post_meta['other_comments'])) : ""); ?>
						</div>
					<?php } ?>
				</div>
			</div>

			<?php
			$sold_listing_comment = (isset($lwp_options['sold_listing_comment']) && !empty($lwp_options['sold_listing_comment']) ? $lwp_options['sold_listing_comment'] : "");

			if($is_sold) {
				echo wpautop( do_shortcode( $sold_listing_comment ) );
			} ?>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 right-content padding-right-none">
<div class="inventory-heading margin-bottom-10 clearfix" style="background-color: #e0dbdb;">
		<div class="row">
			<div class="col-lg-12 col-md-9 col-sm-9 col-xs-12 xs-padding-none">
				<div class="col-lg-12 col-md-9 col-sm-9 col-xs-12 xs-padding-none"><h2 itemprop="name" style="margin-bottom:6px;"><?php the_title(); ?></h2></div>
<div class="col-lg-12 col-md-3 col-sm-3  xs-padding-none">

				<?php
				if(isset($lwp_options['show_vehicle_history_inventory']) && !empty($lwp_options['show_vehicle_history_inventory']) && $lwp_options['show_vehicle_history_inventory'] == true){
					if(isset($lwp_options['vehicle_history']['url']) && !empty($lwp_options['vehicle_history']['url']) && isset($post_meta['verified'])){
						if(isset($lwp_options['carfax_linker']['url']) && !empty($lwp_options['carfax_linker']['url']) && isset($lwp_options['carfax_linker']['category']) && !empty($lwp_options['carfax_linker']['category'])){
							$url = str_replace("{vin}", $post_meta[$lwp_options['carfax_linker']['category']], $lwp_options['carfax_linker']['url']);
							echo "<a href='" . $url . "' target='_blank'>";
						}
						?>
						<img src="<?php echo $lwp_options['vehicle_history']['url']; ?>" alt="<?php echo (isset($lwp_options['vehicle_history_label']) && !empty($lwp_options['vehicle_history_label']) ? $lwp_options['vehicle_history_label'] : ""); ?>" class="carfax_title" />
						<?php
						if(isset($lwp_options['carfax_linker']['url']) && !empty($lwp_options['carfax_linker']['url']) && isset($lwp_options['carfax_linker']['category']) && !empty($lwp_options['carfax_linker']['category'])){
							echo "</a>";
						}
					}
				} ?>

				<?php
				if(isset($listing_options['price']['value']) && !empty($listing_options['price']['value'])){
					$original = (isset($listing_options['price']['original']) && !empty($listing_options['price']['original']) ? $listing_options['price']['original'] : "");

					

					if(isset($lwp_options['price_text_replacement']) && !empty($lwp_options['price_text_replacement']) && $lwp_options['price_text_all_listings'] == 0){
						echo do_shortcode($lwp_options['price_text_replacement']);
					} else {

						if((isset($post_meta['car_sold']) && $post_meta['car_sold'] == 1) && (!isset($lwp_options['hide_sold_price']) || empty($lwp_options['hide_sold_price'])) || (isset($post_meta['car_sold']) && $post_meta['car_sold'] == 2)) {
							echo '<h2 style="color: #f5612e;">' . $Listing->format_currency( $listing_options['price']['value'] ) . '</h2>';

							
						}
					}
				} elseif( (empty($listing_options['price']['value']) && isset($lwp_options['price_text_all_listings']) && $lwp_options['price_text_all_listings'] == 1 ) || (isset($lwp_options['price_text_replacement']) && !empty($lwp_options['price_text_replacement']) && $lwp_options['price_text_all_listings'] == 0) ){
					echo do_shortcode($lwp_options['price_text_replacement']);
				}

				if(isset($post_meta['car_sold']) && $post_meta['car_sold'] == 1){
					echo '<span class="sold_text' . ( ! isset( $listing_options['price']['value'] ) || empty( $listing_options['price']['value'] ) || (isset($lwp_options['hide_sold_price']) && !empty($lwp_options['hide_sold_price'])) ? ' no_price' : '' ) . '">' . $sold_text . '</span>';
				} ?>
			</div>

				<?php echo (isset($post_meta['secondary_title']) && !empty($post_meta['secondary_title']) ? "<span class='margin-top-10'>" . $post_meta['secondary_title'] . "</span>" : ""); ?>
			</div>
			
		</div>
<br>
<div class="col-lg-5 col-md-9 col-sm-9 col-xs-12 xs-padding-none"><h2 itemprop="name">
<form action="" method="post">
		<input name="add-to-cart" type="hidden" value="<?php echo $post->ID ?>" />
		
		<input name="submit" type="submit" value="Request Quote" />
	</form></h2></div>
<div class="col-lg-5 col-md-9 col-sm-9 col-xs-12 xs-padding-none"><h2 itemprop="name">
<div class="content-nav margin-bottom-30">
		<ul>
			<?php
			$next_link          = (get_permalink(get_adjacent_post(false,'',false)) == get_permalink() ? "#" : get_permalink(get_adjacent_post(false,'',false)));
			$prev_link          = (get_permalink(get_adjacent_post(false,'',true)) == get_permalink() ? "#" : get_permalink(get_adjacent_post(false,'',true)));

			$request_link       = (isset($lwp_options['request_more_link']) && !empty($lwp_options['request_more_link']) ? $lwp_options['request_more_link'] : "#request_fancybox_form");
			$request_target     = esc_attr(isset($lwp_options['request_more_target']) && !empty($lwp_options['request_more_target']) ? $lwp_options['request_more_target'] : "");

			$schedule_link      = (isset($lwp_options['schedule_test_link']) && !empty($lwp_options['schedule_test_link']) ? $lwp_options['schedule_test_link'] : "#schedule_fancybox_form");
			$schedule_target    = esc_attr(isset($lwp_options['schedule_test_target']) && !empty($lwp_options['schedule_test_target']) ? $lwp_options['schedule_test_target'] : "");

			$offer_link         = (isset($lwp_options['make_offer_link']) && !empty($lwp_options['make_offer_link']) ? $lwp_options['make_offer_link'] : "#offer_fancybox_form");
			$offer_target       = esc_attr(isset($lwp_options['make_offer_target']) && !empty($lwp_options['make_offer_target']) ? $lwp_options['make_offer_target'] : "");

			$trade_link         = (isset($lwp_options['tradein_link']) && !empty($lwp_options['tradein_link']) ? $lwp_options['tradein_link'] : "#trade_fancybox_form");
			$trade_target       = esc_attr(isset($lwp_options['tradein_target']) && !empty($lwp_options['tradein_target']) ? $lwp_options['tradein_target'] : "");

			$friend_link        = (isset($lwp_options['email_friend_link']) && !empty($lwp_options['email_friend_link']) ? $lwp_options['email_friend_link'] : "#email_fancybox_form");
			$friend_target      = esc_attr(isset($lwp_options['email_friend_target']) && !empty($lwp_options['email_friend_target']) ? $lwp_options['email_friend_target'] : "");

			$pdf_brochure       = get_post_meta($post->ID, "pdf_brochure_input", true);
			$pdf_link           = wp_get_attachment_url( $pdf_brochure ); ?>

			<?php if(isset($lwp_options['print_vehicle_show']) && !empty($lwp_options['print_vehicle_show']) && $lwp_options['print_vehicle_show'] == 1){ ?>
				<li class="desktop print gradient_button"><a class="print_page"><?php echo $lwp_options['print_vehicle_label']; ?></a></li>
			<?php } ?>

		</ul>
	</div>
	</div>
	</div>
         

			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>

		<?php if(isset($lwp_options['listing_comment_footer']) && !empty($lwp_options['listing_comment_footer']) && !$is_sold){ ?>
			<div class="listing_bottom_message margin-top-30" style="margin-bottom:10px;">
				<?php echo do_shortcode(wpautop($lwp_options['listing_comment_footer'])); ?>
			</div>
		<?php } ?>

		<?php if(isset($lwp_options['recent_vehicles_show']) && $lwp_options['recent_vehicles_show'] == 1){//recent_automatic_scrolling

			$related_category = 'related_category' . (defined("ICL_LANGUAGE_CODE") ? '_' . ICL_LANGUAGE_CODE : '');
			$other_options = ((isset($lwp_options[$related_category]) && !empty($lwp_options[$related_category]) ? $lwp_options[$related_category] : "") ? array("related_val" => $post_meta[$lwp_options[$related_category]], "current_id" => $post->ID) : array());

			if(isset($lwp_options['recent_automatic_scrolling']) && $lwp_options['recent_automatic_scrolling'] == 1){
				$other_options['autoscroll'] = "true";
			}

			echo vehicle_scroller($lwp_options['recent_vehicles_title'], $lwp_options['recent_vehicles_desc'],  $lwp_options['recent_vehicles_limit'], (isset($lwp_options['recent_related_vehicles']) && $lwp_options['recent_related_vehicles'] == 0 ? "related" : "newest"), null, $other_options );
		} ?>
	</div>

	<?php
	if( isset($lwp_options['listing_comments']) && $lwp_options['listing_comments'] == 1 ){
		echo '<div class="comments page-content margin-top-30 margin-bottom-40">';
		comments_template();
		echo '</div>';
	} ?>
</div></div>