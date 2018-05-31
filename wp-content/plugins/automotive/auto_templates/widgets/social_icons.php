<?php
$share_facebook_text    = __("Share link on Facebook", "listings");
$share_google_text      = __("Share link on Google+", "listings");
$share_pinterest_text   = __("Share link on Pinterest", "listings");
$share_twitter_text     = __("Share link on Twitter", "listings");

global $Listing, $lwp_options, $post;

echo $before_widget;
echo (!empty($title) ? $before_title . $title . $after_title : "");

if(isset($lwp_options['social_icons_show']) && $lwp_options['social_icons_show'] == 1){ ?>
	<ul class="social-likes pull-right listing_share" data-url="<?php echo get_permalink($post->ID); ?>" data-title="<?php the_title($post->ID); ?>">
		<li class="facebook" title="<?php echo $share_facebook_text; ?>"></li>
		<li class="plusone" title="<?php echo $share_google_text; ?>"></li>
		<li class="pinterest" title="<?php echo $share_pinterest_text; ?>" data-media="<?php echo (isset($gallery_images[0]) && !empty($gallery_images[0]) ? $Listing->auto_image($gallery_images[0], "full", true) : ""); ?>"></li>
		<li class="twitter" title="<?php echo $share_twitter_text; ?>"></li>
	</ul>
<?php }

echo $after_widget;