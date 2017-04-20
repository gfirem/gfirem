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
class select_image extends gfirem_field_base {
	
	public $version = '2.0.0';
	private $load_script = false;
	private $base_url;
	private $upload_file_tab_string;
	private $upload_image_tab_string;
	
	public function __construct() {
		parent::__construct( 'select_image', gfirem_manager::translate( 'Select Image' ),
			array(
				'library_title'        => gfirem_manager::translate( 'Choose Image' ),
				'library_button_title' => gfirem_manager::translate( 'Choose Image' ),
				'button_title'         => gfirem_manager::translate( 'Select Image' ),
				'button_css'           => '',
				'activate_zoom'        => 'true',
				'scroll_zoom'          => 'false',
			),
			gfirem_manager::translate( 'Show a field to select image from WP Media library.' ),
			array( 'name' => 'Select Image Tweaks', 'view' => array( $this, 'global_tab' ) ), gfirem_fs::$starter
		);
		$this->base_url = plugin_dir_url( __FILE__ ) . 'assets/';
		add_filter( 'ajax_query_attachments_args', array( $this, 'show_current_user_attachments' ) );
		add_filter( 'media_view_strings', array( $this, 'media_view_strings' ), 10, 2 );
	}
	
	public function media_view_strings( $strings, $post ) {
		$this->upload_file_tab_string  = $strings['uploadFilesTitle'];
		$this->upload_image_tab_string = $strings['uploadImagesTitle'];
		
		return $strings;
	}
	
	public function show_current_user_attachments( $query ) {
		$field_global_options = get_option( $this->slug );
		if ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_tweak_belong_image' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_tweak_belong_image' ] == '1' ) {
			$user_id = get_current_user_id(); // get current user ID
			if ( $user_id && ! current_user_can( 'manage_options' ) ) {  // if we have a current user ID (they're logged in) and the current user is not an administrator
				$query['author'] = $user_id; // add author filter, ensures only the current users images are displayed
			}
		}
		
		return $query;
	}
	
	public function global_tab() {
		global $wp_settings_sections;
		add_settings_field( 'section_select_image', __( 'Owner Image', 'gfirem-locale' ), array( $this, 'owner_image' ), 'select_image', 'section_select_image' );
		add_settings_field( 'section_select_image_upload_as_default_tab', __( 'Upload as default Tab', 'gfirem-locale' ), array( $this, 'upload_as_default_tab' ), 'select_image', 'section_select_image' );
	}
	
	public function owner_image() {
		echo '<p ' . $this->disable_class_tag( 'p', gfirem_fs::$starter ) . '>';
		$this->get_view_for( 'enabled_' . $this->slug . '_tweak_belong_image', 'checkbox', 'select_image', array(), gfirem_fs::$starter );
		_e( ' This option enforce media library to only show the image to the owners.', 'gfirem-locale' );
		echo '</p>';
	}
	
	public function upload_as_default_tab() {
		echo '<p ' . $this->disable_class_tag( 'p', gfirem_fs::$starter ) . '>';
		$this->get_view_for( 'enabled_' . $this->slug . '_upload_as_default_tab', 'checkbox', 'select_image', array(), gfirem_fs::$starter );
		_e( ' Set Upload tab as default when open the media library.', 'gfirem-locale' );
		echo '</p>';
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
	
	/**
	 * Load the scripts when needed in front or backend
	 *
	 * @param string $hook
	 * @param string $image_url
	 */
	public function add_script( $hook = '', $image_url = '' ) {
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
			if ( $this->load_script ) {
				wp_enqueue_style( 'select_image', $this->base_url . 'css/select_image.css', array(), $this->version );
				wp_enqueue_media();
				wp_enqueue_script( 'jquery.elevateZoom', $this->base_url . 'js/jquery.elevateZoom-3.0.8.min.js', array( "jquery" ), $this->version, true );
				wp_enqueue_script( 'gfirem_select_image', $this->base_url . 'js/select_image.js', array( "jquery" ), $this->version, true );
				$params = array();
				$fields = FrmField::get_all_types_in_form( $this->form_id, $this->slug );
				foreach ( $fields as $key => $field ) {
					foreach ( $this->defaults as $def_key => $def_val ) {
						$opt                                                             = FrmField::get_option( $field, $def_key );
						$params['config'][ 'item_meta[' . $field->id . ']' ][ $def_key ] = ( ! empty( $opt ) ) ? $opt : $def_val;
					}
					if ( ! empty( $image_url ) ) {
						$params['config'][ 'item_meta[' . $field->id . ']' ]['image_url'] = $image_url;
					}
				}
				if ( ! empty( $_GET['frm_action'] ) ) {
					$params['action'] = FrmAppHelper::get_param( 'frm_action' );
				}
				if ( ! empty( $this->upload_file_tab_string ) && ! empty( $this->upload_image_tab_string ) ) {
					$params['upload_file_tab_string']  = $this->upload_file_tab_string;
					$params['upload_image_tab_string'] = $this->upload_image_tab_string;
				}
				$field_global_options = get_option( $this->slug );
				if ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_upload_as_default_tab' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_upload_as_default_tab' ] == '1' ) {
					$params['upload_as_default_tab'] = $field_global_options[ 'enabled_' . $this->slug . '_upload_as_default_tab' ];
				}
				wp_localize_script( 'gfirem_select_image', 'gfirem_select_image', $params );
			}
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
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
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
			$full_image_url   = wp_get_attachment_image_src( $field['value'], 'full' );
			$imageFullUrl     = wp_get_attachment_url( $field['value'] );
			$attachment_title = basename( get_attached_file( $field['value'] ) );
			
			$button_name    = FrmField::get_option( $field, 'button_title' );
			$button_classes = FrmField::get_option( $field, 'button_css' );
			
			$this->load_script = true;
			$this->add_script( '', $imageUrl );
			
			include dirname( __FILE__ ) . '/view/field_select_image.php';
		}
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
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
			if ( isset( $src ) && ! empty( $src ) ) {
				$result = wp_get_attachment_image( $src, array( 50, 50 ), true ) . " <a style='vertical-align: top;' target='_blank' href='" . $src . "'>" . gfirem_manager::translate( "Full Image" ) . "</a>";
			}
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
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
			$value = $this->getMicroImage( $value );
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
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
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
			$replace_with = $result;
		}
		
		return $replace_with;
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
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
			if ( ! empty( $value ) ) {
				$value = wp_get_attachment_image_url( $value, 'full' );
			}
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