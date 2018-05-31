<?php
//********************************************
//	Automotive Video Shortcode
//***********************************************************
global $Listing;

$video_id = $Listing->get_video_id( $url );

if ( $video_id ) {
	echo "<br>";

	if ( $video_id[0] == "youtube" ) {
		echo "<iframe width='" . esc_attr( $width ) . "' height='" . esc_attr( $height ) . "' src=\"https://www.youtube.com/embed/" . $video_id[1] . "\" allowfullscreen></iframe>";
	} elseif ( $video_id[0] == "vimeo" ) {
		echo "<iframe width='" . esc_attr( $width ) . "' height='" . esc_attr( $height ) . "' src=\"https://player.vimeo.com/video/" . $video_id[1] . "\"  allowfullscreen></iframe>";
	} elseif ( $video_id[0] == "self_hosted" ) {
		echo do_shortcode( "[video width='" . esc_attr( $width ) . "' height='" . esc_attr( $height ) . "' mp4=\"" . $video_id[1] . "\"]" );
	}
} else {
	echo __( "Not a valid YouTube/Vimeo link", "listings" ) . "...";
}