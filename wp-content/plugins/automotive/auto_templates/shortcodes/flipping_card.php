<?php
//********************************************
//	Automotive Flipping Card Shortcode
//*********************************************************** ?>
<div class="flip <?php echo( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ); ?>"
     data-image="<?php echo esc_url( $larger_img ); ?>">
	<?php echo( ! empty( $card_link ) ? '<a href="' . esc_url( $card_link ) . '">' : '' ); ?>
    <div class="card">
        <div class="face front">
            <img class="img-responsive no_border" src="<?php echo esc_url( $image ); ?>"
                 alt="<?php echo esc_html( $alt ); ?>">
        </div>
        <div class="face back">
            <div class='hover_title'><?php echo esc_html( $title ); ?></div>

			<?php echo( ! empty( $link ) ? '<a href="' . esc_url( $link ) . '" ' . ( isset( $target ) && ! empty( $target ) ? "target='" . esc_attr( $target ) . "' " : "" ) . 'class=""><i class="fa fa-link button_icon"></i></a>' : '' ); ?>
			<?php echo( ! empty( $larger_img ) ? '<a href="' . esc_url( $larger_img ) . '" class="fancybox"><i class="fa fa-arrows-alt button_icon"></i></a>' : '' ); ?>
        </div>
    </div>
	<?php echo( ! empty( $card_link ) ? '</a>' : '' ); ?>
</div>