<?php
//********************************************
//	Automotive List Items Widget
//***********************************************************

echo $before_widget;
echo $before_title . $title . $after_title;

echo "<ul class='icons-ul shortcode type-" . $style . "'>";
$field_and_value = explode("&", $fields);

if(!empty($field_and_value) && !empty($field_and_value[0])){
	foreach($field_and_value as $values){
		$explode  = explode("=", $values);
		$text     = $explode[1];

		switch($style){
			case "checkboxes";
				$icon = "<i class=\"fa fa-check\"></i>";
				break;
			default:
				$icon = "<span class=\"red_box\"><i class=\"fa fa-angle-right fa-light\"></i></span>";
				break;
		}

		echo "<li>" . $icon . urldecode($text) . "</li>";
	}
}
echo "</ul>";

echo $after_widget;