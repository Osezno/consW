<?php
//********************************************
//	Automotive Staff Shortcode
//***********************************************************

echo "<div class=\"team hoverimg " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "\"  itemscope itemtype=\"http://schema.org/Person\"> ";
echo ( ! empty( $hoverimg ) ? "<a href=\"" . esc_url( $hoverimg ) . "\" class=\"fancybox\">" : "" ) . " <img src=\"" . esc_url( $img ) . "\" class=\"aligncenter no_border\" alt=\"" . esc_attr( $name ) . "\" itemprop=\"image\" /> " . ( ! empty( $hoverimg ) ? "</a>" : "" );
echo "<div class=\"name_post\">";
echo "<h4 itemprop=\"name\">" . esc_attr( $name ) . "</h4>";
echo "<p itemprop=\"jobTitle\">" . esc_attr( $position ) . "</p>";
echo "</div>";
echo "<div class=\"about_team\">";
echo "<p>" . do_shortcode( $content ) . "</p>";
echo "<ul>";
echo( ! empty( $phone ) ? "<li><i class=\"fa fa-phone\"></i><span itemprop=\"telephone\">" . esc_attr( $phone ) . "</span></li>" : "" );
echo( ! empty( $cell_phone ) ? "<li><i class=\"fa fa-mobile\"></i><span itemprop=\"telephone\">" . esc_attr( $cell_phone ) . "</span></li>" : "" );
echo( ! empty( $email ) ? "<li><i class=\"fa fa-envelope-o\"></i><a href='mailto:" . sanitize_email($email) . "' itemprop=\"email\">" . sanitize_email($email) . "</a></li>" : "" );
echo "</ul>";
echo "</div>";
echo "<div class=\"social_team pull-left\">";
echo "<ul class=\"social\">";

foreach ( $icons as $icon ) {
	if ( $$icon !== false && ! empty( $$icon ) ) {
		echo "<li class='margin-bottom-none'><a href=\"" . esc_url( $$icon ) . "\" class=\"" . sanitize_html_classes($icon) . "\"></a></li>\n";
	}
}

echo "</ul>";
echo "</div>";
echo "<div class=\"clearfix\"></div>";
echo "</div>";