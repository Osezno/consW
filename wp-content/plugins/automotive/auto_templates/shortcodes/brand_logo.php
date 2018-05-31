<?php
//********************************************
//	Automotive Brand Logo Shortcode
//***********************************************************

echo "<div class='slide hoverimg'>";
echo "<a href='" . esc_url( $link ) . "'" . ( isset( $target ) && ! empty( $target ) ? " target='" . esc_attr( $target ) . "'" : "" ) . " style='background-image: url(" . esc_url( wp_get_attachment_url( $img ) ) . ");' data-hoverimg='" . esc_url( wp_get_attachment_url( $hoverimg ) ) . "'></a>";
echo "</div>";
