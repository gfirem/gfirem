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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class date_time_field extends gfirem_field_base {
	
	public $version = '1.0.0';
	
	function __construct() {
		parent::__construct( 'date_time_field', _gfirem( 'DateTimePicker' ),
			array(
				'datetimepicker_format' => 'Y/m/d H:i',
				'datetimepicker_timepicker' => 'true',
				'datetimepicker_inline' => 'true',
			),
			_gfirem( 'Show the Date and Time with a DateTimePicker.' ),
			array(), gfirem_fs::$professional
		);
	}
	
	protected function set_field_options( $fieldData ) {
		$fieldData['default_value'] = date( 'Y/m/d' ) . ' ' . date( 'H:i' );
		
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
		
		$print_value = $field['default_value'];
		if ( ! empty( $field['value'] ) ) {
			$print_value = $field['value'];
		}
		
		$html_id = $field['field_key'];
		$this->load_script( $print_value, $html_id );
		
		include dirname( __FILE__ ) . '/view/field_datetime.php';
	}
	
	
	private function load_script( $print_value = "", $field_id = "" ) {
		$base_url = plugin_dir_url( __FILE__ ) . 'assets/';
		wp_enqueue_style( 'jquery.datetimepicker', $base_url . 'css/jquery.datetimepicker.min.css', array(), $this->version );
		wp_enqueue_script( 'jquery.datetimepicker', $base_url . 'js/jquery.datetimepicker.full.min.js', array( "jquery" ), $this->version, true );
		wp_enqueue_script( 'date_time_field', $base_url . 'js/date_time_field.js', array( "jquery" ), $this->version, true );
		$params = array(
			'now_date' => date( 'Y/m/d' ),
			'now_time' => date( 'H:i' ),
			'language' => 'en',
		);
		if ( ! empty( $print_value ) ) {
			$params["print_value"] = $print_value;
		}
		if ( ! empty( $field_id ) ) {
			$params["field_id"] = $field_id;
		}
		wp_localize_script( 'date_time_field', 'date_time_field', $params );
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