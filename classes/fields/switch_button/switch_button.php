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
	private $load_script;
	
	function __construct() {
		parent::__construct( 'switch_button', gfirem_manager::translate( 'SwitchButton' ),
			array(
				'labels_placement' => 'both',
				'on_label'         => gfirem_manager::translate( 'ON' ),
				'off_label'        => gfirem_manager::translate( 'OFF' ),
				'button_width'     => 25,
				'width'            => 50,
				'height'           => 20,
			
			),
			gfirem_manager::translate( 'Show a Switch Button.' ), array(), gfirem_fs::$starter
		);
		add_action( 'admin_footer', array( $this, 'add_script' ) );
		add_action( 'wp_footer', array( $this, 'add_script' ) );
	}
	
	/**
	 * Load the scripts when needed in front or backend
	 *
	 * @param $hook
	 */
	public function add_script( $hook ) {
		if ( $this->load_script ) {
			$this->load_script();
		}
	}
	
	/**
	 * Load the scripts for all existing fields
	 *
	 * @param string $print_value
	 * @param string $field_id
	 */
	private function load_script( $print_value = "", $field_id = "" ) {
		$base_url = plugin_dir_url( __FILE__ ) . 'assets/';
		wp_enqueue_style( 'jquery.switchButton', $base_url . 'css/jquery.switchButton.css', array(), $this->version );
		wp_enqueue_script( 'jquery.switchButton', $base_url . 'js/jquery.switchButton.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'jquery-effects-core' ), $this->version, true );
		wp_enqueue_script( 'gfirem_switch_button', $base_url . 'js/switch_button.js', array( "jquery" ), $this->version, true );
		
		$params = array();
		$fields = FrmField::get_all_types_in_form( $this->form_id, $this->slug );
		foreach ( $fields as $key => $field ) {
			foreach ( $this->defaults as $def_key => $def_val ) {
				$opt                                                          = FrmField::get_option( $field, $def_key );
				$params['config'][ 'field_' . $field->field_key ][ $def_key ] = ( ! empty( $opt ) ) ? $opt : $def_val;
			}
		}
		wp_localize_script( 'gfirem_switch_button', 'gfirem_switch_button', $params );
	}
	
	/**
	 * Set the default value to the field
	 *
	 * @param $fieldData
	 *
	 * @return mixed
	 */
	protected function set_field_options( $fieldData ) {
		$fieldData['default_value'] = gfirem_manager::translate( 'OFF' );
		
		return $fieldData;
	}
	
	/**
	 * Options inside the form
	 *
	 * @param $field
	 * @param $display
	 * @param $values
	 */
	protected function inside_field_options( $field, $display, $values ) {
		$label_placement_option = array(
			'both'  => gfirem_manager::translate( 'Both' ),
			'left'  => gfirem_manager::translate( 'Left' ),
			'right' => gfirem_manager::translate( 'Right' ),
		);
		include dirname( __FILE__ ) . '/view/field_option.php';
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
		$this->load_script = true;
		
		include dirname( __FILE__ ) . '/view/field_switch_button.php';
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