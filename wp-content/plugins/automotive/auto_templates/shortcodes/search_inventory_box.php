<?php
//********************************************
//	Automotive Search Inventory Box Shortcode
//***********************************************************

global $lwp_options, $Listing;

$column_1_items = ( isset( $column_1 ) && ! empty( $column_1 ) ? explode( ",", $column_1 ) : "" );
$column_2_items = ( isset( $column_2 ) && ! empty( $column_2 ) ? explode( ",", $column_2 ) : "" );

echo "<div class=\"search-form search_inventory_box  " . ( ! empty( $extra_class ) ? $extra_class : "" ) . " styled_input\">";

if($page_id == "|||" || empty($page_id)){
	echo "<div class='col-md-12'>";
	echo do_shortcode('[alert type="3"]' . __('This form doesn\'t have a form action set, please set this to point to your inventory page under <b>Form Action</b> in the shortcode settings.', 'listings') . '[/alert]');
	echo "</div>";
}

echo "<form method=\"get\" action=\"" . $page_id . "\" data-form=\"" . $term_form . "\">";

parse_str( parse_url( $page_id, PHP_URL_QUERY ), $result );
$result['page_id'] = ( isset( $result['page_id'] ) && ! empty( $result['page_id'] ) ? $result['page_id'] : "" );

echo (!empty($result['page_id']) ? "<input type='hidden' name='page_id' value='" . $result['page_id'] . "'>" : "");

$column_class = (isset($column_2_items) && !empty($column_2_items) ? "col-md-6" : "col-md-12");

echo "<div class=\"" . $column_class . " clearfix\">";
echo generate_search_dropdown( $column_1_items, $min_max, array('prefix_text' => $prefix_text, 'term_form' => $term_form) );
echo apply_filters("search_box_column_1", "");
echo "<div class='clearfix'></div></div>";

if(!empty($column_2_items)) {
	echo "<div class=\"" . $column_class . " clearfix\">";
	echo generate_search_dropdown( $column_2_items, $min_max, array(
		'prefix_text'   => $prefix_text,
		'term_form'     => $term_form
	) );
	echo apply_filters("search_box_column_2", "");
	echo "<div class='clearfix'></div></div>";
}

echo "<div class=\"col-md-12 clearfix search_categories\">";

$additional_categories = $Listing->get_additional_categories();

$i = 1;
if ( ! empty( $additional_categories ) ) {
	foreach ( $additional_categories as $category ) {
		if ( ! empty( $category ) ) {
			echo "<div class='form-element'><input type='checkbox' id='check_" . $i . "' name='" . str_replace( " ", "_", strtolower( $category ) ) . "' value='1'><label for='check_" . $i . "'>" . esc_html( $category ) . "</label></div>";
			$i ++;
		}
	}
}

echo "<div class='clearfix'></div></div>";

echo '<div class="form-element pull-right margin-right-10 col-md-12"><input type="submit" value="' . esc_attr( $button_text ) . '" class="find_new_vehicle pull-right"><div class="loading_results pull-right"><i class="fa fa-circle-o-notch fa-spin"></i></div></div>';

echo "</form>";
echo "</div>";