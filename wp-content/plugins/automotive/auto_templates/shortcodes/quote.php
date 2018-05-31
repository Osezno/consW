<?php
//********************************************
//	Automotive Quote Shortcode
//***********************************************************

echo "<div class='quote'" . ( isset( $color ) && $color != "#c7081b" ? " style='border-color: " . esc_html( $color ) . "'" : "" ) . ">";
echo do_shortcode( $content );
echo "</div>";