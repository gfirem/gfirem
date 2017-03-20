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
class select_image extends gfirem_field_base {
	
	public $version = '1.0.0';
	private $load_script = false;
	private $base_url;
	
	public function __construct() {
		parent::__construct( 'select_image', _gfirem( 'Select Image' ),
			array(
				'library_title'        => _gfirem( 'Choose Image' ),
				'library_button_title' => _gfirem( 'Choose Image' ),
				'button_title'         => _gfirem( 'Select Image' ),
				'button_css'           => '',
			),
			_gfirem( 'Show a field to select image from WP Media library.' ),
			array(), gfirem_fs::$starter
		);
		$this->base_url = plugin_dir_url( __FILE__ ) . 'assets/';
	}
	
	/**
	 * Options inside the form
	 *
	 * @param $field
	 * @param $display
	 * @param $values
	 */
	protected function inside_field_options( $field, $display, $values ) {
		include dirname( __FILE__ ) . '/view/field_option.php';
	}
	
	/**
	 * Load the scripts when needed in front or backend
	 *
	 * @param $hook
	 */
	public function add_script( $hook ) {
		if ( $this->load_script ) {
			wp_enqueue_style( 'select_image', $this->base_url . 'css/select_image.css', array(), $this->version );
			wp_enqueue_media();
			wp_enqueue_script( 'gfirem_select_image', $this->base_url . 'js/select_image.js', array( "jquery" ), $this->version, true );
			$params = array();
			$fields = FrmField::get_all_types_in_form( $this->form_id, $this->slug );
			foreach ( $fields as $key => $field ) {
				foreach ( $this->defaults as $def_key => $def_val ) {
					$opt                                                             = FrmField::get_option( $field, $def_key );
					$params['config'][ 'item_meta[' . $field->id . ']' ][ $def_key ] = ( ! empty( $opt ) ) ? $opt : $def_val;
				}
			}
			wp_localize_script( 'gfirem_select_image', 'gfirem_select_image', $params );
		}
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
		$html_id        = $field['id'];
		$print_value    = $field['default_value'];
		if ( ! empty( $field['value'] ) ) {
			$print_value = $field['value'];
		}
		
		$showContainer = '';
		if ( empty( $field['value'] ) ) {
			$showContainer = 'style = "display:none;"';
		}
		$imageUrl         = wp_get_attachment_image_url( $field['value'] );
		$imageFullUrl     = wp_get_attachment_url( $field['value'] );
		$attachment_title = basename( get_attached_file( $field['value'] ) );
		
		$button_name    = FrmField::get_option( $field, 'button_title' );
		$button_classes = FrmField::get_option( $field, 'button_css' );
		
		$this->load_script = true;
		$this->add_script( '' );
		
		include dirname( __FILE__ ) . '/view/field_select_image.php';
	}
	
	/**
	 * Return html of image with micro size 50px
	 *
	 * @param $src
	 *
	 * @return string
	 */
	private function getMicroImage( $src ) {
		$result = '';
		if ( isset( $src ) && ! empty( $src ) ) {
			$result = wp_get_attachment_image( $src, array( 50, 50 ), true ) . " <a style='vertical-align: top;' target='_blank' href='" . $src . "'>" . _gfirem( "Full Image" ) . "</a>";
		}
		
		return $result;
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
		$value = $this->getMicroImage( $value );
		
		return $value;
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
		$internal_attr = shortcode_atts( array(
			'output' => 'url',
			'size'   => 'thumbnail',
			'html'   => '0',
		), $attr );
		
		$result = wp_get_attachment_url( $replace_with );
		if ( $internal_attr['output'] == 'img' ) {
			$result = wp_get_attachment_image( $replace_with, $internal_attr['size'] );
		}
		
		if ( $internal_attr['html'] == '1' ) {
			$result = "<a style='vertical-align: top;' target='_blank'  href='" . wp_get_attachment_url( $replace_with ) . "' >" . $result . "</a>";
		}
		
		return $result;
	}
	
	/**
	 * Set the url for the signature to use the email notification
	 *
	 * @param $value
	 * @param $meta
	 * @param $entry
	 *
	 * @return false|string
	 */
	public function replace_value_in_mail( $value, $meta, $entry ) {
		if ( ! empty( $value ) ) {
			$value = wp_get_attachment_image_url( $value, 'full' );
		}
		
		return $value;
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