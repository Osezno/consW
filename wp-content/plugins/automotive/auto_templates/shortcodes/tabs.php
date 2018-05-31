<?php
//********************************************
//	Automotive Tabs Shortcode
//***********************************************************

if ( is_array( $GLOBALS['auto_tabs'] ) ) {
	foreach ( $GLOBALS['auto_tabs'] as $tab ) {
		$tabs[]  = '<li' . ( ! isset( $tabs ) ? " class='active'" : "" ) . '><a href="#' . esc_attr( strtolower( str_replace( " ", "-", $tab['title'] ) ) ) . '">' . esc_html( $tab['title'] ) . '</a></li>';
		$panes[] = '<div class="tab-pane' . ( ! isset( $panes ) ? " active" : "" ) . '" id="' . esc_attr( strtolower( str_replace( " ", "-", $tab['title'] ) ) ) . '">' . do_shortcode( $tab['content'] ) . '</div>';
	}

	echo '<ul class="nav nav-tabs tabs_shortcode ' . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . '" role="tablist">' . implode( "\n", $tabs ) . '</ul>';
	echo "<div class=\"tab-content\">";
	echo '' . implode( "\n", $panes );
	echo '</div>' . "\n";
}