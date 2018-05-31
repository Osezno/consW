<?php
//********************************************
//	Automotive Recent Posts Widget
//***********************************************************

echo $before_widget;
echo $before_title . $title . $after_title;

$post_args = array("posts_per_page" => $posts,
                   "order"			=> "DESC",
                   "orderby"		=> "date");

$posts = get_posts( $post_args );

echo "<div class='recent_posts_container'>";
foreach($posts as $single_post){
	echo "<div class=\"side-blog\">";
	if(has_post_thumbnail( $single_post->ID )){
		echo "<a href='" . get_permalink($single_post->ID) . "'>" . get_the_post_thumbnail($single_post->ID, array(50,50), array('class' => 'alignleft')) . "</a>";
	} else if(get_first_post_image($single_post)){
		echo "<a href='" . get_permalink($single_post->ID) . "'><img src='" . get_first_post_image($single_post) . "' class='alignleft wp-post-image'></a>";
	}
	echo "<strong><a href='" . get_permalink($single_post->ID) . "'>" . get_the_title($single_post) . "</a></strong>";
	echo "<p>" . mb_substr(strip_shortcodes(strip_tags($single_post->post_content)), 0, 55) . " " . (strlen(strip_shortcodes(strip_tags($single_post->post_content))) > 55 ? "..." : "") . "</p>";
	echo "</div>";
}
echo "</div>";

echo $after_widget;