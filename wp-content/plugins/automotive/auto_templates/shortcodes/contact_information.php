<?php
//********************************************
//	Automotive Contact Information Shortcode
//*********************************************************** ?>
<div
	class="address clearfix margin-right-25 padding-bottom-40 <?php echo( ! empty( $extra_class ) ? $extra_class : "" ); ?>">
	<div class="icon_address">
		<p><i class="fa fa-map-marker"></i><strong><?php _e( "Address", "listings" ); ?>:</strong></p>
	</div>
	<div class="contact_address">
		<p class="margin-bottom-none"><?php echo( ! empty( $company ) ? $company . "<br>" : "" ); ?>
			<?php echo( ! empty( $address ) ? $address : "" ); ?></p>
	</div>
</div>
<div class="address clearfix address_details margin-right-25 padding-bottom-40">
	<ul class="margin-bottom-none">
		<?php echo( ! empty( $phone ) ? '<li><i class="fa fa-phone"></i><strong>' . __( 'Phone', 'listings' ) . ':</strong> <span><a href="tel:' . preg_replace('/\D/', '', $phone) . '">' . $phone . '</a></span></li>' : '' ); ?>
		<?php echo( ! empty( $fax ) ? '<li><i class="fa fa-fax"></i><strong>' . __( 'Fax', 'listings' ) . ':</strong> <span>' . $fax . '</span></li>' : '' ); ?>
		<?php echo( ! empty( $email ) ? '<li><i class="fa fa-envelope-o"></i><strong>' . __( 'Email', 'listings' ) . ':</strong> <a href="mailto:' . $email . '">' . $email . '</a></li>' : '' ); ?>
		<?php echo( ! empty( $web ) ? '<li class="padding-bottom-none"><i class="fa fa-laptop"></i><strong>' . __( 'Web', 'listings' ) . ':</strong> <a href="' . $web . '">' . $web . '</a></li>' : '' ); ?>
	</ul>
</div>

<div class="clearfix"></div>