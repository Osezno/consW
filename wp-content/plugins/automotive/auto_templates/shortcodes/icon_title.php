<?php
//********************************************
//	Automotive Icon Title Shortcode
//*********************************************************** ?>
<div class="small-block clearfix <?php echo( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ); ?>">
    <h4 class="margin-bottom-25 margin-top-none"><?php echo esc_html( $title ); ?></h4>
    <a href="<?php echo esc_url( $link ); ?>" <?php echo( isset( $target ) && ! empty( $target ) ? "target='" . esc_attr( $target ) . "' " : "" ); ?>>
        <span class="align-center"><i class="<?php echo sanitize_html_classes( $icon ); ?> fa-7x"></i></span>
    </a>
</div>