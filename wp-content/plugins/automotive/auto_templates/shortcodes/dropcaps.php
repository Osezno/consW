<?php
//********************************************
//	Automotive Dropcaps Shortcode
//***********************************************************

echo "<span class='firstcharacter" . (!empty($extra_class) ? " " . sanitize_html_classes( $extra_class ) : "") . "'>" . do_shortcode( $content ) . "</span>";