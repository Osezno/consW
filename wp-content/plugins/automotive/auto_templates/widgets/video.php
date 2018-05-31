<?php
global $Listing, $lwp_options;

echo $before_widget;

echo (!empty($title) ? $before_title . $title . $after_title : "");

if(isset($lwp_options['display_vehicle_video']) && $lwp_options['display_vehicle_video'] == 1 && !empty($listing_options['video'])){
	if ( isset( $listing_options['video'] ) && ! empty( $listing_options['video'] ) ) {

		$video_id = $Listing->get_video_id($listing_options['video']);

		if($video_id){
			echo "<br>";

			$youtube_args       = array();
			$is_rel             = (isset($lwp_options['youtube_video_options']['rel']) && !empty($lwp_options['youtube_video_options']['rel']) ? $lwp_options['youtube_video_options']['rel'] : "");
			$is_auto_play       = (isset($lwp_options['youtube_video_options']['auto_play']) && !empty($lwp_options['youtube_video_options']['auto_play']) ? $lwp_options['youtube_video_options']['auto_play'] : "");
			$is_player_controls = (isset($lwp_options['youtube_video_options']['player_controls']) && !empty($lwp_options['youtube_video_options']['player_controls']) ? $lwp_options['youtube_video_options']['player_controls'] : "");
			$is_title_actions   = (isset($lwp_options['youtube_video_options']['title_actions']) && !empty($lwp_options['youtube_video_options']['title_actions']) ? $lwp_options['youtube_video_options']['title_actions'] : "");
			$is_privacy         = (isset($lwp_options['youtube_video_options']['privacy']) && !empty($lwp_options['youtube_video_options']['privacy']) ? $lwp_options['youtube_video_options']['privacy'] : "");

			if(!$is_rel){
				$youtube_args['rel'] = 0;
			}

			if($is_auto_play){
				$youtube_args['autoplay'] = 1;
			}

			if(!$is_player_controls){
				$youtube_args['controls'] = 0;
			}

			if(!$is_title_actions){
				$youtube_args['showinfo'] = 0;
			}

			if($video_id[0] == "youtube"){
				echo "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube" . ($is_privacy ? "-nocookie" : "") . ".com/embed/" . $video_id[1] . "?" . http_build_query($youtube_args) . "\" allowfullscreen></iframe>";
			} elseif($video_id[0] == "vimeo"){
				echo "<iframe width=\"560\" height=\"315\" src=\"https://player.vimeo.com/video/" . $video_id[1] . "\"  allowfullscreen></iframe>";
			} elseif($video_id[0] == "self_hosted"){
				echo do_shortcode("[video width=\"600\" height=\"480\" mp4=\"" . $video_id[1] . "\"]");
			}
		} else {
			echo __( "Not a valid YouTube/Vimeo link", "listings" ) . "...";
		}
	}
}

echo $after_widget;