<?php

/**
 * @package WordPress
 * @subpackage Formidable, gfirem
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
class switch_button extends gfirem_field_base {
	
	public $version = '1.0.0';
	
	function __construct() {
		parent::__construct( 'switch_button', _gfirem( 'SwitchButton' ),
			array(
				'switch_button_show_labels' => ''
			),
			_gfirem( 'Show a Switch Button.' )
		);
	}
	
	protected function set_field_options( $fieldData ) {
		$fieldData['default_value'] = 'false';
		
		return $fieldData;
	}
	
	protected function inside_field_options( $field, $display, $values ) {
		
	}
	
	
	/**
	 * Add the HTML for the field on the front end
	 *
	 * @param $field
	 * @param $field_name
	 *
	 */
	protected function field_front_view( $field, $field_name, $html_id ) {
		$field['value'] = stripslashes_deep( $field['value'] );
		$html_id        = $field['field_key'];
		$print_value    = $field['default_value'];
		if ( ! empty( $field['value'] ) ) {
			$print_value = $field['value'];
		}
		
		$this->load_script( $print_value, $html_id );
		
		include dirname( __FILE__ ) . '/view/field_switch_button.php';
	}
	
	private function load_script( $print_value = "", $field_id = "" ) {
		$base_url = plugin_dir_url( __FILE__ ) . 'assets/';
		wp_enqueue_style( 'jquery.switchButton', $base_url . 'css/jquery.switchButton.css', array(), $this->version );
		wp_enqueue_script( 'jquery.switchButton', $base_url . 'js/jquery.switchButton.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'jquery-effects-core' ), $this->version, true );
		wp_enqueue_script( 'gfirem_switch_button', $base_url . 'js/switch_button.js', array( "jquery" ), $this->version, true );
		$params = array(
			'field_id' => $field_id
		);
		if ( ! empty( $print_value ) ) {
			$params["print_value"] = $print_value;
		}
		wp_localize_script( 'gfirem_switch_button', 'gfirem_switch_button', $params );
	}
	
	private function process_value( $value ) {
		$str = 'OFF';
		if ( $value == 'true' ) {
			$str = 'ON';
		}
		
		return $str;
	}
	
	protected function field_admin_view( $value, $field, $attr ) {
		return esc_html( $this->process_value( $value ) );
	}
	
	protected function process_short_code( $replace_with, $tag, $attr, $field ) {
		if ( empty( $replace_with ) ) {
			return $replace_with;
		}
		
		return esc_html( $this->process_value( $replace_with ) );
	}
	
	/**
	 * Set display option for the field
	 *
	 * @param $display
	 *
	 * @return mixed
	 */
	protected function display_options( $display ) {
		$display['unique']         = false;
		$display['required']       = true;
		$display['read_only']      = true;
		$display['description']    = true;
		$display['options']        = true;
		$display['label_position'] = true;
		$display['css']            = true;
		$display['conf_field']     = false;
		$display['invalid']        = true;
		$display['default_value']  = true;
		$display['visibility']     = true;
		$display['size']           = true;
		
		return $display;
	}
	
}