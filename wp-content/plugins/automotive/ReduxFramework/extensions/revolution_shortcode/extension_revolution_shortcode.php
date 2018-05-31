<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_Multi_Text
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_Extension_revolution_shortcode' ) ) {

    /**
     * Main ReduxFramework_Extension_revolution_shortcode class
     *
     * @since       1.0.0
     */
    class ReduxFramework_Extension_revolution_shortcode {


	    // Protected vars
	    protected $parent;
	    public $extension_url;
	    public $extension_dir;
	    public static $theInstance;

	    /**
	     * Field Constructor.
	     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	     *
	     * @since       1.0.0
	     * @access      public
	     * @return      void
	     */
	    public function __construct( $parent ) {

		    $this->parent = $parent;
		    if ( empty( $this->extension_dir ) ) {
			    $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
		    }
		    $this->field_name = 'revolution_shortcode';

		    self::$theInstance = $this;

		    add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array(
			    &$this,
			    'overload_field_path'
		    ) ); // Adds the local field

	    }

	    public static function getInstance() {
		    return self::$theInstance;
	    }

	    public function rev_field( $type ) {
		    $return = '';

		    if ( $type == "color" ) {
//			    $return .= '<input type="text" id="' . $this->field['id'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[]' . '" value="" class="regular-text ' . $this->field['class'] . '" />';

			    $return .= '<div class="color_picker_container"><span>' . __('Badge Font', 'redux-framework') . '</span><input data-id="' . $this->field['id'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[font][sold]' . '" id="' . $this->field['id'] . '-color" class="redux-color redux-color-init ' . $this->field['class'] . '"  type="text" value="' . $sold_font . '" data-oldcolor=""  data-default-color="' . ( isset( $this->field['default'] ) ? $this->field['default'] : "" ) . '" />';
			    $return .= '<input type="hidden" class="redux-saved-color" id="' . $this->field['id'] . '-saved-color" value=""></div>';

		    }

		    return $return;
	    }

	    /**
	     * Field Render Function.
	     * Takes the vars and outputs the HTML for the field in the settings
	     *
	     * @since       1.0.0
	     * @access      public
	     * @return      void
	     */
	    public function render() {
		    $custom_fields = array(
			    "background_color" => array(
				    "type" => "color",
				    "label" => __( "Background Color", "listings" )
			    )
		    );

		    if ( ! empty( $custom_fields ) ) {
			    echo "<table>";
			    foreach ( $custom_fields as $field_name => $field ) {
				    echo "<tr><td>" . $field['label'] . "</td><td>" . $this->rev_field( $field['type'] ) . "</td>";
			    }
			    echo "</table>";
		    }


		    ?>

		    <?php

		    /*echo '<ul id="' . $this->field['id'] . '-ul" class="redux-multi-text">';

			if ( isset( $this->value ) && is_array( $this->value ) ) {
				foreach ( $this->value as $k => $value ) {
					if ( $value != '' ) {
						echo '<li><input type="text" id="' . $this->field['id'] . '-' . $k . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[]' . '" value="' . esc_attr( $value ) . '" class="regular-text ' . $this->field['class'] . '" /> <a href="javascript:void(0);" class="deletion redux-multi-text-remove">' . __( 'Remove', 'redux-framework' ) . '</a></li>';
					}
				}
			} elseif ( $this->show_empty == true ) {
				echo '<li><input type="text" id="' . $this->field['id'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[]' . '" value="" class="regular-text ' . $this->field['class'] . '" /> <a href="javascript:void(0);" class="deletion redux-multi-text-remove">' . __( 'Remove', 'redux-framework' ) . '</a></li>';
			}

			echo '<li style="display:none;"><input type="text" id="' . $this->field['id'] . '" name="" value="" class="regular-text" /> <a href="javascript:void(0);" class="deletion redux-multi-text-remove">' . __( 'Remove', 'redux-framework' ) . '</a></li>';

			echo '</ul>';
			$this->field['add_number'] = ( isset( $this->field['add_number'] ) && is_numeric( $this->field['add_number'] ) ) ? $this->field['add_number'] : 1;
			echo '<a href="javascript:void(0);" class="button button-primary redux-multi-text-add" data-add_number="' . $this->field['add_number'] . '" data-id="' . $this->field['id'] . '-ul" data-name="' . $this->field['name'] . $this->field['name_suffix'] . '[]">' . $this->add_text . '</a><br/>';*/
	    }

	    public function overload_field_path( $field ) {
		    return dirname(__FILE__).'/'.$this->field_name.'/field_'.$this->field_name.'.php';
	    }
    }
}