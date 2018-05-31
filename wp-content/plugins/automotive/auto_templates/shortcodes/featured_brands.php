<?php
//********************************************
//	Automotive Featured Brands Shortcode
//***********************************************************

echo "<div class=\"featured-brand " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "\">";
echo ( ! empty( $title ) ? "<h3 class='margin-bottom-25'>" . esc_html( $title ) . "</h3>" : "" );
echo "<div class=\"arrow2 pull-right clearfix slideControls\"><span class=\"next-btn\"></span><span class=\"prev-btn\"></span></div>";
echo "<div class=\"carasouel-slider featured_slider\">";
echo do_shortcode( $content );
echo "</div>";
echo "</div>";