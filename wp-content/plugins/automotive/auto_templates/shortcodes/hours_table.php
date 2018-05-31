<?php
//********************************************
//  Automotive Hours Table Shortcode
//***********************************************************

echo '<table class="table table-bordered no-border font-12px hours_table ' . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . '">
		<thead>
			<tr>
				<td colspan="2"><strong>' . esc_html( $title ) . '</strong></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>' . __( 'Mon', 'listings' ) . ':</td>
				<td>' . esc_html( $mon ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Tue', 'listings' ) . ':</td>
				<td>' . esc_html( $tue ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Wed', 'listings' ) . ':</td>
				<td>' . esc_html( $wed ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Thu', 'listings' ) . ':</td>
				<td>' . esc_html( $thu ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Fri', 'listings' ) . ':</td>
				<td>' . esc_html( $fri ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Sat', 'listings' ) . ':</td>
				<td>' . esc_html( $sat ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Sun', 'listings' ) . ':</td>
				<td>' . esc_html( $sun ) . '</td>
			</tr>
		</tbody>
		</table>';