<?php
//********************************************
//	Automotive Single Filter Widget
//***********************************************************
global $post, $Listing;

echo $before_widget;
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title;

$options  = $Listing->get_single_listing_category($filter);
$compare  = (isset($options['compare_value']) && !empty($options['compare_value']) ? $options['compare_value'] : "=");
$currency = (isset($options['currency']) && !empty($options['currency']) ? $options['currency'] : "");
$sort     = (isset($options['sort_terms']) && !empty($options['sort_terms']) ? $options['sort_terms'] : "");
$options  = (isset($options['terms']) && !empty($options['terms']) ? $options['terms'] : "");

if(isset($options) && !empty($options)){
	$i = 0;
	echo "<ul class='single_filter margin-bottom-none'>";

	$url = (isset($_REQUEST['page_id']) && !empty($_REQUEST['page_id']) ? get_permalink($_REQUEST['page_id']) : get_permalink( $post->ID ));

	if(isset($sort) && $sort == "desc"){
		arsort($options);
	} else {
		asort($options);
	}

	// if compare value isn't = we need to do some calculations
	if($compare != "="){
		$all_post_values = get_all_meta_values($filter, 'listings', 'publish', ($show_sold == "yes" ? true : false));
	}

	foreach($options as $option => $option_value){
		$number_meta = get_total_meta($filter, $option_value, false);

		$option_label = $option_value;

		if(isset($currency) && $currency == 1){
			$option_label = $Listing->format_currency($option_label);
		}

		$current_categories = $Listing->current_categories;
		$current_categories[(strtolower( $filter ) == "year" ? "yr" : $filter)] = urlencode( $option );

		if($compare != "=" && isset($all_post_values)){
			if(version_compare(phpversion(), '5.4', '>')) {
				$all_numbers = array_filter(
					$all_post_values,
					function ( $value ) use ( &$option_value, &$compare ) {
						if ( $compare == "<" || $compare == htmlentities( "<" ) ) {
							return ( $value < $option_value );
						} elseif ( $compare == "<=" || $compare == htmlentities( "<=" ) ) {
							return ( $value <= $option_value );
						} elseif ( $compare == ">" || $compare == htmlentities( ">" ) ) {
							return ( $value > $option_value );
						} elseif ( $compare == ">=" || $compare == htmlentities( ">=" ) ) {
							return ( $value >= $option_value );
						}
					}
				);
			}

			echo "<li><a href='" . str_replace( "&", "&amp;", add_query_arg( $current_categories, $url ) ) . "'>" . $compare . " " . $option_label . " (" . count($all_numbers) . ")</a></li>\n";
			$i ++;
		} else {
			if ( $number_meta != 0 ) {
				echo "<li><a href='" . str_replace( "&", "&amp;", add_query_arg( $current_categories, $url ) ) . "'>" . $option_label . " (" . $number_meta . ")</a></li>\n";
				$i ++;
			}
		}

		if($i == $number){
			break;
		}
	}
	echo "</ul>";
}

echo "<div class='clearfix'></div>";
echo $after_widget;