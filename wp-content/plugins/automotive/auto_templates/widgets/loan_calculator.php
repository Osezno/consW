<?php
//********************************************
//	Automotive Loan Calculator Widget
//***********************************************************
global $lwp_options;

$currency_symbol = (isset($lwp_options['currency_symbol']) && !empty($lwp_options['currency_symbol']) ? $lwp_options['currency_symbol'] : "");

// automatically pickup the price on listings
if(is_singular("listings")){
    global $post;

    $listing_options = get_post_meta($post->ID, "listing_options", true);

    if(!empty($listing_options)){
        $listing_options = unserialize($listing_options);
    }

    $price           = (isset($listing_options['price']['value']) && !empty($listing_options['price']['value']) ? $listing_options['price']['value'] : "");
}

echo $before_widget;
echo "<div class=\"financing_calculator\">";
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title; ?>
	<div class="table-responsive">
		<table class="table no-border no-margin">
			<tbody>
			<tr>
				<td><?php _e("Cost of Vehicle", "listings"); ?> (<?php echo esc_html( $currency_symbol ); ?>):</td>
				<td><input type="text" class="number cost" value="<?php echo esc_attr( $price ); ?>"></td>
			</tr>
			<tr>
				<td><?php _e("Down Payment", "listings"); ?> (<?php echo esc_html( $currency_symbol ); ?>):</td>
				<td><input type="text" class="number down_payment" value="<?php echo esc_attr( $down_payment ); ?>"></td>
			</tr>
			<tr>
				<td><?php _e("Annual Interest Rate", "listings"); ?> (%):</td>
				<td><input type="text" class="number interest" value="<?php echo esc_attr( $rate ); ?>"></td>
			</tr>
			<tr>
				<td><?php _e("Term of Loan in Years", "listings"); ?>:</td>
				<td><input type="text" class="number loan_years" value="<?php echo esc_attr( $loan_years ); ?>"></td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="bi_weekly clearfix">
		<div class="pull-left"><?php _e("Frequency of Payments", "listings"); ?>:</div>
		<?php $default_frequency = (isset($lwp_options['default_frequency']) && !empty($lwp_options['default_frequency']) ? $lwp_options['default_frequency'] : ""); ?>
		<div class="styled pull-right">
			<select class="frequency css-dropdowns">
				<option value='0'<?php selected(1, $default_frequency); ?>><?php _e("Bi-Weekly", "listings"); ?></option>
				<option value='1'<?php selected(2, $default_frequency); ?>><?php _e("Weekly", "listings"); ?></option>
				<option value='2'<?php selected(3, $default_frequency); ?>><?php _e("Monthly", "listings"); ?></option>
			</select>
		</div>
	</div>
	<a class="btn-inventory pull-right calculate"><?php _e("Calculate My Payment", "listings"); ?></a>
	<div class="clear"></div>
	<div class="calculation">
		<div class="table-responsive">
			<table>
				<tbody><tr>
					<td><strong><?php _e("NUMBER OF PAYMENTS", "listings"); ?>:</strong></td>
					<td><strong class="payments">60</strong></td>
				</tr>
				<tr>
					<td><strong><?php _e("PAYMENT AMOUNT", "listings"); ?>:</strong></td>
					<td><strong class="payment_amount"><?php echo esc_html( $currency_symbol ); ?> 89.11</strong></td>
				</tr>
				</tbody></table>
		</div>
	</div>

<?php if(isset($text_below) && !empty($text_below)){
	echo "<p>" . esc_html( $text_below ) . "</p>";
} ?>
	</div>
<?php
echo $after_widget;