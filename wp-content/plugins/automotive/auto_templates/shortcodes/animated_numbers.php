<?php
//********************************************
//	Automotive Animated Numbers Shortcode
//*********************************************************** ?>
<?php echo( ! empty( $icon ) ? '<i class="fa ' . sanitize_html_classes( $icon ) . '"></i>' : '' ); ?>
<span class="animate_number margin-vertical-15 <?php echo( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ); ?>"
	<?php echo( ! empty( $alignment ) && in_array( $alignment, array(
		"left",
		"right",
		"center"
	) ) ? " style='text-align: " . $alignment . "'" : "" ); ?>>
    <?php echo( ! empty( $before_number ) ? esc_html( $before_number ) : "" ); ?>
    <?php echo( ! empty( $number ) ? '<span class="number" data-separator="' . esc_attr( $separator_value ) . '">' . esc_html( $number ) . '</span>' : "" ); ?>
    <?php echo( ! empty( $after_number ) ? esc_html( $after_number ) : "" ); ?>
</span>