<?php
//********************************************
//	Automotive Featured Icon Box Shortcode
//***********************************************************

echo "<span class='align-center featured_icon_box " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "'><i class='" . sanitize_html_classes( $icon ) . " fa-6x'></i></span><h4>" . esc_html( $title ) . "</h4><p>" . do_shortcode( $content ) . "</p>";