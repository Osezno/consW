<?php
//********************************************
//	Automotive MailChimp Widget
//***********************************************************

echo $before_widget;
if ( ! empty( $title ) )
	echo "<div class='newsletter'>";
echo $before_title . $title . $after_title;

if ( ! empty( $description ) )
	echo "<p class='description margin-bottom-20'>" . $description . "</p>";

echo "<div class='form_contact'>";
echo "<input type='text' class='email margin-bottom-15' placeholder='" . __("Email Address", "listings") . "'><button class='add_mailchimp button pull-left md-button' data-list='" . $list . "' data-nonce='" . wp_create_nonce("automotive_add_mailchimp") . "'>" . __("Subscribe", "listings") . "</button><br><span class='response'></span>";
echo "</div>";

echo "</div>";
echo $after_widget;