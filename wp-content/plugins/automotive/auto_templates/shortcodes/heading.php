<?php
//********************************************
//	Automotive Heading Shortcode
//***********************************************************

$heading = (in_array($heading, array("h1", "h2", "h3", "h4", "h5", "h6")) ? $heading : "h1");

echo "<" . $heading . ">" . $content . "</" . $heading . ">";