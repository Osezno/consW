<?php
//********************************************
//	Automotive FAQ Shortcode
//***********************************************************

if($sort_element == "yes") {
	echo "<div class=\"list_faq clearfix " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "\">";
	echo "<h5>" . $sort_text . "</h5>";
	echo "<ul>";

	if ( isset( $categories ) && ! empty( $categories ) ) {
		$categories      = explode( ",", $categories );
		$sort_categories = ( $all_category == "yes" ? "<li><a href='#All' data-action='sort'>" . __( "All", "listings" ) . "</a></li>" : "" );

		foreach ( $categories as $category ) {
			$sort_categories .= "<li><a href='#" . esc_attr( $category ) . "' data-action='sort'>" . esc_attr( $category ) . "</a></li>";
		}

		echo $sort_categories;
	}

	echo "</ul>";
	echo "</div>";
}

echo "<div class=\"accodian_panel margin-top-30\"><div class=\"panel-group description-accordion faq-sort faq margin-bottom-none\" id=\"accordion\"> ";
echo do_shortcode( $content );
echo "</div></div>";