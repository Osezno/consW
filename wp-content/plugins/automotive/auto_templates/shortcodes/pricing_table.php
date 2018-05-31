<?php
//********************************************
//	Pricing Table
//***********************************************************

echo "<div class=\"pricing_table " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "\">";
echo "<div class=\"pricing-header padding-vertical-10\"" . (!empty($header_color) ? " style='background-color: " . esc_attr( $header_color ) . "'" : "") . "><h4>" . esc_html( $title ) . "</h4></div>";
echo "<div class=\"main_pricing\">";

echo "<div class=\"inside\">";
echo "<span class=\"super\">" . ( isset( $lwp_options['currency_symbol'] ) && ! empty( $lwp_options['currency_symbol'] ) ? $lwp_options['currency_symbol'] : "" ) . "</span>";

if ( strstr( $price, "." ) ) {
	$price_exploded = explode( ".", $price );
	echo "<span class=\"amt annual\">" . $price_exploded[0] . "</span><span class=\"sub1\">" . $price_exploded[1] . "</span>";
} else {
	echo "<span class=\"amt annual\">" . $price . "</span>";
}

echo ( ! empty( $often ) ? "<span class=\"slash\"><img src=\"" . LISTING_DIR . "images/slash.png\" alt=\"\" class=\"no_border\"></span>" : "" );
echo ( ! empty( $often ) ? "<span class=\"sub\">" . esc_html( $often ) . "</span>" : "" );
echo "</div>";
echo "</div>";
echo "<div class=\"category_pricing\">";
echo "<ul>";
echo do_shortcode( $content );
echo "</ul>";
echo "</div>";
echo "<div class=\"price-footer padding-top-20 padding-bottom-15\">";
echo "<form method=\"post\" action=\"" . esc_html( $link ) . "\">";
echo "<input type=\"submit\" value=\"" . esc_html( $button ) . "\" class='lg-button'>";
echo "</form>";
echo "</div>";
echo "</div>";