<?php
//********************************************
//	Automotive Contact Us Widget
//***********************************************************

$allowed_html = array(
	'a' => array(
		'href' => array(),
		'title' => array()
	),
	'br' => array(),
	'em' => array(),
	'strong' => array(),
);

echo $before_widget;
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title; ?>

	<div class="footer-contact xs-margin-bottom-60">
		<ul>
			<li><i class="fa fa-map-marker"></i> <strong><?php _e("Address", "listings"); ?>:</strong><?php echo wp_kses( $address, $allowed_html ); ?></li>
			<li><i class="fa fa-phone"></i> <strong><?php _e("Phone", "listings"); ?>:</strong><?php echo wp_kses( $phone, $allowed_html ); ?></li>
			<?php if(!empty($email)){ ?>
				<li><i class="fa fa-envelope-o"></i> <strong><?php _e("Email", "listings"); ?>:</strong><a href="mailto:<?php echo sanitize_email($email); ?>"><?php echo sanitize_email($email); ?></a></li>
			<?php } ?>
		</ul>

		<i class="fa fa-location-arrow back_icon"></i>
	</div>
<?php
echo $after_widget;