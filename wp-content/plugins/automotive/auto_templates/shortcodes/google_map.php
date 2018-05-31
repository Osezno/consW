<?php
//********************************************
//	Automotive Google Map Shortcode
//***********************************************************
echo "<div class='contact google_map_init " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "' data-longitude='" . esc_attr( $longitude ) . "' data-latitude='" . esc_attr( $latitude ) . "' data-zoom='" . (int) $zoom . "' data-map-type='" . esc_attr( $map_type ) . "' style='height: " . (int) $height . "px;'" . ( ! empty( $map_style ) ? " data-style='" . $map_style . "'" : "" ) . " data-scroll='" . $scrolling . "'" . ( ! empty( $parallax_disabled ) && $parallax_disabled == "disabled" ? " data-parallax='false'" : "" ) .
     ( ! empty( $info_window_content ) ? " data-info-content='" . esc_attr( $info_window_content ) . "'" : "" ) .
     ( ! empty( $directions_button ) ? " data-directions_button='true'" : "" ) .
     ( ! empty( $directions_text ) ? " data-directions_text='" . esc_attr( $directions_text ) . "'" : "" ) .
     ( ! empty( $scrolling_disabled ) && $scrolling_disabled == "disabled" ? " data-scrolling='false'" : "" ) . "></div>";