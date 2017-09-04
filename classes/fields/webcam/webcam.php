<?php

/**
 * @package    WordPress
 * @subpackage Formidable, gfirem
 * @author     GFireM
 * @copyright  2017
 * @link       http://www.gfirem.com
 * @license    http://www.apache.org/licenses/
 *
 */
class webcam extends gfirem_field_base {

	public $version = '2.0.0';

	public function __construct() {
		parent::__construct( 'webcam', gfirem_manager::translate( 'Webcam' ),
			array(
				'button_title'  => gfirem_manager::translate( 'Take Snapshot' ),
				'button_css'    => '',
				'activate_zoom' => 'true',
				'scroll_zoom'   => 'false',
			),
			gfirem_manager::translate( 'Take a Snapshot whit the webcam.' )

		);
		add_action( 'admin_footer', array( $this, 'add_script' ) );
		add_action( 'wp_footer', array( $this, 'add_script' ) );
		add_filter( 'frm_pre_create_entry', array( $this, 'process_pre_create_entry' ), 10, 1 );
		add_filter( 'frm_pre_update_entry', array( $this, 'process_pre_update_entry' ), 10, 2 );
		add_action( 'frm_before_destroy_entry', array( $this, 'process_destroy_entry' ), 10, 2 );
	}

	public function process_pre_update_entry( $values) {
		$values['item_meta'] = $this->save_signature( $values['item_meta'], $values['form_id'], true );

		return $values;
	}
	public function process_pre_create_entry( $values ) {
		$values['item_meta'] = $this->save_signature( $values['item_meta'], $values['form_id'] );

		return $values;
	}

	public function save_signature( $fields_collections, $form_id, $delete_before = false ) {
		foreach ( $fields_collections as $key => $value ) {
			$field_type = FrmField::get_type( $key );
			if ( $field_type == 'webcam' && ! empty( $value ) ) {

				$exploded_data = explode( ",", $value );
				if ( ! isset( $exploded_data[1] ) ) {
					//En caso de que no se edite el campo signature @Victor
					continue;
				}

				if ( $delete_before && ! empty( $decoded_value['id'] ) ) {
					wp_delete_attachment( $decoded_value['id'], true );
				}
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
						$value                         = json_encode(  $upload_file['url'] );
						$fields_collections[ $key ]    = $value;
						$_POST['item_meta'][ $key ]    = $value;//Used to update the current request
						$_REQUEST['item_meta'][ $key ] = $value;//Used to update the current request
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

		$base_url = plugin_dir_url( __FILE__ ) . 'assets/';
		wp_enqueue_script( 'webcam', $base_url . 'webcam.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'gfirem_webcam', $base_url . 'camera.js', array( "jquery" ), $this->version, true );
		$params          = array();
		$signatureFields = FrmField::get_all_types_in_form( $this->form_id, $this->slug );
		foreach ( $signatureFields as $key => $field ) {
			foreach ( $this->defaults as $def_key => $def_val ) {
				$opt                                                          = FrmField::get_option( $field, $def_key );
				$params['config'][ 'field_' . $field->field_key ][ $def_key ] = ( ! empty( $opt ) ) ? $opt : $def_val;
			}
		}
		wp_localize_script( 'gfirem_webcam', 'gfirem_webcam', $params );

	}

	/**
	 * Options inside the form
	 *
	 * @param $field
	 * @param $display
	 * @param $values
	 */
	protected function inside_field_options( $field, $display, $values ) {
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
			include dirname( __FILE__ ) . '/view/field_option.php';
		}
	}

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

		$button_name = FrmField::get_option( $field, 'button_title' );

		include dirname( __FILE__ ) . '/view/field_webcam.php';

	}

	protected function process_short_code( $replace_with, $tag, $attr, $field ) {
		$internal_attr = shortcode_atts( array(
			'show' => 'id',
		), $attr );

		if ( $internal_attr['show'] == 'id' ) {
			return $replace_with;
		}

		$user = get_userdata( $replace_with );
		if ( ! empty( $user ) ) {
			$user_field = $internal_attr['show'];
			if ( property_exists( $user->data, $user_field ) ) {
				return esc_html( $user->$user_field );
			}
		}

		return $replace_with;
	}

}