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
		$this->global_settings_tabs = apply_filters( 'gfirem_add_setting_tabs', array() );
		$active_tab                 = 'generic';
		$option                     = 'gfirem_options';
		if ( ! empty( $_GET['tab'] ) ) {
			$active_tab = $_GET['tab'];
		}
		$this->global_settings_tabs = apply_filters( 'gfirem_add_setting_tabs', array() );
		foreach ( $this->global_settings_tabs as $global_settings_tab_key => $global_settings_tab_data ) {
			if ( ! empty( $active_tab ) && $active_tab != 'generic' && $global_settings_tab_key == $active_tab ) {
				$option = $global_settings_tab_key;
				break;
			}
		}
		include_once( GFIREM_VIEW_PATH . 'html_admin.php' );
	}
	
	public function register_admin_settings() {
		register_setting( 'gfirem_options', 'gfirem_options' );
		add_settings_section( 'section_general', '', array( $this, 'general_tab' ), 'gfirem_options' );
		
		$this->global_settings_tabs = apply_filters( 'gfirem_add_setting_tabs', array() );
		foreach ( $this->global_settings_tabs as $global_settings_tab_key => $global_settings_tab_data ) {
			register_setting( $global_settings_tab_key, $global_settings_tab_key );
			add_settings_section( 'section_' . $global_settings_tab_key, '', array( $this, 'tab_content' ), $global_settings_tab_key );
		}
	}
	
	public function general_tab() {
		add_settings_field( 'tabs_shop', __( '<b>Shop Settings</b>', 'wc4bp' ), array( $this, 'wc4bp_shop_tabs' ), 'gfirem_options', 'section_general' );
	}
	
	public function wc4bp_shop_tabs(){
		echo 'Generixx';
	}
	
	public function tab_content() {
		$this->global_settings_tabs = apply_filters( 'gfirem_add_setting_tabs', array() );
		foreach ( $this->global_settings_tabs as $global_settings_tab_key => $global_settings_tab_data ) {
			$active_tab = esc_attr( $_GET['tab'] );
			if ( ! empty( $active_tab ) && $active_tab != 'generic' && $global_settings_tab_key == $active_tab ) {
				$global_settings_tab_data['view'][0]->$global_settings_tab_data['view'][1]();
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