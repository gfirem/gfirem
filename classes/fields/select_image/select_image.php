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
	
	public function __construct() {
		parent::__construct( 'select_image', _gfirem( 'Select Image' ),
			array(
				'select_image_option1' => '0',
			),
			_gfirem( 'Show a field to select image from WP Media library' ),
			array(), gfirem_fs::$starter
		);
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
		
		$this->load_script( $print_value, $field_name );
		
		include dirname( __FILE__ ) . '/view/field_select_image.php';
	}
	
	
	private function load_script( $print_value = "", $field_id = "" ) {
		$base_url = plugin_dir_url( __FILE__ ) . 'assets/';
		wp_enqueue_style( 'select_image', $base_url . 'css/select_image.css', array(), $this->version );
		wp_enqueue_media();
		wp_enqueue_script( 'gfirem_select_image', $base_url . 'js/select_image.js', array( "jquery" ), $this->version, true );
		$params = array(
			'field_id' => $field_id
		);
		if ( ! empty( $print_value ) ) {
			$params["print_value"] = $print_value;
		}
		wp_localize_script( 'gfirem_select_image', 'gfirem_select_image', $params );
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
	
	protected function field_admin_view( $value, $field, $attr ) {
		$value = $this->getMicroImage( $value );
		
		return $value;
	}
	
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
	
	
}