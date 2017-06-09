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
class page_break extends gfirem_field_base {
	private $load_script = false;
	private $version = '1.0.0';
	private $base_url;
	
	public function __construct() {
		$this->process_page_now();
		parent::__construct( 'page_break', __( 'Page Break', 'gfirem-locale' ),
			array(
				'page_break_save_button' => '',
			),
			__( 'New option to add a save now button beside of the next/previous', 'gfirem-locale' ),
			array( 'name' => 'Page Break Tweaks', 'view' => array( $this, 'global_tab' ) ),
			gfirem_fs::$professional, true
		);
		$this->base_url = plugin_dir_url( __FILE__ ) . 'assets/';
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ) {
			$field_global_options = get_option( $this->slug );
			if ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_save_now' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_save_now' ] == '1' ) {
				//Tweak
				add_action( 'frm_field_options_form', array( $this, 'field_option_form__premium_only' ), 10, 3 );
				add_action( 'frm_update_field_options', array( $this, 'field_options__premium_only' ), 10, 3 );
				
				
				add_action( 'frm_get_field_scripts', array( $this, 'add_script_for_field' ), 10, 3 );
				add_action( 'wp_footer', array( $this, 'load_script' ), 10 );
				add_action( 'admin_footer', array( $this, 'load_script' ), 10 );
				add_filter('frm_success_filter', array($this, 'success_filter'),10, 3);
			}
		}
	}
	
	public function success_filter($trigger, $form, $action){
		if ( ! empty( $_POST['save_now_page'] ) && ! empty( $_POST['form_id'] ) ) {
			$trigger = 'redirect';
		}
		
		return $trigger;
	}
	
	private function process_page_now() {
		if ( ! empty( $_POST['save_now_page'] ) && ! empty( $_POST['form_id'] ) ) {
			$form_id       = intval( FrmAppHelper::get_post_param( 'form_id' ) );
			$save_page_now = intval( FrmAppHelper::get_post_param( 'save_now_page' ) );
			if ( ! empty( $form_id ) ) {
				global $frm_vars;
				$fields       = FrmField::get_all_for_form( $form_id );
				$error        = ! empty( $errors );
				$page_numbers = FrmProFieldsHelper::get_base_page_info( compact( 'fields', 'form_id', 'error', 'errors' ) );
				foreach ( (array) $fields as $k => $f ) {
					// prevent sub fields from showing
					if ( $f->form_id != $form_id ) {
						unset( $fields[ $k ] );
					}
					
					if ( $f->type != 'break' ) {
						continue;
					}
					
					$page_numbers['page_breaks'][ $f->field_order ] = $f;
					
					self::get_next_and_prev_page( $f, null, $page_numbers );
					
					unset( $f, $k );
				}
				$last_page = 0;
				if ( ! empty( $page_numbers['page_breaks'] ) && array_key_exists( $save_page_now, $page_numbers['page_breaks'] ) ) {
					$keys             = array_keys( $page_numbers['page_breaks'] );
					$current_position = array_search( $save_page_now, $keys );
					if ( ! empty( $current_position ) ) {
						$last_page = isset( $keys[ $current_position - 1 ] ) ? $keys[ $current_position - 1 ] : 0;
					}
				}
//				$_GET[ 'frm_page_order_' . $form_id ]  = $last_page;
//				$_POST[ 'frm_page_order_' . $form_id ] = $last_page;
			}
			
		}
	}
	
	private static function get_next_and_prev_page( $f, $error, &$page_numbers ) {
		if ( ( $page_numbers['prev_page'] || $page_numbers['go_back'] ) && ! $page_numbers['get_last'] ) {
			if ( ( ( $error || $page_numbers['go_back'] ) && $f->field_order < $page_numbers['prev_page'] ) || ( ! $error && ! $page_numbers['go_back'] && ! $page_numbers['prev_page_obj'] && $f->field_order == $page_numbers['prev_page'] ) ) {
				$page_numbers['prev_page_obj'] = true;
				$page_numbers['prev_page']     = $f->field_order;
			} else if ( $page_numbers['set_prev'] && $f->field_order < $page_numbers['set_prev'] ) {
				$page_numbers['prev_page_obj'] = true;
				$page_numbers['prev_page']     = $f->field_order;
			} else if ( ( $f->field_order > $page_numbers['prev_page'] ) && ! $page_numbers['set_next'] && ( ! $page_numbers['next_page'] || is_numeric( $page_numbers['next_page'] ) ) ) {
				$page_numbers['next_page'] = $f;
				$page_numbers['set_next']  = true;
			}
		} else if ( $page_numbers['get_last'] ) {
			$page_numbers['prev_page_obj'] = true;
			$page_numbers['prev_page']     = $f->field_order;
			$page_numbers['next_page']     = false;
		} else if ( ! $page_numbers['next_page'] ) {
			$page_numbers['next_page'] = $f;
		} else if ( is_numeric( $page_numbers['next_page'] ) && $f->field_order == $page_numbers['next_page'] ) {
			$page_numbers['next_page'] = $f;
		}
	}
	
	public function field_option_form__premium_only( $field, $display, $values ) {
		if ( $field['type'] != 'break' ) {
			return;
		}
		
		foreach ( $this->defaults as $k => $v ) {
			if ( ! isset( $field[ $k ] ) ) {
				$field[ $k ] = $v;
			}
		}
		
		$enabled_save_now = "";
		if ( $field['page_break_save_button'] == "1" ) {
			$enabled_save_now = "checked='checked'";
		}
		
		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'break_options.php';
	}
	
	public function field_options__premium_only( $field_options, $field, $values ) {
		if ( $field->type != 'break' ) {
			return $field_options;
		}
		
		foreach ( $this->defaults as $opt => $default ) {
			$field_options[ $opt ] = isset( $values['field_options'][ $opt . '_' . $field->id ] ) ? $values['field_options'][ $opt . '_' . $field->id ] : $default;
		}
		
		return $field_options;
	}
	
	/**
	 * Add the HTML for the field on the front end
	 *
	 * @param $field
	 * @param $form
	 * @param $form_id
	 */
	public function add_script_for_field( $field, $form, $form_id ) {
		if ( $field['type'] != 'break' ) {
			return;
		}
		$field_global_options = get_option( $this->slug );
		if ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_save_now' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_save_now' ] == '1' ) {
			$page_break_save_button = FrmField::get_option( $field, 'page_break_save_button' );
			if ( ! empty( $page_break_save_button ) ) {
				$this->load_script = true;
				$this->form_id     = $form_id;
			}
		}
	}
	
	public function load_script() {
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ) {
			if ( $this->load_script ) {
				wp_enqueue_script( 'gfirem_tweak_page_break', $this->base_url . 'js/gfirem_tweak_page_break.js', array( "jquery" ), $this->version, true );
				$param  = array();
				$fields = FrmField::get_all_types_in_form( $this->form_id, 'break' );
				foreach ( $fields as $field ) {
					$page_break_save_button = FrmField::get_option( $field, 'page_break_save_button' );
					if ( ! empty( $page_break_save_button ) ) {
						$param[] = $field->field_order;
					}
				}
				wp_localize_script( 'gfirem_tweak_page_break', 'gfirem_tweak_page_break', $param );
			}
		}
	}
	
	public function global_tab() {
		add_settings_field( 'section_page_break', __( 'Enable Save Now', 'gfirem-locale' ), array( $this, 'section_page_break' ), 'page_break', 'section_page_break' );
	}
	
	public function section_page_break() {
		echo '<p ' . $this->disable_class_tag( 'p', gfirem_fs::$professional ) . '>';
		$this->get_view_for( 'enabled_' . $this->slug . '_save_now', 'checkbox', 'page_break', array(), gfirem_fs::$professional );
		_e( 'If tick this you get a new option inside each Page Break to enable a Save Now button beside it', 'gfirem-locale' );
		echo '</p>';
	}
}