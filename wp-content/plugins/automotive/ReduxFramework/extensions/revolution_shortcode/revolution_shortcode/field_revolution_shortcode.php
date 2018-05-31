<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @author      Dovy Paukstys
 * @version     3.1.5
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_revolution_shortcode' ) ) {

    /**
     * Main ReduxFramework_multi_text_auto class
     *
     * @since       1.0.0
     */
    class ReduxFramework_revolution_shortcode extends ReduxFramework {
        public $redux_slider = '';

	    const REV_SLIDER_TEMPLATE = "automotive_rev_slider_templates";

        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value ='', $parent ) {
            
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
            }    

            // Set default args for this field to avoid bad indexes. Change this to anything you use.
            $defaults = array(
                'options'           => array(),
                'stylesheet'        => '',
                'output'            => true,
                'enqueue'           => true,
                'enqueue_frontend'  => true
            );
            $this->field = wp_parse_args( $this->field, $defaults );

	        $this->redux_slider = new ReduxFramework_slider(array(
		        'id'            => 'test_slider',
		        'name'          => 'test_name',
		        'name_suffix'   => 'rev',
		        'class'         => ''
	        ), '', $this);

        }

	    public function rev_field( $field, $field_name ) {
	    	$type       = $field['type'];
			$options    = (isset($field['options']) && !empty($field['options']) ? $field['options'] : "");

		    $return = '';

		    if ( $type == "color" ) {
			    $return .= '<input data-id="rev_' . $field_name . '" name="' . $field_name . $this->field['name_suffix'] . '" id="rev_' . $field_name . '"';
			    $return .= 'class="redux-color redux-color-init"  type="text" value="" data-alpha="true" data-oldcolor=""  data-default-color="' . ( isset( $this->field['default'] ) ? $this->field['default'] : "" ) . '" />';
			    $return .= '</div>';
		    } elseif( $type == "slider" ) {
		    	$return .= '<input type="text"
                         id="rev_' . $field_name . '"
                         value="10"
                         class="redux-slider-input redux-slider-input-one-default_value_' . $field_name . '"/>
                         <div
                class="redux-slider-container "
                id="default_value_' . $field_name . '"
                data-id="default_value_' . $field_name . '"
                data-min="' . $options['min'] . '"
                data-max="' . $options['max'] . '"
                data-step="' . $options['step'] . '"
                data-handles="1"
                data-display="2"
                data-rtl=""
                data-forced="1"
                data-float-mark="."
                data-resolution="1" 
                data-default-one="' . $options['default'] . '">';
		    } elseif( $type == "select" ){
		    	$return .= "<select id='rev_" . $field_name . "'>";

			    if(!empty($options)){
			    	foreach($options as $val => $text){
			    		$return .= "<option value='" . $val . "'";

					    if(isset($field['default']) && !empty($field['default'])){
					    	$return .= selected($field['default'], $val, false);
					    }

					    $return .= ">" . $text . "</option>";
				    }
			    }

			    $return .= "</select>";
		    }

		    return $return;
	    }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {
	        global $awp_options;

	        $gFile            = include(LISTING_HOME . 'ReduxFramework/ReduxCore/inc/fields/typography/googlefonts.php');
	        $google_font_keys = array_keys($gFile);
	        $google_fonts     = array_combine($google_font_keys, $google_font_keys);

	        $custom_fields = array(
	        	"color" => array(
	        	    "title"  => __("Colors", "listings"),
			        "fields" => array(
				        "background_color"  => array(
					        "type"  => "color",
					        "label" => __( "Background Color", "listings" )
				        ),
			        )
		        ),
		        "dimensions" => array(
		        	"title"  => __("Dimensions", "listings"),
			        "fields" => array(
				        "width"             => array(
					        "type"      => "slider",
					        "label"     => __("Width", "listings"),
					        "options"   => array(
						        "min"       => 0,
						        "max"       => 800,
						        "step"      => 1,
						        "default"   => 400
					        )
				        ),
				        "padding_vertical"             => array(
					        "type"      => "slider",
					        "label"     => __("Padding Top &amp; Bottom", "listings"),
					        "options"   => array(
						        "min"       => 0,
						        "max"       => 200,
						        "step"      => 1,
						        "default"   => 10
					        )
				        ),
				        "padding_horizontal"             => array(
					        "type"      => "slider",
					        "label"     => __("Padding Left &amp; Right", "listings"),
					        "options"   => array(
						        "min"       => 0,
						        "max"       => 200,
						        "step"      => 1,
						        "default"   => 10
					        )
				        ),
			        )
		        ),
		        "border" => array(
		        	"title"  => __("Border", "listings"),
			        "fields" => array(
				        "border_radius"             => array(
					        "type"      => "slider",
					        "label"     => __("Border Radius", "listings"),
					        "options"   => array(
						        "min"       => 0,
						        "max"       => 500,
						        "step"      => 1,
						        "default"   => 10
					        )
				        ),
				        "border_width"              => array(
					        "type"      => "slider",
					        "label"     => __("Border Width", "listings"),
					        "options"   => array(
						        "min"       => 0,
						        "max"       => 20,
						        "step"      => 1,
						        "default"   => 0
					        )
				        ),
				        "border_style"              => array(
					        "type"      => "select",
					        "label"     => __("Border Style", "listings"),
					        "options"   => array(
						        "none"      => __("None", "listings"),
						        "dotted"    => __("Dotted", "listings"),
						        "dashed"    => __("Dashed", "listings"),
						        "solid"     => __("Solid", "listings"),
						        "double"    => __("Double", "listings"),
						        "groove"    => __("Groove", "listings"),
						        "ridge"     => __("Ridge", "listings"),
						        "inset"     => __("Inset", "listings"),
						        "outset"    => __("Outset", "listings"),
					        )
				        ),
				        "border_color"              => array(
					        "type"      => "color",
					        "label"     => __("Border Color", "listings")
				        )
			        )
		        ),
		        "font"  => array(
		        	"title"  => __("Font", "listings"),
			        "fields" => array(
				        "text_color"        => array(
					        "type"  => "color",
					        "label" => __("Font Color", "listings")
				        ),
				        "font_size"             => array(
					        "type"      => "slider",
					        "label"     => __("Font Size", "listings"),
					        "options"   => array(
						        "min"       => 1,
						        "max"       => 72,
						        "step"      => 1,
						        "default"   => 16
					        )
				        ),
				        "font_family"              => array(
					        "type"      => "select",
					        "label"     => __("Font Family", "listings"),
					        "options"   => $google_fonts,
					        "default"   => (isset($awp_options['body_font']['font-family']) && !empty($awp_options['body_font']['font-family']) ? $awp_options['body_font']['font-family'] : "")
				        ),
			        )
		        )
	        );
	        ?>

	        <?php _e("Load Existing Template", "listings"); ?>:

	        <select id="rev_templates">
		        <?php
				$rev_slider_templates = get_option(self::REV_SLIDER_TEMPLATE);

		        if(!empty($rev_slider_templates)){

		        	echo "<option value=''>" . __("Choose...", "listings") . "</option>";
					foreach($rev_slider_templates as $id => $template){
						echo "<option value='" . $id . "' data-options='" . json_encode($template['options']) . "'>" . sanitize_text_field($template['name']) . "</option>";
					}
		        } else {
		        	echo "<option value=''>" . __("No templates yet", "listings") . "</option>";
		        }
	            ?>
	        </select>

	        <br><br>

	        <?php
	        echo "<div id='rev_preview_options'>";
	        if ( ! empty( $custom_fields ) ) {
		        $field_tabs    = "";
		        $field_content = "";

		        foreach($custom_fields as $tab_id => $tab){
		        	$field_tabs .= "<li><a href='#" . $tab_id . "'>" . $tab['title'] . "</a></li>";

			        $field_content .= "<div id='" . $tab_id . "'><table>";
			        foreach($tab['fields'] as $field_name => $field){
			        	$field_content .= "<tr class='" . $field_name . "' data-type='" . $field['type'] . "'><td>" . $field['label'] . "</td><td><div>" . $this->rev_field( $field, $field_name ) . "</div></td>";
			        }
			        $field_content .= "</table></div>";
		        }

		        echo "<ul>" . $field_tabs . "</ul>";
		        echo $field_content;
	        }
	        echo "</div>"; ?>

	        <style type="text/css" id="rev_shortcode_css">

	        </style>

	        <div id="rev_preview_area_container">
		        <div style="float: right;">
			        <?php _e("Card Type", "listings"); ?>:

			        <select id="rev_card_type">
				        <option value="text"><?php _e("Text Card", "listings"); ?></option>
				        <option value="info"><?php _e("Info Card", "listings"); ?></option>
			        </select>
		        </div>

		        <h2><?php _e("Preview", "listings"); ?>:</h2>
				<div id="revolution_slider_preview">
					<div class="inner">
						<?php _e("Loading...", "listings"); ?>
					</div>
				</div>
	        </div>

	        <div id="rev_preview_text_container">
		        <?php _e("Text", "listings"); ?>:
		        <input type="text" id="rev_text">

		        <?php _e("Insert Category", "listings"); ?>:
		        <select id="rev_categories">
				<?php
				global $Listing;

	            $listing_categories = $Listing->get_listing_categories();

				if(!empty($listing_categories)){
					foreach($listing_categories as $slug => $category){
						echo "<option value='" . $slug . "'>" . $category['singular'] . "</option>";
					}
				} ?>
		        </select>

		        <button id="insert_category" class="button-primary"><?php _e("Insert Category", "listings"); ?></button>
	        </div>

	        <?php _e("Test with data", "listings"); ?>:
	        <select id="rev_listing_data">
		        <?php
		        $listing_data = ReduxFramework_revolution_shortcode::get_listing_data();

		        if(!empty($listing_data)){
		        	foreach($listing_data as $listing_id => $listing){
		        		echo "<option value='" . $listing_id . "'>" . $listing['title'] . "</option>";
		        	}
		        }
		        ?>
	        </select>

	        <br><br>

	        <h3><?php _e("Save Template", "listings"); ?></h3>

	        <button class="button-primary" id="rev_save_as_new"><?php _e("Save As New Template", "listings"); ?></button>
	        <button class="button-primary" id="rev_save_as_existing"><?php _e("Update Existing Template", "listings"); ?></button>

	        <div id="rev_save_as_form">
		        <?php _e("New Template Name", "listings"); ?>: <input type="text" id="rev_template_name">

		        <button id="rev_save_template" class="button-primary"><?php _e("Save New Template", "listings"); ?></button>
	        </div>

	        <div id="rev_update_existing_form">
		        <select id="overwrite_rev_template">
			        <?php
			        if(!empty($rev_slider_templates)){
				        echo "<option value=''>" . __("Choose...", "listings") . "</option>";
				        foreach($rev_slider_templates as $id => $template){
					        echo "<option value='" . $id . "'>" . sanitize_text_field($template['name']) . "</option>";
				        }
			        } else {
				        echo "<option value=''>" . __("No templates yet", "listings") . "</option>";
			        } ?>
		        </select>

		        <button id="rev_update_template" class="button-primary"><?php _e("Update Template", "listings"); ?></button>
	        </div>

	        <div id="rev_ajax_message">

	        </div>

	        <?php
        }

        public static function get_listing_data(){
        	global $Listing;

	        $listing_data = array();

	        $listings = get_posts(
		        array(
			        "post_type"      => "listings",
			        "posts_per_page" => 25
		        )
	        );

	        if(!empty($listings)){
		        global $Listing;

		        $listing_categories = $Listing->get_listing_categories();

		        foreach($listings as $listing){
			        $listing_post_meta     = $Listing->get_listing_meta($listing->ID);
			        $data                  = array();

			        $data["title"]         = get_post_field("post_title", $listing->ID);
			        $data["listing_price"] = (isset($listing_post_meta['listing_options']['price']['value']) && !empty($listing_post_meta['listing_options']['price']['value']) ? $Listing->format_currency($listing_post_meta['listing_options']['price']['value']) : "");

			        foreach($listing_categories as $slug => $category){
				        $data[$slug] = (isset($listing_post_meta[$slug]) && !empty($listing_post_meta[$slug]) ? $listing_post_meta[$slug] : "");
			        }

			        $listing_data[$listing->ID] = $data;
		        }
	        }

	        return $listing_data;
        }

	    /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public static function enqueue() {
        	global $Listing, $lwp_options;

//	        $extension = ReduxFramework_revolution_shortcode::getInstance();

	        wp_enqueue_style( 'wp-color-picker' );

	        wp_enqueue_script(
		        'redux-field-revolution-shortcode-js',
		        LISTING_DIR . "/ReduxFramework/extensions/revolution_shortcode/field_revolution_shortcode.js",
		        array( 'jquery', 'redux-js' ),
		        time(),
		        true
	        );

	        $listing_details            = $Listing->get_use_on_listing_categories();

	        $listing_data               = array();
	        $listing_data['listings']   = ReduxFramework_revolution_shortcode::get_listing_data();

	        if ( count( $listing_details ) > 5 ) {
		        $listing_data['use_categories']  = array_slice( $listing_details, 0, 5, true );
	        } else {
		        $listing_data['use_categories'] = $listing_details;
	        }

	        $listing_data['text']       = array(
	            "price"     => $lwp_options['default_value_price'],
		        "tax"       => $lwp_options['tax_label_box'],
		        "none"      => __("None", "listings")
	        );

	        wp_localize_script('redux-field-revolution-shortcode-js', 'listing_data', $listing_data);

	        wp_enqueue_style(
		        'redux-field-revolution-shortcode-css',
		        LISTING_DIR . "/ReduxFramework/extensions/revolution_shortcode/field_revolution_shortcode.css"
	        );

	        wp_enqueue_script(
		        'redux-revolution-shortcode-color-trans-js',
		        LISTING_DIR . "/ReduxFramework/extensions/revolution_shortcode/wp-color-picker-alpha.min.js",
		        array( 'jquery', 'wp-color-picker', 'redux-js' ),
		        time(),
		        true
	        );

	        wp_enqueue_script(
		        'redux-revolution-shortcode-color-js',
		        LISTING_DIR . "/ReduxFramework/extensions/revolution_shortcode/field_color.js",
		        array( 'jquery', 'wp-color-picker', 'redux-js', 'redux-revolution-shortcode-color-trans-js' ),
		        time(),
		        true
	        );

	        wp_enqueue_script(
		        'redux-revolution-shortcode-slider-js',
		        LISTING_DIR . "/ReduxFramework/extensions/revolution_shortcode/field_slider.js",
		        array( 'jquery', 'redux-nouislider-js', 'redux-js' ),
		        time(),
		        true
	        );
        
        }
        
        /**
         * Output Function.
         *
         * Used to enqueue to the front-end
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */        
        public static function output() {

            //if ( $this->field['enqueue_frontend'] ) {

            //}
            
        }        
        
    }
}
