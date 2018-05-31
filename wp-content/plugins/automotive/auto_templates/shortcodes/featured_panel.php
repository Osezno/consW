<?php
//********************************************
//	Automotive Featured Panel
//***********************************************************

echo "<div class='featured margin-top-25 " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "'>";
echo "<h5>" . esc_html( $title ) . "</h5>";

echo( ! empty( $image_link ) ? "<a href='" . esc_url( $image_link ) . "'>" : "" );
echo "<img src='" . esc_url( $icon[0] ) . "' data-hoverimg='" . esc_url( $hover_icon[0] ) . "' alt=\"" . esc_html( $alt ) . "\" class=\"no_border\">";
echo( ! empty( $image_link ) ? "</a>" : "" );

echo "<p>" . do_shortcode( $content ) . "</p>";
echo "</div>";