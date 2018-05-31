<?php
//********************************************
//	Automotive Progress Bar Shortcode
//***********************************************************
echo '<div class="progressbar ' . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . '">';
echo '<div class="progress margin-bottom-15">';
echo '<div class="progress-bar progress-bar-danger' . ( ! empty( $class ) ? " " . sanitize_html_classes( $class ) : "" ) . '" style="' . ( isset( $color ) && $color != "#c7081b" ? "background-color: " . esc_html( $color ) . ";" : "" ) . '" data-width="' . esc_html( str_replace("%", "", $filled) ) . '">' . do_shortcode( $content ) . '</div>';
echo '</div>';
echo '</div>';