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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class gfirem_manager {
	
	private static $plugin_slug = 'gfirem';
	protected static $version;
	
	public function __construct() {
		self::$version  = self::$version  = '1.0.0';
		
		require_once GFIREM_CLASSES_PATH . 'gfirem_log.php';
		
		try {
			if ( self::is_formidable_active() ) {
				include GFIREM_CLASSES_PATH . 'gfirem_admin.php';
				new gfirem_admin();
				
				if ( gfirem_fs::getFreemius()->is_paying() ) {
					require_once GFIREM_FIELDS_PATH.'gfirem_field_base.php';
					//Override the register action from formidable
					if(self::is_formidable_registration_active()) {
						require_once 'gfirem_formidable_register_action_override.php';
						if ( class_exists( 'gfirem_formidable_register_action_override' ) && class_exists( 'FrmRegAction' ) ) {
//						$register_action = new gfirem_formidable_register_action_override();
							add_action( 'frm_registered_form_actions', array( $this, 'override_register_actions' ), 10 );
						}
					}
					
					
					
					require_once GFIREM_FIELDS_PATH.'date_time_field/date_time_field.php';
					new date_time_field();
					require_once GFIREM_FIELDS_PATH.'switch_button/switch_button.php';
					new switch_button();
					require_once GFIREM_FIELDS_PATH.'user_list/user_list.php';
					new user_list();
				}
			}
		} catch ( Exception $ex ) {
			gfirem_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => gfirem_manager::get_slug(),
				'object_subtype' => 'loading_dependency',
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	/**
	 * Override Formidable Register action
	 *
	 * @param $actions
	 *
	 * @return mixed
	 */
	public function override_register_actions( $actions ) {
		$actions['register'] = 'gfirem_formidable_register_action_override';
		
		return $actions;
	}
	
	public static function load_plugins_dependency() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	
	public static function is_formidable_active() {
		self::load_plugins_dependency();
		
		return is_plugin_active( 'formidable/formidable.php' );
	}
	
	public static function is_formidable_registration_active() {
		self::load_plugins_dependency();
		
		return is_plugin_active( 'formidable-registration/formidable-registration.php' );
	}
	
	/**
	 * Get plugins version
	 *
	 * @return mixed
	 */
	static function get_version() {
		return self::$version;
	}
	
	/**
	 * Get plugins slug
	 *
	 * @return string
	 */
	static function get_slug() {
		return self::$plugin_slug;
	}
	
	public static function load_field_template( $part ) {
		$template = locate_template( array( 'templates/' . $part . '.php' ) );
		if ( ! $template ) {
			return GFIREM_TEMPLATES_PATH . $part . ".php";
		} else {
			return $template;
		}
	}
}