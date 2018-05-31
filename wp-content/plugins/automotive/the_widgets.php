<?php
$allowed_widget_tags = "<br><p><b><u><i><div><span><img>";

//********************************************
//	Loan Calculator 
//***********************************************************
if(!class_exists("Loan_Calculator")){
    class Loan_Calculator extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'loan_calculator', 'description' => __('A widget that displays a calculator able to calculate loan payments', 'listings') );  
            $control_ops = array( 'id_base' => 'loan-calculator-widget' );  
            parent::__construct( 'loan-calculator-widget', __('[LISTINGS] Loan Calculator', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {
    		extract( $args );
            $title        = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Financing Calculator', 'listings' ));
            $price        = (isset($instance[ 'price' ]) && (!empty($instance[ 'price' ]) || $instance['price'] == 0) ? $instance[ 'price' ] : 10000 );
            $rate         = (isset($instance[ 'rate' ]) && (!empty($instance[ 'rate' ]) || $instance['rate'] == 0) ? $instance[ 'rate' ] : 7 );
            $down_payment = (isset($instance[ 'down_payment' ]) && (!empty($instance[ 'down_payment' ]) || $instance['down_payment'] == 0 ) ? $instance[ 'down_payment' ] : 1000 );
            $loan_years   = (isset($instance[ 'loan_years' ]) && (!empty($instance[ 'loan_years' ]) || $instance['loan_years'] == 0) ? $instance[ 'loan_years' ] : 5 );
            $text_below   = (isset($instance[ 'text_below' ]) && (!empty($instance[ 'text_below' ]) || $instance['text_below'] == 0) ? $instance[ 'text_below' ] : '' );

            $title        = apply_filters("widget_title", $title);
    		
            global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/loan_calculator",
                array(
                    "title"         => $title,
                    "price"         => $price,
                    "rate"          => $rate,
                    "down_payment"  => $down_payment,
                    "loan_years"    => $loan_years,
                    "text_below"    => $text_below,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) { 
            $title        = (isset($instance[ 'title' ]) && (!empty($instance[ 'title' ]) || $instance['title'] == 0) ? $instance[ 'title' ] : __( 'Financing Calculator', 'listings' ));
            $price        = (isset($instance[ 'price' ]) && (!empty($instance[ 'price' ]) || $instance['price'] == 0) ? $instance[ 'price' ] : 10000);
            $down_payment = (isset($instance[ 'down_payment' ]) && (!empty($instance[ 'down_payment' ]) || $instance['down_payment'] == 0) ? $instance[ 'down_payment' ] : 1000);
            $rate         = (isset($instance[ 'rate' ]) && (!empty($instance[ 'rate' ]) || $instance['rate'] == 0) ? $instance[ 'rate' ] : 7);
            $loan_years   = (isset($instance[ 'loan_years' ]) && (!empty($instance[ 'loan_years' ]) || $instance['loan_years'] == 0) ? $instance[ 'loan_years' ] : 5);
            $text_below   = (isset($instance[ 'text_below' ]) && (!empty($instance[ 'text_below' ]) || $instance['text_below'] == 0) ? $instance[ 'text_below' ] : ''); ?>
            <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_name( 'price' ); ?>"><?php _e( 'Price:', 'listings' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'price' ); ?>" name="<?php echo $this->get_field_name( 'price' ); ?>" type="text" value="<?php echo esc_attr( $price ); ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_name( 'down_payment' ); ?>"><?php _e( 'Down Payment:', 'listings' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'down_payment' ); ?>" name="<?php echo $this->get_field_name( 'down_payment' ); ?>" type="text" value="<?php echo esc_attr( $down_payment ); ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_name( 'rate' ); ?>"><?php _e( 'Rate:', 'listings' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'rate' ); ?>" name="<?php echo $this->get_field_name( 'rate' ); ?>" type="text" value="<?php echo esc_attr( $rate ); ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_name( 'loan_years' ); ?>"><?php _e( 'Loan Years:', 'listings' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'loan_years' ); ?>" name="<?php echo $this->get_field_name( 'loan_years' ); ?>" type="text" value="<?php echo esc_attr( $loan_years ); ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_name( 'text_below' ); ?>"><?php _e( 'Text Below Calculator:', 'listings' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'text_below' ); ?>" name="<?php echo $this->get_field_name( 'text_below' ); ?>" type="text" value="<?php echo esc_attr( $text_below ); ?>" />
            </p>
        <?php
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;
    		
            $instance["title"]        = ( !empty( $new_instance["title"] ) || $new_instance['title'] == 0 ) ? strip_tags( $new_instance["title"], $allowed ) : '';
            $instance["price"]        = ( !empty( $new_instance["price"] ) || $new_instance['price'] == 0 ) ? strip_tags( $new_instance["price"], $allowed ) : '';
            $instance["down_payment"] = ( !empty( $new_instance["down_payment"] ) || $new_instance['down_payment'] == 0 ) ? strip_tags( $new_instance["down_payment"], $allowed ) : '';
            $instance["rate"]         = ( !empty( $new_instance["rate"] ) || $new_instance['rate'] == 0 ) ? strip_tags( $new_instance["rate"], $allowed ) : '';
            $instance["loan_years"]   = ( !empty( $new_instance["loan_years"] ) || $new_instance['loan_years'] == 0 ) ? strip_tags( $new_instance["loan_years"], $allowed ) : '';
            $instance["text_below"]   = ( !empty( $new_instance["text_below"] ) || $new_instance['text_below'] == 0 ) ? strip_tags( $new_instance["text_below"], $allowed ) : '';

            //WMPL
            /**
             * register strings for translation
             */
            if(function_exists('icl_register_string')){
                icl_register_string('Widgets', 'Automotive Widget Loan Calculator Text Below Field', $instance['text_below']);
            }
    		
    		return $instance;
    	}

    }
}

//********************************************
//	Listing Filter
//***********************************************************
if(!class_exists("Filter_Listings")){
    class Filter_Listings extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'filter_listings', 'description' => __('A widget that can filter/search the listings being currently displayed', 'listings') );  
            $control_ops = array( 'id_base' => 'filter-listings-widget' );  
            parent::__construct( 'filter-listings-widget', __('[LISTINGS] Filter Listings', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {
    		extract( $args );

    		$title       = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Search Our Listings', 'listings' ));
    		$prefix_text = (isset($instance[ 'prefix_text' ]) && !empty($instance[ 'prefix_text' ]) ? $instance[ 'prefix_text' ] : __( 'Search by', 'listings' ));

            $title = apply_filters("widget_title", $title);
    		
            global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/filter_listings",
                array(
                    "title" => $title,
                    "prefix_text"   => $prefix_text,
                    "instance"      => $instance,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) {
     	    global $Listing;

     		$filterable = $Listing->get_filterable_listing_categories();
    		
    		$title       = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Search Our Inventory', 'listings' ));
    		$prefix_text = (isset($instance[ 'prefix_text' ]) && !empty($instance[ 'prefix_text' ]) ? $instance[ 'prefix_text' ] : __( 'Search by', 'listings' )); ?>
            <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            <br />
            <label for="<?php echo $this->get_field_name( 'prefix_text' ); ?>"><?php _e( 'Prefix Text:', 'listings' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'prefix_text' ); ?>" name="<?php echo $this->get_field_name( 'prefix_text' ); ?>" type="text" value="<?php echo esc_attr( $prefix_text ); ?>" />
            <br />
            <table>
            <?php
    		foreach($filterable as $filter){
    			$value      = (isset($instance[$filter['slug']]) && $instance[$filter['slug']] == 1 ? "checked='checked' " : null); ?>
    			<tr><td><label for="<?php echo $this->get_field_name( $filter['slug'] ); ?>"><?php echo $filter['singular']; ?></label> </td>
                <td><input id="<?php echo $this->get_field_id( $filter['slug'] ); ?>" name="<?php echo $this->get_field_name( $filter['slug'] ); ?>" type="checkbox" value="1" <?php echo $value; ?>/></td></tr>
                
    		<?php 
    		}
    		
    		echo "</table></p>";
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags, $Listing;

     		$filterable = $Listing->get_filterable_listing_categories();
    		
    		$instance   = array();
    		$allowed    = $allowed_widget_tags;
    		
    		foreach($filterable as $filter){
    			$instance[$filter['slug']] = ( !empty( $new_instance[$filter['slug']] ) ) ? strip_tags( $new_instance[$filter['slug']], $allowed ) : '';
    		}

    		$instance["title"]       = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';
    		$instance["prefix_text"] = ( !empty( $new_instance["prefix_text"] ) ) ? strip_tags( $new_instance["prefix_text"], $allowed ) : '';

    		return $instance;
    	}
    }
}

//********************************************
//	Single Filter
//***********************************************************
if(!class_exists("Single_Filter")){
    class Single_Filter extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'single_filter', 'description' => __('A widget that can filter/search listings and shows a custom amount of options', 'listings') );  
            $control_ops = array( 'id_base' => 'single-filter-widget' );  
            parent::__construct( 'single-filter-widget', __('[LISTINGS] Single Filter', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {
    		extract( $args );
    		
    		$title     = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Search Our Listings', 'listings' ));
    		$number    = (isset($instance[ 'number' ]) && !empty($instance[ 'number' ]) ? $instance[ 'number' ] : __( 10, 'listings' ));
		    $filter    = (isset($instance[ 'filter' ]) && !empty($instance[ 'filter' ]) ? $instance[ 'filter' ] : "");
		    $show_sold = (isset($instance[ 'show_sold' ]) && !empty($instance[ 'show_sold' ]) ? $instance[ 'show_sold' ] : "");

		    $title     = apply_filters("widget_title", $title);
    		
            global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/single_filter",
                array(
                    "title"         => $title,
                    "number"        => $number,
                    "filter"        => $filter,
                    "show_sold"     => $show_sold,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) {
     		global $Listing;

    		$title     = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Filter', 'listings' ));
    		$number    = (isset($instance[ 'number' ]) && !empty($instance[ 'number' ]) ? $instance[ 'number' ] : __( '10', 'listings' ));
            $cfilter   = (isset($instance[ 'filter' ]) && !empty($instance[ 'filter' ]) ? $instance[ 'filter' ] : __( 'years', 'listings' ));
            $show_sold = (isset($instance[ 'show_sold' ]) && !empty($instance[ 'show_sold' ]) ? $instance[ 'show_sold' ] : __( 'no', 'listings' )); ?>
            <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            <br />
            <label for="<?php echo $this->get_field_name( 'number' ); ?>"><?php _e( 'Number of terms to display:', 'listings' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
            <br />
            <label for="<?php echo $this->get_field_name( 'filter' ); ?>"><?php _e( 'Filter:', 'listings' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" class="widefat">
            <?php
     		$filterable = $Listing->get_filterable_listing_categories();

    		foreach($filterable as $filter){
    			$spost_meta = $filter['slug'];
    			echo "<option value='" . $spost_meta . "' " . selected($spost_meta, $cfilter, false) . ">" . $filter['singular'] . "</option>";
    		}
    		?>
            </select>
			<br />
			<label for="<?php echo $this->get_field_name( 'show_sold' ); ?>"><?php _e( 'Include sold values in count:', 'listings' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'show_sold' ); ?>" name="<?php echo $this->get_field_name( 'show_sold' ); ?>" class="widefat">
				<?php
				$values = array("yes" => __("Yes", "listings"), "no" => __("No", "listings"));

				foreach($values as $value => $label){
					echo "<option value='" . $value . "' " . selected($value, $show_sold, false) . ">" . $label . "</option>";
				}
				?>
			</select>
            <?php
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;
    		
    		$instance["title"]     = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';
    		$instance["number"]    = ( !empty( $new_instance["number"] ) ) ? strip_tags( $new_instance["number"], $allowed ) : '';
		    $instance["filter"]    = ( !empty( $new_instance["filter"] ) ) ? strip_tags( $new_instance["filter"], $allowed ) : '';
		    $instance["show_sold"] = ( !empty( $new_instance["show_sold"] ) ) ? strip_tags( $new_instance["show_sold"], $allowed ) : '';
    		
    		return $instance;
    	}
    }
}


//********************************************
//	Contact Us
//***********************************************************
if(!class_exists("Contact_Us")){
    class Contact_Us extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'contact_us', 'description' => __('A widget that displays contact information ', 'listings') );  
            $control_ops = array( 'id_base' => 'contact-us-widget' );  
            parent::__construct( 'contact-us-widget', __('[LISTINGS] Contact Us', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {
    		extract( $args );
    		$title   = apply_filters( 'widget_title', $instance['title'] );
    		$phone   = apply_filters( 'widget_phone', $instance['phone'] );
    		$address = apply_filters( 'widget_address', $instance['address'] );
    		$email   = apply_filters( 'widget_email', $instance['email'] );

            //WMPL
            /**
             * retreive translations
             */
            if (function_exists ( 'icl_translate' )){
                $email = icl_translate('Widgets', 'Automotive Widget Contact Us Email Field', $email);
                $phone = icl_translate('Widgets', 'Automotive Widget Contact Us Phone Field', $phone);
                $address = icl_translate('Widgets', 'Automotive Widget Contact Us Address Field', $address);
            }

    		global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/contact_us",
                array(
                    "title"         => $title,
                    "phone"         => $phone,
                    "address"       => $address,
                    "email"         => $email,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) {
    		$title   = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Contact Us', 'listings' ));
    		$phone   = (isset($instance[ 'phone' ]) && !empty($instance[ 'phone' ]) ? $instance[ 'phone' ] : "");
    		$address = (isset($instance[ 'address' ]) && !empty($instance[ 'address' ]) ? $instance[ 'address' ] : "");
    		$email   = (isset($instance[ 'email' ]) && !empty($instance[ 'email' ]) ? $instance[ 'email' ] : "");
    		?>
    		<p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            <br />
    		<label for="<?php echo $this->get_field_name( 'phone' ); ?>"><?php _e( 'Phone:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>" />
            <br />
            <label for="<?php echo $this->get_field_name( 'address' ); ?>"><?php _e( 'Address:', 'listings' ); ?></label> 
    		<textarea class="widefat" id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>"><?php echo esc_attr( $address ); ?></textarea>
            <br />
            <label for="<?php echo $this->get_field_name( 'email' ); ?>"><?php _e( 'Email:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>" />
    		</p>
    		<?php
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;
    		
    		$instance['title']   = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'], $allowed ) : '';
    		$instance['phone']   = ( !empty( $new_instance['phone'] ) ) ? strip_tags( $new_instance['phone'], $allowed  ) : '';
    		$instance['address'] = ( !empty( $new_instance['address'] ) ) ? strip_tags( $new_instance['address'], $allowed  ) : '';
    		$instance['email']   = ( !empty( $new_instance['email'] ) ) ? strip_tags( $new_instance['email'], $allowed  ) : '';

            //WMPL
            /**
             * register strings for translation
             */
            if(function_exists('icl_register_string')){
                icl_register_string('Widgets', 'Automotive Widget Contact Us Email Field', $instance['email']);
                icl_register_string('Widgets', 'Automotive Widget Contact Us Phone Field', $instance['phone']);
                icl_register_string('Widgets', 'Automotive Widget Contact Us Address Field', $instance['address']);
            }
    		
    		return $instance;
    	}

    }
}

//********************************************
//	Google Maps
//***********************************************************
if(!class_exists("Google_Map")){
    class Google_Map extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'google_map', 'description' => __('A widget that displays a google map of a location', 'listings') );  
            $control_ops = array( 'id_base' => 'google-map-widget' );  
            parent::__construct( 'google-map-widget', __('[LISTINGS] Google Map', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) { 
    	extract( $args );
    		$title     = apply_filters( 'widget_title', $instance['title'] );
    		$type      = apply_filters( 'widget_type', $instance['type'] );
    		$zoom      = apply_filters( 'widget_type', $instance['zoom'] );
    		$latitude  = apply_filters( 'widget_latitude', $instance['latitude'] );
    		$longitude = apply_filters( 'widget_longitude', $instance['longitude'] );
    		$rand_id   = random_string();

    		wp_enqueue_script( 'google-maps' );
    		
            global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/google_map",
                array(
                    "title"         => $title,
                    "type"          => $type,
                    "zoom"          => $zoom,
                    "latitude"      => $latitude,
                    "longitude"     => $longitude,
                    "rand_id"       => $rand_id,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) { 
    		$map_types = array("roadmap", "satellite", "hybrid", "terrain");
    		
    		$title     = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Location', 'listings' ));
    		$latitude  = (isset($instance[ 'latitude' ]) && !empty($instance[ 'latitude' ]) ? $instance[ 'latitude' ] : "");
    		$longitude = (isset($instance[ 'longitude' ]) && !empty($instance[ 'longitude' ]) ? $instance[ 'longitude' ] : "");
    		$type      = (isset($instance[ 'type' ]) && !empty($instance[ 'type' ]) ? $instance[ 'type' ] : "");
    		$zoom      = (isset($instance[ 'zoom' ]) && !empty($instance[ 'zoom' ]) ? $instance[ 'zoom' ] : "8");		
    	?>
        <p>
        <label for="<?php echo $this->get_field_name( "title" ); ?>"><?php _e("Title", "listings"); ?>:</label> 
        <input class="widefat" id="<?php echo $this->get_field_id( "title" ); ?>" name="<?php echo $this->get_field_name( "title" ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <br />
        <label for="<?php echo $this->get_field_name( "latitude" ); ?>"><?php _e("Latitude", "listings"); ?>:</label> 
        <input class="widefat" id="<?php echo $this->get_field_id( "latitude" ); ?>" name="<?php echo $this->get_field_name( "latitude" ); ?>" type="text" value="<?php echo esc_attr( $latitude ); ?>" />
        <br />
        <label for="<?php echo $this->get_field_name( "longitude" ); ?>"><?php _e("Longitude", "listings"); ?>:</label> 
        <input class="widefat" id="<?php echo $this->get_field_id( "longitude" ); ?>" name="<?php echo $this->get_field_name( "longitude" ); ?>" type="text" value="<?php echo esc_attr( $longitude ); ?>" />
        <br />
        <label for="<?php echo $this->get_field_name( "zoom" ); ?>"><?php _e("Zoom", "listings"); ?>: <span class='zoom_level'><?php echo $zoom; ?></span></label> 
        <input id="<?php echo $this->get_field_id( "zoom" ); ?>" name="<?php echo $this->get_field_name( "zoom" ); ?>" type="hidden" value="<?php echo esc_attr( $zoom ); ?>" class="zoom_text" />
        <div class="zoom_slider"></div>
        <script type="text/javascript">
    	jQuery(document).ready( function($) {
    		$(".zoom_slider").slider({
    			max: 21,
    			min: 0,
    			value: <?php echo $zoom; ?>,
    			slide: function( event, ui ) {
    				$( ".zoom_text" ).val( ui.value );
    				$( ".zoom_level" ).text( ui.value );
    			}
    		});
    	});
    	</script>
        <br />
        <label for="<?php echo $this->get_field_name( "type" ); ?>"><?php _e("Map Type", "listings"); ?>: </label>
        <select id="<?php echo $this->get_field_id( "type" ); ?>" name="<?php echo $this->get_field_name( "type" ); ?>" class="widefat">
        <?php
    	foreach($map_types as $map_type){
    		echo ($map_type != $type ? "<option value='" . $map_type . "'>" . ucwords($map_type) . "</option>" : "<option value='" . $map_type . "' selected='selected'>" . ucwords($map_type) . "</option>");
    	}
    	?>
        </select>
        <br />
        
        </p>
        <?php
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;	
    		
    		$instance["title"]     = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';
    		$instance["latitude"]  = ( !empty( $new_instance["latitude"] ) ) ? strip_tags( $new_instance["latitude"], $allowed ) : '';
    		$instance["longitude"] = ( !empty( $new_instance["longitude"] ) ) ? strip_tags( $new_instance["longitude"], $allowed ) : '';
    		$instance["type"]      = ( !empty( $new_instance["type"] ) ) ? strip_tags( $new_instance["type"], $allowed ) : '';
    		$instance["zoom"]      = ( !empty( $new_instance["zoom"] ) ) ? strip_tags( $new_instance["zoom"], $allowed ) : '';
    		
    		return $instance;
    	}

    }
}

//********************************************
//	MailChimp Newsletter
//***********************************************************
if(!class_exists("Mail_Chimp")){
    class Mail_Chimp extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'mail_chimp', 'description' => __('A widget that displays a form for users to register for a mailchimp newsletter', 'listings') );  
            $control_ops = array( 'id_base' => 'mail-chimp-widget' );  
            parent::__construct( 'mail-chimp-widget', __('[LISTINGS] Mail Chimp', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {				
    		extract( $args );
    		$title       = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Newsletter', 'listings' ));
    		$description = (isset($instance[ 'description' ]) && !empty($instance[ 'description' ]) ? $instance[ 'description' ] : "");
    		$list        = (isset($instance[ 'list' ]) && !empty($instance[ 'list' ]) ? $instance[ 'list' ] : "");

    		$title       = apply_filters("widget_title", $title);

            //WMPL
            /**
             * retreive translations
             */
            if (function_exists ( 'icl_translate' )){
                $description = icl_translate('Widgets', 'Automotive Widget MailChimp Description Field', $description);
            }
    		
    		global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/mailchimp",
                array(
                    "title"         => $title,
                    "description"   => $description,
                    "list"          => $list,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) {
	        global $lwp_options;

    		$title       = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Newsletter', 'listings' ));
    		$description = (isset($instance[ 'description' ]) && !empty($instance[ 'description' ]) ? $instance[ 'description' ] : "" );
    		$list        = (isset($instance[ 'list' ]) && !empty($instance[ 'list' ]) ? $instance[ 'list' ] : "" );

	        $api_key     = (isset($lwp_options['mailchimp_api_key']) && !empty($lwp_options['mailchimp_api_key']) ? $lwp_options['mailchimp_api_key'] : "");
    		?>
    		<p>
                <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_name( 'description' ); ?>"><?php _e( 'Description:', 'listings' ); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" ><?php echo esc_attr( $description ); ?></textarea>
            </p>

            <p>
                <?php if(!empty($api_key)){ ?>
                    <label for="<?php echo $this->get_field_name( 'list' ); ?>"><?php _e( 'List:', 'listings' ); ?></label>
                    <select id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>" class="widefat">
                    <?php
                    require_once(LISTING_HOME . "/classes/mailchimp/MCAPI.class.php");

                    $api       = new MCAPI($api_key);
                    $list_list = $api->lists();

                    if(!empty($list_list['data'])){
                        foreach($list_list['data'] as $lists){
                            echo ($lists['id'] == $list ? "<option value='" . $lists['id'] . "' selected='selected'>" . $lists['name'] . "</option>" : "<option value='" . $lists['id'] . "'>" . $lists['name'] . "</option>");
                        }
                    }
                    ?>
                    </select>
                <?php } else {
                    echo "<strong>" . __("Add your MailChimp API Key under Listing Options >> API Keys >> MailChimp API to choose a newsletter.", "listings") . "</strong>";
                } ?>
            </p>
            <?php
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;
    		
    		$instance["title"]       = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';
    		$instance["description"] = ( !empty( $new_instance["description"] ) ) ? strip_tags( $new_instance["description"], $allowed ) : '';
    		$instance["list"]        = ( !empty( $new_instance["list"] ) ) ? strip_tags( $new_instance["list"], $allowed ) : '';
    		
            //WMPL
            /**
             * register strings for translation
             */
            if(function_exists('icl_register_string')){
                icl_register_string('Widgets', 'Automotive Widget MailChimp Description Field', $instance['description']);
            }

    		return $instance;
    	}

    }
}

//********************************************
//	Twitter
//***********************************************************
if(!class_exists("Twitter_Feed")){
    class Twitter_Feed extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'twitter_feed', 'description' => __('A widget that displays a feed from your twitter', 'listings') );  
            $control_ops = array( 'id_base' => 'twitter-feed-widget' );  
            parent::__construct( 'twitter-feed-widget', __('[LISTINGS] Twitter Feed', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {
    		extract( $args );
    		$title    = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Newsletter', 'listings' ));
    		$username = (isset($instance[ 'username' ]) && !empty($instance[ 'username' ]) ? $instance[ 'username' ] : "themesuite" );
    		$tweets   = (isset($instance[ 'tweets' ]) && !empty($instance[ 'tweets' ]) ? $instance[ 'tweets' ] : 2 );

    		$title    = apply_filters("widget_title", $title);
    		
    		wp_enqueue_script('twitter_feed');
    		wp_enqueue_script('twitter_tweet');

            global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/twitter_feed",
                array(
                    "title"         => $title,
                    "username"      => $username,
                    "tweets"        => $tweets,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) {
    		$title    = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Twitter Feed', 'listings' ));
    		$username = (isset($instance[ 'username' ]) && !empty($instance[ 'username' ]) ? $instance[ 'username' ] : "" );
    		$tweets   = (isset($instance[ 'tweets' ]) && !empty($instance[ 'tweets' ]) ? $instance[ 'tweets' ] : "" );
    		?>
    		<p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            <br />
            <label for="<?php echo $this->get_field_name( 'username' ); ?>"><?php _e( 'Username:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>" />
            <br />
            <label for="<?php echo $this->get_field_name( 'tweets' ); ?>"><?php _e( 'Number of tweets:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'tweets' ); ?>" name="<?php echo $this->get_field_name( 'tweets' ); ?>" type="text" value="<?php echo esc_attr( $tweets ); ?>" />
            </p>
            
            <?php
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;
    		
    		$instance["title"]    = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';
    		$instance["username"] = ( !empty( $new_instance["username"] ) ) ? strip_tags( $new_instance["username"], $allowed ) : '';
    		$instance["tweets"] = ( !empty( $new_instance["tweets"] ) ) ? strip_tags( $new_instance["tweets"], $allowed ) : '';
    		
    		return $instance;
    	}

    }
}

//********************************************
//	Custom recent posts
//***********************************************************
if(!class_exists("Recent_Posts")){
    class Recent_Posts extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'recent_posts', 'description' => __('A widget that can displays your posts with the featured image.', 'listings') );  
            $control_ops = array( 'id_base' => 'recent-posts-widget' );  
            parent::__construct( 'recent-posts-widget', __('[LISTINGS] Recent Posts', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {
    		extract( $args );
    		
    		$title = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Recent Posts', 'listings' ));
    		$posts = (isset($instance[ 'posts' ]) && !empty($instance[ 'posts' ]) ? $instance[ 'posts' ] : __( 5, 'listings' ));

            $title = apply_filters("widget_title", $title);

            global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/recent_posts",
                array(
                    "title"         => $title,
                    "posts"         => $posts,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) {
    		$title = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Recent Posts', 'listings' ));
    		$posts = (isset($instance[ 'posts' ]) && !empty($instance[ 'posts' ]) ? $instance[ 'posts' ] : __( 5, 'listings' ));
    		
    		?>
            <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            <br />
            <label for="<?php echo $this->get_field_name( 'posts' ); ?>"><?php _e( 'Number of posts:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'posts' ); ?>" name="<?php echo $this->get_field_name( 'posts' ); ?>" type="text" value="<?php echo esc_attr( $posts ); ?>" />
            </p>
            <?php
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;
    		
    		$instance["title"] = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';
    		$instance["posts"] = ( !empty( $new_instance["posts"] ) ) ? strip_tags( $new_instance["posts"], $allowed ) : '';
    		
    		return $instance;
    	}
    }
}


//********************************************
//	Recent Listings Widget
//***********************************************************
if(!class_exists("Recent_Listings")){
    class Recent_Listings extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'recent_listings', 'description' => __('A widget that can show a custom amount of options', 'listings') );  
            $control_ops = array( 'id_base' => 'recent-listings-widget' );  
            parent::__construct( 'recent-listings-widget', __('[LISTINGS] Recent Listings', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {
    		extract( $args );
    		
    		$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Recent Listings', 'listings' ));
    		$number = (isset($instance[ 'number' ]) && !empty($instance[ 'number' ]) ? $instance[ 'number' ] : 3);

    		$title  = apply_filters("widget_title", $title);

            global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/recent_listings",
                array(
                    "title"         => $title,
                    "number"        => $number,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) {
    		$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Recent Listings', 'listings' ));
    		$number = (isset($instance[ 'number' ]) && !empty($instance[ 'number' ]) ? $instance[ 'number' ] : 2); ?>

    		<p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>

    		<p>
            <label for="<?php echo $this->get_field_name( 'number' ); ?>"><?php _e( 'Number of Listings:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
            </p>
            <?php 
    		
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;
    		
    		$instance["title"]  = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';
    		$instance["number"] = ( !empty( $new_instance["number"] ) ) ? strip_tags( $new_instance["number"], $allowed ) : '';
    		
    		return $instance;
    	}
    }
}

//********************************************
//	 Contact Form
//***********************************************************
if(!class_exists("Contact_Form")){
    class Contact_Form extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'contact_form', 'description' => __('A widget that displays a contact form and emails it to the email specified in the Contact Settings (under the Theme Options).', 'listings') );  
            $control_ops = array( 'id_base' => 'contact-form-widget' );  
            parent::__construct( 'contact-form-widget', __('[LISTINGS] Contact Form', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {
    		extract($args);

    		wp_enqueue_script( 'contact_form' );
    		
    		$title   = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Get In Touch', 'listings' ));
    		$name    = (isset($instance[ 'name' ]) && !empty($instance[ 'name' ]) ? $instance[ 'name' ] : __('Name', 'listings')); 
    		$email   = (isset($instance[ 'email' ]) && !empty($instance[ 'email' ]) ? $instance[ 'email' ] : __('Email', 'listings')); 
    		$message = (isset($instance[ 'message' ]) && !empty($instance[ 'message' ]) ? $instance[ 'message' ] : __('Message', 'listings'));
    		$button  = (isset($instance[ 'button' ]) && !empty($instance[ 'button' ]) ? $instance[ 'button' ] : __('Send', 'listings'));
            $title   = apply_filters("widget_title", $title);

            //WMPL
            /**
             * retreive translations
             */
            if (function_exists ( 'icl_translate' )){
                $name = icl_translate('Widgets', 'Automotive Widget Contact Form Name Field', $name);
                $email = icl_translate('Widgets', 'Automotive Widget Contact Form Email Field', $email);
                $message = icl_translate('Widgets', 'Automotive Widget Contact Form Message Field', $message);
                $button = icl_translate('Widgets', 'Automotive Widget Contact Form Button Field', $button);
            }

            global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/contact_form",
                array(
                    "title"         => $title,
                    "name"          => $name,
                    "email"         => $email,
                    "message"       => $message,
                    "button"        => $button,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) {
    		$title   = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Get In Touch', 'listings' ));
    		$name    = (isset($instance[ 'name' ]) && !empty($instance[ 'name' ]) ? $instance[ 'name' ] : __('Name', 'listings')); 
    		$email   = (isset($instance[ 'email' ]) && !empty($instance[ 'email' ]) ? $instance[ 'email' ] : __('Email', 'listings')); 
    		$message = (isset($instance[ 'message' ]) && !empty($instance[ 'message' ]) ? $instance[ 'message' ] : __('Message', 'listings'));
    		$button  = (isset($instance[ 'button' ]) && !empty($instance[ 'button' ]) ? $instance[ 'button' ] : __('Send', 'listings')); ?>	

    		<p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>

    		<p>
            <label for="<?php echo $this->get_field_name( 'name' ); ?>"><?php _e( 'Name Placeholder:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" />
            </p>

    		<p>
            <label for="<?php echo $this->get_field_name( 'email' ); ?>"><?php _e( 'Email Placeholder:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>" />
            </p>

    		<p>
            <label for="<?php echo $this->get_field_name( 'message' ); ?>"><?php _e( 'Message Placeholder:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'message' ); ?>" name="<?php echo $this->get_field_name( 'message' ); ?>" type="text" value="<?php echo esc_attr( $message ); ?>" />
            </p>

    		<p>
            <label for="<?php echo $this->get_field_name( 'button' ); ?>"><?php _e( 'Button Text:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'button' ); ?>" name="<?php echo $this->get_field_name( 'button' ); ?>" type="text" value="<?php echo esc_attr( $button ); ?>" />
            </p>
    	<?php	
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;
    		
    		$instance["title"]   = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';
    		$instance["name"]    = ( !empty( $new_instance["name"] ) ) ? strip_tags( $new_instance["name"], $allowed ) : '';
    		$instance["email"]   = ( !empty( $new_instance["email"] ) ) ? strip_tags( $new_instance["email"], $allowed ) : '';
    		$instance["message"] = ( !empty( $new_instance["message"] ) ) ? strip_tags( $new_instance["message"], $allowed ) : '';
    		$instance["button"]  = ( !empty( $new_instance["button"] ) ) ? strip_tags( $new_instance["button"], $allowed ) : '';
            
            //WMPL
            /**
             * register strings for translation
             */
            if(function_exists('icl_register_string')){
                icl_register_string('Widgets', 'Automotive Widget Contact Form Name Field', $instance['name']);
                icl_register_string('Widgets', 'Automotive Widget Contact Form Email Field', $instance['email']);
                icl_register_string('Widgets', 'Automotive Widget Contact Form Message Field', $instance['message']);
                icl_register_string('Widgets', 'Automotive Widget Contact Form Button Field', $instance['button']);
            }
    		
    		return $instance;
    	}
    }
}

//********************************************
//	Testimonial Widget
//***********************************************************
if(!class_exists("Testimonial_Slider")){
    class Testimonial_Slider extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'testimonial_slider', 'description' => __('A widget that can slide through customer testimonials', 'listings') );  
            $control_ops = array( 'id_base' => 'testimonial-slider-widget' );  
            parent::__construct( 'testimonial-slider-widget', __('[LISTINGS] Testimonial Slider', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {
    		extract( $args );
    		
    		$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Testimonials', 'listings' ));
    		$fields = (isset($instance[ 'fields' ]) && !empty($instance[ 'fields' ]) ? $instance[ 'fields' ] : "");

    		$title  = apply_filters("widget_title", $title);
    		
            global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/testimonial",
                array(
                    "title"         => $title,
                    "fields"        => $fields,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) {
    		$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Testimonials', 'listings' ));
    		$fields = (isset($instance[ 'fields' ]) && !empty($instance[ 'fields' ]) ? $instance[ 'fields' ] : "");
    		
    		$id = random_string();
    		?>
            <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            <input type="hidden" value="<?php echo $fields; ?>" name="<?php echo $this->get_field_name( 'fields' ); ?>" class='testimonial_fields' id="<?php echo $id; ?>" />
            <br />
            </p>
            
            <span class='edit_testimonials btn button' data-id="<?php echo $id; ?>"><?php _e("Edit Testimonials", "listings"); ?></span>
                    
            <?php
    		
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;
    		
    		$instance["title"]  = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';
    		$instance["fields"] = ( !empty( $new_instance["fields"] ) ) ? strip_tags( $new_instance["fields"], $allowed ) : '';
    		
    		return $instance;
    	}
    }
}

//********************************************
//	^ Modal for Testimonial Widget ^
//***********************************************************
function testimonial_window(){
	echo "<div id='testimonial_window' title='" . __("Testimonials", "listings") . "'>";
	echo "<form id='testimonial_form'>";
	echo "<table class='load' style='border: 0; width: 100%;'>";
	
	echo "</table>";
	echo "</form>";
	echo "</div>";
}
add_action( 'admin_footer', 'testimonial_window');


//********************************************
//	Process Fields
//***********************************************************
function testimonial_widget_fields(){
	$value = $_POST['value'];
	
	if(isset($value) && !empty($value)){		
		$field_and_value = explode("&", $value);
		$field_and_value = array_chunk($field_and_value, 2);
		
		$widget = array();
		$i      = 1;
		
		foreach($field_and_value as $values){
			$explode  = explode("=", $values[0]);
			$explode2 = explode("=", $values[1]);
			
			$name = $explode[1];
			$text = $explode2[1];
			
			echo "<tr><td>Name: </td><td> <input type='text' name='testimonial_name_" . $i . "' value='" . urldecode($name) . "'>&nbsp; <i class='fa fa-times remove_testimonial'></i></td></tr>";
			echo "<tr><td>Text: </td><td> <textarea name='testimonial_text_" . $i . "'>" . urldecode($text) . "</textarea></td></tr>";
			$i++;
		}
	} else {
		echo "<tr><td>Name: </td><td> <input type='text' name='testimonial_name_1'></td></tr>";
		echo "<tr><td>Text: </td><td> <textarea name='testimonial_text_1'></textarea></td></tr>";
	}
	
	die;
}
add_action("wp_ajax_testimonial_widget_fields", "testimonial_widget_fields");
add_action("wp_ajax_nopriv_testimonial_widget_fields", "testimonial_widget_fields");

//********************************************
//	List Item Shortcode
//***********************************************************
if(!class_exists("List_Items")){
    class List_Items extends WP_Widget {

    	public function __construct() {
    		$widget_ops = array( 'classname' => 'list_items', 'description' => __('A widget that can create a list from a bunch of items', 'listings') );  
            $control_ops = array( 'id_base' => 'list-items-widget' );  
            parent::__construct( 'list-items-widget', __('[LISTINGS] List Items', 'listings'), $widget_ops, $control_ops );
    	}

    	public function widget( $args, $instance ) {
    		extract( $args );
    		
    		$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'List', 'listings' ));
    		$style  = trim(isset($instance[ 'style' ]) && !empty($instance[ 'style' ]) ? $instance[ 'style' ] : "");
    		$fields = (isset($instance[ 'fields' ]) && !empty($instance[ 'fields' ]) ? $instance[ 'fields' ] : "");

    		$title  = apply_filters("widget_title", $title);
    		
            global $Listing_Template;

            echo $Listing_Template->locate_template( "widgets/list_items",
                array(
                    "title"         => $title,
                    "style"         => $style,
                    "fields"        => $fields,
                    "before_widget" => $before_widget,
                    "after_widget"  => $after_widget,
                    "before_title"  => $before_title,
                    "after_title"   => $after_title
                )
            );
    	}

     	public function form( $instance ) {
    		$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'List', 'listings' ));
    		$style  = (isset($instance[ 'style' ]) && !empty($instance[ 'style' ]) ? $instance[ 'style' ] : "");
    		$fields = (isset($instance[ 'fields' ]) && !empty($instance[ 'fields' ]) ? $instance[ 'fields' ] : "");
    		
    		$id = random_string(); ?>
            <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'listings' ); ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            <br />
            <label for="<?php echo $this->get_field_name( 'style' ); ?>"><?php _e( 'Style:', 'listings' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
            <?php $styles = array("arrows", "checkboxes");
    		foreach($styles as $single_style){
    			echo "<option value='" . $single_style . " " . selected($style, $single_style) . "'>" . ucwords($single_style) . "</option>";
    		}
    		?>
            </select>
            <input type="hidden" value="<?php echo $fields; ?>" name="<?php echo $this->get_field_name( 'fields' ); ?>" class='list_fields' id="<?php echo $id; ?>" />
            <br />
            </p>
            
            <span class='edit_list btn button' data-id="<?php echo $id; ?>"><?php _e("Edit List", "listings"); ?></span>
                    
            <?php
    		
    	}

    	public function update( $new_instance, $old_instance ) {
            global $allowed_widget_tags;

    		$instance = array();
    		$allowed  = $allowed_widget_tags;
    		
    		$instance["title"]  = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';
    		$instance["style"]  = ( !empty( $new_instance["style"] ) ) ? strip_tags( $new_instance["style"], $allowed ) : '';
    		$instance["fields"] = ( !empty( $new_instance["fields"] ) ) ? strip_tags( $new_instance["fields"], $allowed ) : '';
    		
    		return $instance;
    	}
    }
}

//********************************************
//	^ Modal for List Widget ^
//***********************************************************
function list_window(){
	echo "<div id='list_window' title='List'>";
	echo "<form id='list_form'>";
	echo "<table class='load'>";
	
	echo "</table>";
	echo "</form>";
	echo "</div>";
}
add_action( 'admin_footer', 'list_window');

//********************************************
//	Process Fields
//***********************************************************
function list_widget_fields(){
	$value = $_POST['value'];
	
	if(isset($value) && !empty($value)){		
		$field_and_value = explode("&", $value);
		
		foreach($field_and_value as $values){
			$explode  = explode("=", $values);
			
			$text     = $explode[1];
						
			echo "<tr><td>" . __("List Item", "listings") . ": </td><td> <input type='text' name='list_item' value='" . urldecode($text) . "'>&nbsp; <i class='fa fa-times remove_list_item'></i></td></tr>";
		}
	} else {
		echo "<tr><td>" . __("List Item", "listings") . ": </td><td> <input type='text' name='list_item'>&nbsp; <i class='fa fa-times remove_list_item'></i></td></tr>";
	}
	
	die;
}
add_action("wp_ajax_list_widget_fields", "list_widget_fields");
add_action("wp_ajax_nopriv_list_widget_fields", "list_widget_fields");


class Extended_Categories extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'extended_widget_categories', 'description' => __( "A list or dropdown of categories.", 'listings' ) );
		parent::__construct('extended_categories', __('[LISTINGS] Categories', 'listings'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Categories', 'listings' ) : $instance['title'], $instance, $this->id_base );

		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
		$d = ! empty( $instance['dropdown'] ) ? '1' : '0';

        global $Listing_Template;

        echo $Listing_Template->locate_template( 'widgets/categories',
            array(
                "title"         => $title,
                "c"             => $c,
                "h"             => $h,
                "d"             => $d,
                "before_widget" => $before_widget,
                "after_widget"  => $after_widget,
                "before_title"  => $before_title,
                "after_title"   => $after_title
            )
        );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'listings' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown', 'listings' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts', 'listings' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy', 'listings' ); ?></label></p>
<?php
	}

}

if(!class_exists("Listing_Info_Table")){
	class Listing_Info_Table extends WP_Widget {

		public function __construct() {
			$widget_ops = array( 'classname' => 'listing_info', 'description' => __('A widget that displays the listing info in a table.', 'listings') );
			$control_ops = array( 'id_base' => 'listing-info-table' );
			parent::__construct( 'listing-info-table', __('[LISTINGS] Listing Info Table', 'listings'), $widget_ops, $control_ops );
		}

		public function widget( $args, $instance ) {
			extract( $args );

			$title = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : '');
			$title = apply_filters("widget_title", $title);

			if(is_singular("listings")) {
				global $Listing_Template, $Listing, $post;

				$post_meta = $Listing->get_listing_meta($post->ID);

				echo $Listing_Template->locate_template( "widgets/listing_info_table",
					array(
						"title"         => $title,
						"post_meta"     => $post_meta,
						"before_widget" => $before_widget,
						"after_widget"  => $after_widget,
						"before_title"  => $before_title,
						"after_title"   => $after_title
					)
				);
			}
		}

		public function form( $instance ) {
			$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : "");

			echo "<p>" . __("This widget will only show up on in the Single Listing Sidebar on single listing pages.", "listings") . "</p>"; ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'listings' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			global $allowed_widget_tags;

			$instance = array();
			$allowed  = $allowed_widget_tags;

			$instance["title"]  = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';

			return $instance;
		}
	}
}

if(!class_exists("Listing_Woo_Integration")){
	class Listing_Woo_Integration extends WP_Widget {

		public function __construct() {
			$widget_ops = array( 'classname' => 'listing_woo', 'description' => __('A widget the WooCommerce "Add to Cart" button if enabled.', 'listings') );
			$control_ops = array( 'id_base' => 'listing-woo-integration' );
			parent::__construct( 'listing-woo-integration', __('[LISTINGS] Listing WooCommerce Integration', 'listings'), $widget_ops, $control_ops );
		}

		public function widget( $args, $instance ) {
			extract( $args );

			$title = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : '');
			$title = apply_filters("widget_title", $title);

			if(is_singular("listings")) {
				global $Listing_Template, $Listing, $post;

				$post_meta = $Listing->get_listing_meta($post->ID);

				echo $Listing_Template->locate_template( "widgets/woo_integration",
					array(
						"title"         => $title,
						"post_meta"     => $post_meta,
						"before_widget" => $before_widget,
						"after_widget"  => $after_widget,
						"before_title"  => $before_title,
						"after_title"   => $after_title
					)
				);
			}
		}

		public function form( $instance ) {
			$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : "");

			echo "<p>" . __("This widget will only show up on in the Single Listing Sidebar on single listing pages.", "listings") . "</p>";
			echo "<p>" . __("If you are unsure how to set this up we have a <a href='https://www.youtube.com/watch?v=g_5bclI1T2E' target='_blank'>video</a> showing how.", "listings") . "</p>"; ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'listings' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			global $allowed_widget_tags;

			$instance = array();
			$allowed  = $allowed_widget_tags;

			$instance["title"]  = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';

			return $instance;
		}
	}
}

if(!class_exists("Listing_Fuel_Efficiency")){
	class Listing_Fuel_Efficiency extends WP_Widget {

		public function __construct() {
			$widget_ops  = array( 'classname' => 'fuel_efficiency', 'description' => __('A widget that displays a vehicles fuel efficiency.', 'listings') );
			$control_ops = array( 'id_base' => 'listing-fuel-efficiency' );
			parent::__construct( 'listing-fuel-efficiency', __('[LISTINGS] Listing Fuel Efficiency', 'listings'), $widget_ops, $control_ops );
		}

		public function widget( $args, $instance ) {
			extract( $args );

			$title = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : '');
			$title = apply_filters("widget_title", $title);

			if(is_singular("listings")) {
				global $Listing_Template, $Listing, $post;

				$post_meta          = $Listing->get_listing_meta($post->ID);
				$listing_options    = (isset($post_meta['listing_options']) && !empty($post_meta['listing_options']) ? $post_meta['listing_options'] : "");

				echo $Listing_Template->locate_template( "widgets/fuel_efficiency",
					array(
						"title"             => $title,
						"listing_options"   => $listing_options,
						"before_widget"     => $before_widget,
						"after_widget"      => $after_widget,
						"before_title"      => $before_title,
						"after_title"       => $after_title
					)
				);
			}
		}

		public function form( $instance ) {
			$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : "");

			echo "<p>" . __("This widget will only show up on in the Single Listing Sidebar on single listing pages.", "listings") . "</p>"; ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'listings' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			global $allowed_widget_tags;

			$instance = array();
			$allowed  = $allowed_widget_tags;

			$instance["title"]  = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';

			return $instance;
		}
	}
}

if(!class_exists("Listing_Video")){
	class Listing_Video extends WP_Widget {

		public function __construct() {
			$widget_ops  = array( 'classname' => 'listing_video', 'description' => __('A widget that displays the listing video.', 'listings') );
			$control_ops = array( 'id_base' => 'listing-video' );
			parent::__construct( 'listing-video', __('[LISTINGS] Listing Video', 'listings'), $widget_ops, $control_ops );
		}

		public function widget( $args, $instance ) {
			extract( $args );

			$title = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : '');
			$title = apply_filters("widget_title", $title);

			if(is_singular("listings")) {
				global $Listing_Template, $Listing, $post;

				$post_meta          = $Listing->get_listing_meta($post->ID);
				$listing_options    = (isset($post_meta['listing_options']) && !empty($post_meta['listing_options']) ? $post_meta['listing_options'] : "");

				echo $Listing_Template->locate_template( "widgets/video",
					array(
						"title"             => $title,
						"listing_options"   => $listing_options,
						"before_widget"     => $before_widget,
						"after_widget"      => $after_widget,
						"before_title"      => $before_title,
						"after_title"       => $after_title
					)
				);
			}
		}

		public function form( $instance ) {
			$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : "");

			echo "<p>" . __("This widget will only show up on in the Single Listing Sidebar on single listing pages.", "listings") . "</p>"; ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'listings' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			global $allowed_widget_tags;

			$instance = array();
			$allowed  = $allowed_widget_tags;

			$instance["title"]  = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';

			return $instance;
		}
	}
}


if(!class_exists("Listing_Social_Icons")){
	class Listing_Social_Icons extends WP_Widget {

		public function __construct() {
			$widget_ops  = array( 'classname' => 'listing_social_icons', 'description' => __('A widget that displays social icons to share the listing.', 'listings') );
			$control_ops = array( 'id_base' => 'listing-social-icons' );
			parent::__construct( 'listing-social-icons', __('[LISTINGS] Listing Social Icons', 'listings'), $widget_ops, $control_ops );
		}

		public function widget( $args, $instance ) {
			extract( $args );

			$title = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : '');
			$title = apply_filters("widget_title", $title);

			if(is_singular("listings")) {
				global $Listing_Template, $Listing, $post;

				$post_meta      = $Listing->get_listing_meta($post->ID);
				$gallery_images = (isset($post_meta['gallery_images']) && !empty($post_meta['gallery_images']) ? $post_meta['gallery_images'] : "");

				echo $Listing_Template->locate_template( "widgets/social_icons",
					array(
						"title"             => $title,
						"gallery_images"    => $gallery_images,
						"before_widget"     => $before_widget,
						"after_widget"      => $after_widget,
						"before_title"      => $before_title,
						"after_title"       => $after_title
					)
				);
			}
		}

		public function form( $instance ) {
			$title  = (isset($instance[ 'title' ]) && !empty($instance[ 'title' ]) ? $instance[ 'title' ] : "");

			echo "<p>" . __("This widget will only show up on in the Single Listing Sidebar on single listing pages.", "listings") . "</p>"; ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'listings' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			global $allowed_widget_tags;

			$instance = array();
			$allowed  = $allowed_widget_tags;

			$instance["title"]  = ( !empty( $new_instance["title"] ) ) ? strip_tags( $new_instance["title"], $allowed ) : '';

			return $instance;
		}
	}
}


//********************************************
//	 stem widget (still controversial)
//***********************************************************
/*class My_Widget extends WP_Widget {

	public function My_Widget() {
		$widget_ops = array( 'classname' => '', 'description' => __('', 'listings') );
        $control_ops = array( 'id_base' => '-widget' );
        parent::__construct( '-widget', __('[LISTINGS] ', 'listings'), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
	}

 	public function form( $instance ) {
		
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
}*/

function register_listing_widgets(){
	global $lwp_options;
	
	register_widget( 'Loan_Calculator' );
	register_widget( 'Filter_Listings' );
	register_widget( 'Single_Filter' );

	register_widget( 'Contact_Us' );
	register_widget( 'Google_Map' );
	register_widget( 'Mail_Chimp' );
	(isset($lwp_options['twitter_switch']) && $lwp_options['twitter_switch'] == 1 ? register_widget( 'Twitter_Feed' ) : "");
	register_widget( 'Recent_Posts' );
	register_widget( 'Recent_Listings' );
	register_widget( 'Contact_Form' );
	register_widget( 'Testimonial_Slider' );
	register_widget( 'List_Items' );
	register_widget( 'Extended_Categories' );

	// listing sidebar widgets
	register_widget( 'Listing_Info_Table' );
	register_widget( 'Listing_Woo_Integration' );
	register_widget( 'Listing_Fuel_Efficiency' );
	register_widget( 'Listing_Video' );
	register_widget( 'Listing_Social_Icons' );
}

add_action( 'widgets_init', 'register_listing_widgets' );