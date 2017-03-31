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
	private $last_saved = array();
	
	function __construct() {
		parent::__construct( 'signature', gfirem_manager::translate( 'Signature' ),
			array(
				'signature'       => '',
				'backgroundcolor' => '#fff',//Color of the canvas
				'pencolor'        => '#000',
				'width'           => '300',
				'height'          => '150'
			),
			gfirem_manager::translate( 'Show a Signature Pad.' )
		);
		add_action( 'admin_footer', array( $this, 'add_script' ) );
		add_action( 'wp_footer', array( $this, 'add_script' ) );
		add_filter( 'frm_pre_create_entry', array( $this, 'process_pre_create_entry' ), 10, 1 );
		add_filter( 'frm_pre_update_entry', array( $this, 'process_pre_update_entry' ), 10, 2 );
		add_action( 'frm_before_destroy_entry', array( $this, 'process_destroy_entry' ), 10, 2 );
	}
	
	/**
	 * Destroy the attached image to the entry
	 *
	 * @param $id
	 * @param $entry
	 */
	public function process_destroy_entry( $id, $entry ) {
		$entry_with_meta = FrmEntry::getOne( $id, true );
		foreach ( $entry_with_meta->metas as $key => $value ) {
			$field_type = FrmField::get_type( $key );
			if ( $field_type == 'signature' && ! empty( $value ) ) {
				$decoded_value = json_decode( $value, true );
				if ( is_array( $decoded_value ) ) {
					if ( ! empty( $decoded_value['id'] ) ) {
						wp_delete_attachment( $decoded_value['id'], true );
					}
				}
			}
		}
	}
	
	/**
	 * Process the entry before created it in database
	 *
	 * @param $values
	 *
	 * @return mixed
	 */
	public function process_pre_create_entry( $values ) {
		$values['item_meta'] = $this->save_signature( $values['item_meta'], $values['form_id'] );
		
		return $values;
	}
	
	/**
	 * Process the entry before is updated in the database
	 *
	 * @param $values
	 * @param $id
	 *
	 * @return mixed
	 */
	public function process_pre_update_entry( $values, $id ) {
		$values['item_meta'] = $this->save_signature( $values['item_meta'], $values['form_id'], true );
		
		return $values;
	}
	
	/**
	 * Save the signature to wp attachment
	 *
	 * @param $fields_collections
	 * @param $form_id
	 * @param bool $delete_before
	 *
	 * @return mixed
	 */
	public function save_signature( $fields_collections, $form_id, $delete_before = false ) {
		foreach ( $fields_collections as $key => $value ) {
			$field_type = FrmField::get_type( $key );
			if ( $field_type == 'signature' && ! empty( $value ) ) {
				$decoded_value = json_decode( $value, true );
				if ( is_array( $decoded_value ) && is_string( $decoded_value['uri'] ) ) {
					if ( $delete_before && ! empty( $decoded_value['id'] ) ) {
						wp_delete_attachment( $decoded_value['id'], true );
					}
					$prepared_data = stripslashes_deep( $decoded_value['uri'] );
					$exploded_data = explode( ",", $prepared_data );
					$decoded_image = base64_decode( $exploded_data[1] );
					$upload_dir    = wp_upload_dir();
					$file_id       = $this->slug . '_' . $form_id . '_' . $key . '_' . time();
					$file_name     = $file_id . ".png";
					$full_path     = wp_normalize_path( $upload_dir['path'] . DIRECTORY_SEPARATOR . $file_name );
					$upload_file   = wp_upload_bits( $file_name, null, $decoded_image );
					if ( ! $upload_file['error'] ) {
						$wp_filetype   = wp_check_filetype( $file_name, null );
						$attachment    = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', $file_name ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);
						$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );
						if ( ! is_wp_error( $attachment_id ) ) {
							require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
							$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
							wp_update_attachment_metadata( $attachment_id, $attachment_data );
							$decoded_value['uri']          = $attachment_id;
							$decoded_value['id']           = $attachment_id;
							$value                         = json_encode( $decoded_value );
							$fields_collections[ $key ]    = $value;
							$_POST['item_meta'][ $key ]    = $value;//Used to update the current request
							$_REQUEST['item_meta'][ $key ] = $value;//Used to update the current request
							$this->last_saved[ $key ]      = $value;
						}
					}
				}
			}
		}
		
		return $fields_collections;
	}
	
	/**
	 * Add script needed to load the field
	 *
	 * @param $hook
	 */
	public function add_script( $hook ) {
		if ( $this->areAllSignatureFieldVisited ) {
			$base_url = plugin_dir_url( __FILE__ ) . 'assets/';
			wp_enqueue_style( 'signature_pad', $base_url . 'css/signature_pad.css', array(), $this->version );
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_script( 'signature_pad', $base_url . 'js/signature_pad.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( 'gfirem_signature', $base_url . 'js/signature.js', array( "jquery" ), $this->version, true );
			$params          = array();
			$signatureFields = FrmField::get_all_types_in_form( $this->form_id, $this->slug );
			foreach ( $signatureFields as $key => $field ) {
				foreach ( $this->defaults as $def_key => $def_val ) {
					$opt                                                          = FrmField::get_option( $field, $def_key );
					$params['config'][ 'field_' . $field->field_key ][ $def_key ] = ( ! empty( $opt ) ) ? $opt : $def_val;
				}
			}
			wp_localize_script( 'gfirem_signature', 'gfirem_signature', $params );
		}
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
		if ( ! empty( $value ) ) {
			$signature = json_decode( $value, true );
			$value     = $this->getMicroImage( $signature['id'] );
		}
		
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
		if ( ! empty( $replace_with ) ) {
			$signature    = json_decode( $replace_with, true );
			$width        = FrmField::get_option( $field, 'width' );
			$height       = FrmField::get_option( $field, 'height' );
			$replace_with = $this->getMicroImage( $signature['id'], $height, $width );
		}
		
		return $replace_with;
	}
	
	/**
	 * Return html of image with micro size 50px
	 *
	 * @param $id
	 * @param int $height
	 * @param int $width
	 *
	 * @return string
	 */
	private function getMicroImage( $id, $height = 25, $width = 50 ) {
		$result = '';
		if ( isset( $id ) && ! empty( $id ) ) {
			$result = " <a style='vertical-align: top;' target='_blank' href='" . wp_get_attachment_image_url( $id, 'full' ) . "'>" . wp_get_attachment_image( $id, array( $width, $height ), true ) . "</a>";
		}
		
		return $result;
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
			$signature = json_decode( $value, true );
			$value     = wp_get_attachment_image_url( $signature['id'], 'full' );
		}
		
		return $value;
	}
	
	
}