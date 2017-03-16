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
class gfirem_admin {
	
	private $global_settings_tabs;
	private $fields = array();
	
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_admin_settings' ) );
//		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js' ) );
//		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
	}
	
	public function handle_sub_menu( $is_visible, $menu_id ) {
		if ( $menu_id == 'account' ) {
			$is_visible = false;
		}
		
		return $is_visible;
	}
	
	/**
	 * Adding the Admin Page
	 */
	public function admin_menu() {
		add_menu_page( _gfirem( 'GFireM Fields' ), _gfirem( 'GFireM Fields' ), 'manage_options', gfirem_manager::get_slug(), array( $this, 'screen' ), 'dashicons-smiley' );
	}
	
	public function screen() {
		$this->fields = apply_filters( 'gfirem_register_field', array() );
		$active_tab   = 'generic';
		$option       = 'gfirem_options';
		if ( ! empty( $_GET['tab'] ) ) {
			$active_tab = $_GET['tab'];
		}
		foreach ( $this->fields as $global_settings_tab_key => $global_settings_tab_data ) {
			if ( ! empty( $active_tab ) && $active_tab != 'generic' && !empty( $global_settings_tab_data->global_options ) && $global_settings_tab_key == $active_tab ) {
				$option = $global_settings_tab_key;
				break;
			}
		}
		include_once( GFIREM_VIEW_PATH . 'html_admin.php' );
	}
	
	public function register_admin_settings() {
		$this->fields = apply_filters( 'gfirem_register_field', array() );
		//Options for the general tab
		register_setting( 'gfirem_options', 'gfirem_options' );
		add_settings_section( 'section_general', _gfirem( 'Enabled Fields' ), array( $this, 'general_tab' ), 'gfirem_options' );
		add_settings_section( 'save_data', '', array( $this, "save_data" ), 'gfirem_options' );
		
		foreach ( $this->fields as $global_settings_tab_key => $global_settings_tab_data ) {
			if ( ! empty( $global_settings_tab_data->global_options ) ) {
				register_setting( $global_settings_tab_key, $global_settings_tab_key );
				add_settings_section( 'section_' . $global_settings_tab_key, '', array( $this, 'tab_content' ), $global_settings_tab_key );
			}
		}
	}
	
	public function general_tab() {
		_e_gfirem( '<i>Select witch field will be active in your system.</i>' );
		foreach ( $this->fields as $global_settings_tab_key => $global_settings_tab_data ) {
			add_settings_field( 'enabled_field' . $global_settings_tab_key, $global_settings_tab_data->name, function () use ( $global_settings_tab_data ) {
				$this->enabled_field( $global_settings_tab_data );
			}, 'gfirem_options', 'section_general' );
		}
	}
	
	public function enabled_field( $field ) {
		$this->get_view_for( 'enabled_' . $field->slug, 'checkbox' );
	}
	
	public function save_data() {
		submit_button( null, "primary", "gfirem_submit", false );
	}
	
	private function get_view_for( $setting, $type = "text", $domain = 'gfirem_options' ) {
		$general_option = get_option( $domain );
		$data           = '';
		if ( ! empty( $general_option[ $setting ] ) ) {
			$data = $general_option[ $setting ];
		}
		
		switch ( $type ) {
			case "checkbox":
				$value = checked( $data, 1, false ) . " value='1' ";
				break;
			default:
				$value = "value='" . $data . "'";
		}
		echo "<input name='" . $domain . "[" . $setting . "]' id='gfirem_" . $setting . "' type='" . $type . "' " . $value . " />";
	}
	
	public function tab_content() {
		foreach ( $this->fields as $global_settings_tab_key => $global_settings_tab_data ) {
			$active_tab = esc_attr( $_GET['tab'] );
			if ( ! empty( $active_tab ) && $active_tab != 'generic' && $global_settings_tab_key == $active_tab &&  ! empty( $global_settings_tab_data->global_options) ){
				$view_fnc = $global_settings_tab_data->global_options['view'][1];
				$global_settings_tab_data->$view_fnc();
			}
		}
	}
	
	/**
	 * Include styles in admin
	 */
	public function enqueue_style() {
		//TODO si esto no es necesario solo incluirlo en donde corresponda
		wp_enqueue_style( 'jquery' );
		wp_enqueue_style( 'gfirem', GFIREM_CSS_PATH . 'gfirem.css', array(), gfirem_manager::get_version() );
	}
	
	/**
	 * Include script
	 */
	public function enqueue_js() {
		//TODO si esto no es necesario solo incluirlo en donde corresponda
		wp_register_script( 'gfirem', GFIREM_JS_PATH . 'gfirem.js', array( "jquery" ), true );
		wp_enqueue_script( 'gfirem' );
	}
}