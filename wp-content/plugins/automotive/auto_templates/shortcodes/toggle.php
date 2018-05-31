<?php
//********************************************
//	Automotive Toggle Shortcode (FAQ Item)
//***********************************************************

echo "<!--description-->";
echo "<div class=\"panel panel-default padding-top-20 padding-bottom-15\" data-categories=\"" . $categories . "\" itemscope itemtype=\"http://schema.org/Question\">";
echo "<div class=\"panel-heading padding-vertical-10 padding-horizontal-15\">";
echo "<h4 class=\"panel-title padding-left-30\"> <a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse_" . $id . "\" class=\"" . sanitize_html_class( $state ) . "\"><span itemprop=\"text\">" . esc_html( $title ) . "</span></a> </h4>";
echo "</div>";
echo "<div id=\"collapse_" . $id . "\" class=\"panel-collapse " . ( $state == "in" ? "in" : "collapse" ) . "\" style=\"height: " . ( $state == "in" ? "auto" : "0px" ) . ";\">";
echo "<div class=\"panel-body\"> ";
echo "<!--Panel_body-->";
echo "<div class=\"faq_post padding-left-40\">";
echo "<div class=\"post-entry clearfix margin-top-10\"itemprop=\"suggestedAnswer acceptedAnswer\" itemscope itemtype=\"http://schema.org/Answer\">";
echo "<div itemprop=\"text\">" . force_balance_tags( do_shortcode( $content ) ) . "</div>";
echo "</div>";
echo "</div>";
echo "<!--Panel_body--> ";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<!--description--> ";