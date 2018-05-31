<?php
echo $before_widget;

echo (!empty($title) ? $before_title . $title . $after_title : "");

global $lwp_options;

if(isset($lwp_options['fuel_efficiency_show']) && $lwp_options['fuel_efficiency_show'] == 1){ ?>
	<div class="efficiency-rating text-center padding-vertical-15">
		<h3><?php _e("Fuel Efficiency Rating", "listings"); ?></h3>
		<ul>
			<?php $fuel_icon = (isset($lwp_options['fuel_efficiency_image']) && !empty($lwp_options['fuel_efficiency_image']) ? $lwp_options['fuel_efficiency_image']['url'] : ICON_DIR . "fuel_pump.png"); ?>
			<li class="city_mpg"><small><?php echo (isset($lwp_options['default_value_city']) && !empty($lwp_options['default_value_city']) ? $lwp_options['default_value_city'] : ""); ?>:</small> <strong><?php echo (isset($listing_options['city_mpg']['value']) && !empty($listing_options['city_mpg']['value']) ? $listing_options['city_mpg']['value'] : __("N/A", "listings")); ?></strong></li>
			<li class="fuel"><?php echo (!empty($fuel_icon) ? '<img src="'.$fuel_icon.'" alt="" class="aligncenter">' : ""); ?></li>
			<li class="hwy_mpg"><small><?php echo (isset($lwp_options['default_value_hwy']) && !empty($lwp_options['default_value_hwy']) ? $lwp_options['default_value_hwy'] : ""); ?>:</small> <strong><?php echo (isset($listing_options['highway_mpg']['value']) && !empty($listing_options['highway_mpg']['value']) ? $listing_options['highway_mpg']['value'] : __("N/A", "listings")); ?></strong></li>
		</ul>
		<p><?php echo (isset($lwp_options['fuel_efficiency_text']) ? $lwp_options['fuel_efficiency_text'] : ""); ?></p>
	</div>
<?php }

echo $after_widget;