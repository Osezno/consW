<?php
//********************************************
//	Automotive Alert Shortcode
//***********************************************************

echo "<div class=\"alert alert-" . sanitize_html_class( $type ) . " " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "\">";
echo( strtolower( $close ) != "no" ? "<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">" . __( "Close", "listings" ) . "</span></button>" : "" );
echo do_shortcode( $content );
echo "</div>";