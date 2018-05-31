<?php
//********************************************
//	Automotive Recent Posts Shortcode
//***********************************************************

echo "<!--Recent Posts Start-->";
echo "<div class=\"arrow1 pull-right blog_post_controls_" . $rand . " " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "\"></div>";
echo "<ul class=\"recent_blog_posts\" data-controls='blog_post_controls_" . $rand . "' data-showposts='" . (int)$number . "'>";

$args = array( 'posts_per_page' => (int)$posts );

if ( ! empty( $category ) ) {
	$args['category'] = $category;
}

$the_posts = get_posts( $args );

if ( ! empty( $the_posts ) ) {
	foreach ( $the_posts as $single ) {
		$post_content = preg_replace( '/\[[^\]]+\]/', '', $single->post_content );
		$date = date_i18n( get_option( 'date_format' ), strtotime( $single->post_date ) );

		echo "<li>";
		echo "<div class=\"blog-list\">";
		echo "<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12 list-info\">";
		echo "<div class=\"thumb-image\">";
		if ( has_post_thumbnail( $single->ID ) ) {
			echo get_the_post_thumbnail( $single->ID, array(
				100,
				100
			), array( 'class' => 'recent_thumbnail' ) );
		}
		echo "</div>";
		echo "<a href='" . get_permalink( $single->ID ) . "'><h4>" . $single->post_title . "</h4></a>";
		echo "<span>" . $date . " /</span> <span class=\"text-red\">" . $single->comment_count . ( $single->comment_count == 1 ? " " . __( "Comment", "listings" ) . "" : " " . __( "Comments", "listings" ) . "" ) . "</span>";
		echo "<p>" . substr( strip_tags( $post_content ), 0, 115 ) . " " . ( strlen( strip_tags( $post_content ) ) > 112 ? "[...]" : "" ) . "</p>";
		echo "</div>";
		echo "</div>";
		echo "<div class=\"clearfix\"></div>";
		echo "</li>";
	}
}

echo "</ul>";
echo "<!--Recent Posts End-->";