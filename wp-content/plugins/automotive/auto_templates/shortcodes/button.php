<?php
//********************************************
//	Automotive Button Shortcode
//***********************************************************

echo ( ! empty( $link ) ? "<a href='" . esc_url( $link ) . "' " . ( isset( $target ) && ! empty( $target ) ? "target='" . esc_attr( $target ) . "' " : "" ) . ">" : "" );
echo "<button class='btn button" . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . " " . ( ! empty( $size ) ? " " . sanitize_html_class( $size ) . "-button" : "" ) . "'" . ( $color ? " style='background-color: " . esc_attr( $color ) . "' data-color='" . esc_attr( $color ) . "'" : "" ) . ( $hover_color ? " data-hover='" . esc_attr( $hover_color ) . "'" : "" );
echo ( $modal !== false ? "data-toggle='modal' data-target='#" . esc_attr( $modal ) . "'" : "" );
echo ( $popover !== false ? "data-toggle='popover' data-placement='" . esc_attr( $placement ) . "' data-title='" . esc_attr( $title ) . "' data-content='" . esc_attr( $popover_content ) . "'" : "" );
echo ">" . do_shortcode( $content ) . "</button>";
echo ( ! empty( $link ) ? "</a>" : "" );