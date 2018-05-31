<?php
//********************************************
//	Automotive Testimonials Shortcode
//***********************************************************

echo "<div class='" . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "'>" . testimonial_slider( $slide, $speed, $pager, $content ) . "<div class='clearfix'></div></div>";