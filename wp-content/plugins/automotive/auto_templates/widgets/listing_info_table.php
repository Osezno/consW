<?php
$none_text = __("None", "listings");

$is_sold   = (isset($post_meta['car_sold']) && $post_meta['car_sold'] == 1 ? true : false);

global $lwp_options;

echo $before_widget;

echo (!empty($title) ? $before_title . $title . $after_title : ""); ?>
<div class="car-info">
	<div class="table-responsive">
		<table class="table">
			<tbody>
			<?php
			global $Listing;

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
								echo( ( mb_strtolower( $value ) != "none" && ! empty( $value ) ) && $value != __( "None", "listings" ) ? "<tr class='listing_category_" . sanitize_html_class($category['slug']) . "'><td>" . $category['singular'] . ": </td><td>" . $listing_options['city_mpg']['value'] . " " . $lwp_options['default_value_city'] . " / " . $listing_options['highway_mpg']['value'] . " " . $lwp_options['default_value_hwy'] . "</td></tr>" : "" );
							}
						} elseif(
							($category['link_value'] != "price") ||
							($category['link_value'] == "price" && $is_sold && (!isset($lwp_options['hide_sold_price']) || empty($lwp_options['hide_sold_price']))) ||
							($category['link_value'] == "price" && !$is_sold)
						){
							echo( ( mb_strtolower( $value ) != "none" && ! empty( $value ) ) && $value != __( "None", "listings" ) ? "<tr class='listing_category_" . sanitize_html_class($category['slug']) . "'><td>" . $category['singular'] . ": </td><td>" . html_entity_decode( $value ) . "</td></tr>\n" : "" );
						}
					}
				}
			} ?>
			</tbody>
		</table>
	</div>
</div>
<?php echo $after_widget; ?>