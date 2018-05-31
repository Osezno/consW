<?php
//********************************************
//	Automotive Testimonial Widget
//***********************************************************

echo $before_widget;
echo $before_title . $title . $after_title;

$field_and_value = explode("&", $fields);
$field_and_value = array_chunk($field_and_value, 2);

$widget = array();

if(!empty($field_and_value) && !empty($field_and_value[0]) && !empty($field_and_value[0][0])){
	foreach($field_and_value as $values){
		$explode  = explode("=", $values[0]);
		$explode2 = explode("=", $values[1]);

		$name = $explode[1];
		$text = $explode2[1];

		array_push($widget, array('name' => urldecode($name), 'content' => urldecode($text)));
	}

	echo testimonial_slider("horizontal", 500, "false", "", $widget);
}

echo $after_widget;