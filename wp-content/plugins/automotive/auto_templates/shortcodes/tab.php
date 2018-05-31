<?php
//********************************************
//	Automotive Tab Shortcode
//***********************************************************
$x = $GLOBALS['auto_tab_count'];

$GLOBALS['auto_tabs'][ $x ] = array( 'title' => sprintf( $title, $GLOBALS['auto_tab_count'] ), 'content' => $content );
$GLOBALS['auto_tab_count']++;