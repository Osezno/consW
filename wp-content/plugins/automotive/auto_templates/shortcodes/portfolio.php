<?php
//********************************************
//	Automotive Portfolio Shortcode
//***********************************************************
global $lwp_options, $Listing;

echo "<div class='portfolio-container " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "'>";
if($sort_element == 'yes') {
	echo "<div class=\"list_faq clearfix col-lg-12\">";
	echo "<h5>" . esc_html( $sort_text ) . "</h5>";
	echo "<ul class=\"portfolioFilter\">";

	$categories      = explode( ",", $categories );
	$sort_categories = ( $all_category == "yes" ? "<li class=\"active\"><a href=\"#\" data-filter=\"*\" class=\"current filter\">" . __( "All", "listings" ) . "</a></li>" : "" );

	if ( ! empty( $categories ) ) {
		foreach ( $categories as $category ) {
			$safe_category = $Listing->slugify( html_entity_decode( $category ) );

			if(!empty($safe_category)) {
				$sort_categories .= "<li><a href=\"#\" class=\"filter\" data-filter=\"." . $safe_category . "\">" . $category . " </a></li>";
			}
		}
	}

	echo $sort_categories;


	echo "</ul>";
	echo "<div class='clearfix'></div></div>";
}
echo "<div class=\"portfolioContainer portfolio_2\">";
$args = array(
	'post_type'      => 'listings_portfolio',
	'tax_query'      => array(
		array(
			'taxonomy' => 'portfolio_in',
			'field'    => 'term_id',
			'terms'    => $portfolio
		)
	),
	'posts_per_page' => - 1,
	'order'          => $order_by,
	'orderby'        => 'date'
);

$the_query = new WP_Query( $args );

if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
	setup_postdata( $the_query->post );

	$in_categories   = get_the_terms( $the_query->post->ID, "project-type" );
	$categories_list = "";

	if ( ! empty( $in_categories ) ) {
		foreach ( $in_categories as $category ) {
			$categories_list .= ( isset( $category->name ) && ! empty( $category->name ) ? $category->name . ", " : "" );
		}
	}

	$format  = get_post_meta( $the_query->post->ID, "format", true );
	$content = get_post_meta( $the_query->post->ID, "portfolio_content", true );
	$links   = get_post_meta( $the_query->post->ID, "portfolio_links", true );

	// determine image
	if ( $format == "image" && has_post_thumbnail( $the_query->post->ID ) ) {
		$image     = get_the_post_thumbnail( $the_query->post->ID, $img_size, array(
			'class' => 'portfolio image',
			'alt'   => get_post_meta( $the_query->post->ID, "_wp_attachment_image_alt", true )
		) );
		$image_id  = get_post_thumbnail_id( $the_query->post->ID );
		$image_url = wp_get_attachment_url( $image_id );

	} elseif ( $format == "video" ) {
		$video_id  = $Listing->get_video_id($content);
		$video_id  = $video_id[1];

		$image     = "<img src='http://img.youtube.com/vi/" . $video_id . "/hqdefault.jpg' alt='" . __( "youtube thumbnail portfolio image", "listings" ) . "' />";
		$image_url = "http://img.youtube.com/vi/" . $video_id . "/hqdefault.jpg";

	} elseif ( $format == "gallery" ) {
		$image_src = wp_get_attachment_image_src($content[0], "auto_slider");

		$image     = "<img src='" . $image_src[0] . "' alt='" . get_post_meta( $content[0], "_wp_attachment_image_alt", true ) . "' class='" . __( "portfolio image", "listings" ) . "' />";
		$image_url = wp_get_attachment_image_src( $content[0], "full" );

		$image_url = $image_url[0];
	}

	if ( isset( $links[1] ) && ! empty( $links[1] ) ) {
		$image_url = $links[1];
	}

	$the_content = get_the_content();
	$the_content = preg_replace( '/\[[^\]]+\]/', '', $the_content );

	$got_content = strip_tags( $the_content );
	$exploded    = explode( ", ", $categories_list );

	$classes = "";
	foreach ( $exploded as $explode ) {
		$safe_category = $Listing->slugify( html_entity_decode( $explode ) );
		$classes .= $safe_category . " ";
	}

	if ( $format == "video" ) {
		$image_url = "//www.youtube.com/embed/" . $video_id;
	}

	echo "<div class=\"col-md-" . $class . " mix " . sanitize_html_classes( $classes ) . " " . ( $type == "details" ? "margin-bottom-50" : "margin-bottom-30" ) . "\">";
	echo "<div class=\"box clearfix\">";
	echo (isset($lwp_options['portfolio_image_link']) && $lwp_options['portfolio_image_link'] ? "<a class=\"fancybox" . ( $format == "video" ? " fancybox.iframe" : "" ) . "\" href=\"" . $image_url . "\">" : "<a href=\"".get_permalink( $the_query->post->ID )."\">");
	echo $image . "</a>";

	if ( $type == "details" ) {
		echo "<div class='padding-top-25 padding-bottom-10'>\n";
		echo "<h2><a href='" . get_permalink( $the_query->post->ID ) . "'>" . get_the_title() . "</a></h2>\n";
		echo "<span>" . mb_substr( $categories_list, 0, - 2 ) . " </span> </div>\n";
		echo "<p>" . ( strlen( $got_content ) > $length ? mb_substr( $got_content, 0, ( $length - 3 ) ) . "..." : $got_content ) . "</p>\n";
	}

	echo "</div>";
	echo "</div>";

	wp_reset_postdata();
endwhile;

endif;

echo "</div></div>";