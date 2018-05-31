<?php
//********************************************
//	Automotive Item List Shortcode
//***********************************************************

echo "<ul class='shortcode type-" . sanitize_html_class( $style ) . " " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "'>";
echo do_shortcode( $content );
echo "</ul>";