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
class upload extends gfirem_field_base {
	
	private $load_script = false;
	private $version = '1.0.0';
	private $base_url;
	
	public function __construct() {
		parent::__construct( 'upload', __( 'Upload Field', 'gfirem-locale' ),
			array(
				'upload_field_target'       => '',
				'upload_field_target_value' => '',
			),
			__( 'New option to tweak your upload fields', 'gfirem-locale' ),
			array( 'name' => 'Upload Field Tweaks', 'view' => array( $this, 'global_tab' ) ),
			gfirem_fs::$professional, true
		);
		$this->base_url = plugin_dir_url( __FILE__ ) . 'assets/';
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ) {
			$field_global_options = get_option( $this->slug );
			if ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_zoom' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_zoom' ] == '1' ) {
				//Tweak
				add_action( 'frm_form_fields', array( $this, 'show_formidable_field_front_field' ), 10, 2 );
				add_action( "wp_ajax_nopriv_upload_tweak_attachment", array( $this, "upload_tweak_attachment" ) );
				add_action( "wp_ajax_upload_tweak_attachment", array( $this, "upload_tweak_attachment" ) );
			}
		}
	}
	
	public function upload_tweak_attachment() {
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ) {
			if ( ! ( is_array( $_GET ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				wp_die();
			}
			check_ajax_referer( 'frm_ajax', 'nonce' );
			$result = array();
			
			if ( ! empty( $_GET['media_is'] ) ) {
				$i = 0;
				foreach ( $_GET['media_is'] as $media_id ) {
					$url = wp_get_attachment_image_url( $media_id, 'full' );
					if ( is_string( $url ) ) {
						$result[ $i ]['url'] = $url;
						$result[ $i ]['id']  = $media_id;
						$i ++;
					}
				}
			}
			
			echo json_encode( $result );
			wp_die();
		}
	}
	
	/**
	 * Add the HTML for the field on the front end
	 *
	 * @param $field
	 * @param $field_name
	 *
	 */
	public function show_formidable_field_front_field( $field, $field_name ) {
		if ( $field['type'] != 'file' ) {
			return;
		}
		$this->load_script();
	}
	
	public function load_script() {
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ) {
			wp_enqueue_style( 'upload_tweak', $this->base_url . 'css/upload_tweak.css', array(), $this->version );
			wp_enqueue_script( 'jquery.elevateZoom', select_image::get_assets_url() . 'js/jquery.elevateZoom-3.0.8.min.js', array( "jquery" ), $this->version, true );
			wp_enqueue_script( 'upload_tweak', $this->base_url . 'js/upload_tweak.js', array( "jquery" ), $this->version, true );
		}
	}
	
	public function global_tab() {
		add_settings_field( 'section_upload', __( 'Enable Zoom', 'gfirem-locale' ), array( $this, 'upload_zoom' ), 'upload', 'section_upload' );
	}
	
	public function upload_zoom() {
		echo '<p ' . $this->disable_class_tag( 'p', gfirem_fs::$professional ) . '>';
		$this->get_view_for( 'enabled_' . $this->slug . '_zoom', 'checkbox', 'upload', array(), gfirem_fs::$professional );
		_e( 'If tick this you can zoom image in the preview before send the form.', 'gfirem-locale' );
		echo '</p>';
	}
}