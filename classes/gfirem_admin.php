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
	
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		
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
		gfirem_fs::getFreemius()->get_logger()->entrance();
		gfirem_fs::getFreemius()->_account_page_load();
		gfirem_fs::getFreemius()->_account_page_render();
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