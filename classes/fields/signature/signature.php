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
class signature extends gfirem_field_base {
	
	public $version = '1.0.0';
	public $areAllSignatureFieldVisited = false;
	public $form_id;
	public $is_front = false;
	
	function __construct() {
		parent::__construct( 'signature', _gfirem( 'Signature' ),
			array(
				'signature'       => '',
				'backgroundcolor' => '',//Color of the canvas
				'pencolor'        => '',
				'width'           => '300',
				'height'          => '150'
			),
			_gfirem( 'Show a Signature Pad.' )
		);
		add_action( 'admin_footer', array( $this, 'add_script_in_front' ) );
		add_action( 'wp_footer', array( $this, 'add_script_in_front' ) );
	}
	
	public function add_script_in_front( $hook ) {
		if ( $this->areAllSignatureFieldVisited ) {
			$this->load_script();
		}
	}
	
	private function load_script( $front = false ) {
		$base_url = plugin_dir_url( __FILE__ ) . 'assets/';
		wp_enqueue_style( 'signature_pad', $base_url . 'css/signature_pad.css', array(), $this->version );
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_script( 'signature_pad', $base_url . 'js/signature_pad.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'gfirem_signature', $base_url . 'js/signature.js', array( "jquery" ), $this->version, true );
		$params = array(
			'is_front' => $this->is_front,
		);
		$signatureFields = FrmField::get_all_types_in_form( $this->form_id, $this->slug );
		foreach ( $signatureFields as $key => $value ) {
			$backgroundcolor                                  = FrmField::get_option( $value, "backgroundcolor" );
			$pencolor                                         = FrmField::get_option( $value, "pencolor" );
			$width                                            = FrmField::get_option( $value, "width" );
			$height                                           = FrmField::get_option( $value, "height" );
			$params['config'][ 'field_' . $value->field_key ] = array(
				'background' => $backgroundcolor,
				'pencolor'   => $pencolor,
				'width'      => $width,
				'height'     => $height
			);
		}
		wp_localize_script( 'gfirem_signature', 'gfirem_signature', $params );
		
	}
	
	/**
	 * Options to set inside the field
	 *
	 * @param $field
	 * @param $display
	 * @param $values
	 */
	protected function inside_field_options( $field, $display, $values ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		$base_url = plugin_dir_url( __FILE__ ) . 'assets/';
		wp_enqueue_script( 'signature_field_options', $base_url . 'js/signature_field_options.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
		include dirname( __FILE__ ) . '/view/field_option.php';
	}
	
	/**
	 * Add the HTML for the field when edit or create the entry
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
		$this->form_id                     = $field['form_id'];
		$this->areAllSignatureFieldVisited = true;
		include dirname( __FILE__ ) . '/view/field_signature.php';
	}
	
	/**
	 * Value to show in the admin table
	 *
	 * @param $value
	 * @param $field
	 * @param $attr
	 *
	 * @return string
	 */
	protected function field_admin_view( $value, $field, $attr ) {
		return _gfirem( 'Signature' );
	}
	
	/**
	 * Process shortcode for view.
	 *
	 * @param $replace_with
	 * @param $tag
	 * @param $attr
	 * @param $field
	 *
	 * @return string
	 */
	protected function process_short_code( $replace_with, $tag, $attr, $field ) {
		if ( empty( $replace_with ) ) {
			return $replace_with;
		}
		$field       = (array) $field;
		$html_id     = $field['field_key'];
		$print_value = $replace_with;
		$field_name  = 'item_meta[' . $field['id'] . ']';
		$this->is_front = true;
		ob_start();
		include dirname( __FILE__ ) . '/view/field_signature.php';
		$output = ob_get_clean();
		
		return $output;
	}
	
	
	/**
	 * Set display option for the field
	 *
	 * @param $display
	 *
	 * @return mixed
	 */
	protected function display_options( $display ) {
		$display['unique']         = true;
		$display['required']       = true;
		$display['read_only']      = true;
		$display['description']    = true;
		$display['options']        = true;
		$display['label_position'] = true;
		$display['css']            = true;
		$display['conf_field']     = false;
		$display['invalid']        = true;
		$display['default_value']  = false;
		$display['visibility']     = true;
		$display['size']           = false;
		
		return $display;
	}
	
}