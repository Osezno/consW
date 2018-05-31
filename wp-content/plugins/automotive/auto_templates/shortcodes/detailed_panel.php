<?php
//********************************************
//	Automotive Detailed Panel Shortcode
//***********************************************************
echo "<div class='detail-service " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "'>";
echo "<div class='details margin-top-25'>";

echo ( ! empty( $href ) ? "<a href='" . esc_url( $href ) . "'" . ( isset( $target ) && ! empty( $target ) ? " target='" . esc_attr( $target ) . "'" : "" ) . ">" : "" ) . " <h5>" . $icon . esc_attr( $title ) . "</h5>" . ( ! empty( $href ) ? "</a>" : "" );
echo "<p class='padding-top-10 margin-bottom-none'>" . do_shortcode( $content ) . "</p>";
echo "</div></div>";