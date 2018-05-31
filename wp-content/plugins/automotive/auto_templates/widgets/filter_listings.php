<?php
//********************************************
//	Automotive Filter Listings Widget
//***********************************************************
global $Listing;

unset($instance['title']);

echo $before_widget;
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title;



$dependancies = $Listing->process_dependancies($_GET);

echo "<div class='dropdowns select-form'>";
foreach($instance as $post_meta => $value){
	if(isset($value) && $value == 1){
		$key     = $Listing->get_single_listing_category($post_meta);

		if(isset($key['slug']) && !empty($key['slug'])){
			$current_option = (isset($_GET[$key['slug']]) && !empty($_GET[$key['slug']]) ? $_GET[$key['slug']] : "");
   if(sanitize_html_class($key['slug']) == "price"){   
    echo '<div class="my-dropdown ' . sanitize_html_class($key['slug']) . '-dropdown max-dropdown">';
			$Listing->listing_dropdown($key, $prefix_text, "listing_filter sidebar_widget_filter", (isset($dependancies[$key['slug']]) && !empty($dependancies[$key['slug']]) ? $dependancies[$key['slug']] : ""), array("current_option" => $current_option));
			echo '</div>';
echo '<br><div id="priceDiv"><p style="margin-bottom: 0px;margin-left:10px;">Price</p><div style="display: inline-flex; width: 100%;    margin-bottom: 6px;    margin-left: 10px;"><div style="width: 20%;float: left; color: #000;"><p style="margin-bottom: 2px;" id="Min' . sanitize_html_class($key['slug']) . '">$10000</p></div><div style="width: 10%;float: left; color: #000;"><p style="margin-bottom: 2px;" >-</p></div><div style="width: 20%;float: left; color: #000;"><p style="margin-bottom: 2px;" id="Max' . sanitize_html_class($key['slug']) . '">$90000</p></div><div style="10%"><strong>USD</strong></div></div>
<input name="' . sanitize_html_class($key['slug']) . '" id="range-priceDiv" class="sidebar_widget_filter" onchange="rangeInput()" data-sort="' . sanitize_html_class($key['slug']) . '" min="10000" max="90000" type="range" multiple value="10000,90000"  step="10000" /></div>';
  }
  elseif(sanitize_html_class($key['slug']) == "year"){   
    echo '<div class="my-dropdown ' . sanitize_html_class($key['slug']) . '-dropdown max-dropdown">';
			$Listing->listing_dropdown($key, $prefix_text, "listing_filter sidebar_widget_filter", (isset($dependancies[$key['slug']]) && !empty($dependancies[$key['slug']]) ? $dependancies[$key['slug']] : ""), array("current_option" => $current_option));
			echo '</div>';
echo '<br><div id="yearDiv"><p style="margin-bottom: 0px;margin-left:10px;">Year</p><div style="display: inline-flex; width: 100%;    margin-left: 10px;margin-bottom: 6px;"><div style="width: 20%;float: left; color: #000; "><p style="margin-bottom: 2px;" id="Min' . sanitize_html_class($key['slug']) . '">2010</p></div><div style="width:10%;">-</div><div style="width: 50%;float: left; color: #000;"><p style="margin-bottom: 2px;" id="Max' . sanitize_html_class($key['slug']) . '">2017</p></div></div>
<input name="' . sanitize_html_class($key['slug']) . '" class="sidebar_widget_filter" onchange="rangeInputYear()" data-sort="' . sanitize_html_class($key['slug']) . '"  type="range" min="2010" max="2017" multiple value="2010,2017" /></div>';
  }
else{
			echo '<div class="my-dropdown ' . sanitize_html_class($key['slug']) . '-dropdown max-dropdown">';
			$Listing->listing_dropdown($key, $prefix_text, "listing_filter sidebar_widget_filter", (isset($dependancies[$key['slug']]) && !empty($dependancies[$key['slug']]) ? $dependancies[$key['slug']] : ""), array("current_option" => $current_option));
			echo '</div>';
           }
		}
	}
}

echo "</div>";
echo "<button class='btn button reset_widget_filter md-button margin-top-10 margin-bottom-none btn-inventory'>" . __("Reset Search Filters", "listings") . "</button>";
echo "<div class='clearfix'></div>";
echo $after_widget;

