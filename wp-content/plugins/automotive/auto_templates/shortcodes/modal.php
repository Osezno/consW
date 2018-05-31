<?php
//********************************************
//	Automotive Modal Shortcode
//***********************************************************

echo "<!-- Modal -->";
echo "<div class=\"modal fade\" id=\"" . esc_attr( $id ) . "\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">";
echo "<div class=\"modal-dialog\">";
echo "<div class=\"modal-content\">";
echo "<div class=\"modal-header\">";
echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">" . __( "Close", "listings" ) . "</span></button>";
echo "<h4 class=\"modal-title\" id=\"myModalLabel\">" . esc_html( $title ) . "</h4>";
echo "</div>";
echo "<div class=\"modal-body\">";
echo "<div>" . do_shortcode( $content ) . "</div>";
echo "</div>";
echo "<div class=\"modal-footer\">";
echo "<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">" . __("Close", "listings") . "</button>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";