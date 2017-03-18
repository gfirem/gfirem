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
		/*
		 * Filtros a implementar para procesar las entradas y establecer la imagen que se quiere salvar. Hay que iterar sobre todos los id en item
		 * meta para determinar cuales son del tipo correspondiente, igual se pueden obtener todos los id de este tipo y agarrar sus valores para procesarlos
		 * despues asignarlos de nuevo. Victor esto lo ib a implementar en el padre pero no tiene mucho sentido estar procesandolo en cada campo aunque no sea necesario
		 */
		add_filter('frm_pre_create_entry', array( $this, 'process_pre_create_entry' ), 10, 2);
		add_filter('frm_pre_update_entry', array( $this, 'process_pre_update_entry' ), 10, 2);
	}
	
	public function process_pre_create_entry($values, $id){
		$t = $values;			
		$item_meta_collection =$values['item_meta'];	
		foreach ($item_meta_collection as $key => $value) {
			     $field_type =FrmField::get_type($key);
			   	 if ($field_type=='signature') {			   	 
			   	 	$decoded_value = json_decode($value,true);			   	  
			   	 	$preparedData=stripslashes_deep( $decoded_value['uri'] );			   	 	
			   	 	$data_uri = $preparedData;			   	 	
					$encoded_image = explode(",", $data_uri)[1];
					$decoded_image = base64_decode($encoded_image);				
					$path=	wp_normalize_path(wp_upload_dir()['path']); 
					$guid_image_name = $this->GUID();	
					$filename = $path."/".$guid_image_name.".png";				
					file_put_contents($filename, $decoded_image);
					$filetype = wp_check_filetype( basename( $filename ), null ); 	
					$wp_upload_dir = wp_upload_dir();
					// Prepare an array of post data for the attachment.
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
						'post_mime_type' => $filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					); 	
					$attach_id = wp_insert_attachment( $attachment, $filename);	
					// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					// Generate the metadata for the attachment, and update the database record.
					$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
					wp_update_attachment_metadata( $attach_id, $attach_data );
				    $decoded_value['uri']=$attach_id;
				    $decoded_value['id']=$attach_id;
				    $value = json_encode($decoded_value);
				    $values['item_meta'][$key]=$value;					
			   	 }
		}			
		return $values;
	}	

	/**
  	* Generate GUID
  	* * @return string
  	*/
 	private function GUID() {
  		if ( function_exists( 'com_create_guid' ) === true ) {
   			return trim( com_create_guid(), '{}' );
  			}
  		return sprintf( '%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand( 0, 65535 ), mt_rand( 0, 65535 ), mt_rand( 0, 65535 ), mt_rand( 16384, 20479 ), mt_rand( 32768, 49151 ), mt_rand( 0, 65535 ), mt_rand( 0, 65535 ), mt_rand( 0, 65535 ) );
 	}
	
	public function process_pre_update_entry($values, $id){
		$t = $values;
		$item_meta_collection =$values['item_meta'];	
		foreach ($item_meta_collection as $key => $value) {
			     $field_type =FrmField::get_type($key);
			   	 if ($field_type=='signature') {			   	 
			   	 	$decoded_value = json_decode($value,true);	
			   	 	wp_delete_attachment( $decoded_value['id'] );		   	  
			   	 	$preparedData=stripslashes_deep( $decoded_value['uri'] );			   	 	
			   	 	$data_uri = $preparedData;			   	 	
					$encoded_image = explode(",", $data_uri)[1];
					$decoded_image = base64_decode($encoded_image);				
					$path=	wp_normalize_path(wp_upload_dir()['path']); 
					$guid_image_name = $this->GUID();	
					$filename = $path."/".$guid_image_name.".png";				
					file_put_contents($filename, $decoded_image);
					$filetype = wp_check_filetype( basename( $filename ), null ); 	
					$wp_upload_dir = wp_upload_dir();
					// Prepare an array of post data for the attachment.
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
						'post_mime_type' => $filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					); 	
					$attach_id = wp_insert_attachment( $attachment, $filename);	
					// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					// Generate the metadata for the attachment, and update the database record.
					$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
					wp_update_attachment_metadata( $attach_id, $attach_data );
				    $decoded_value['uri']=$attach_id;
				    $decoded_value['id']=$attach_id;
				    $value = json_encode($decoded_value);
				    $values['item_meta'][$key]=$value;					
			   	 }
		}			
		
		return $values;
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