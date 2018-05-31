<?php
//********************************************
//	Automotive Twitter Feed Widget
//***********************************************************

echo $before_widget;
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title;  ?>
	<div class='twitterfeed'></div>
	<script type="text/javascript">
        jQuery(document).ready( function($){
            $('.twitterfeed').tweet({
                modpath: '<?php echo JS_DIR . "twitter/"; ?>',
                count: <?php echo $tweets; ?>,
                loading_text: '<?php _e("Loading twitter feed", "listings"); ?>...',
                username: '<?php echo $username; ?>'
            });
        });
	</script>
<?php
echo $after_widget;