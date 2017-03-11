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

/**
 *
 * @since             1.0.0
 * @package           gfirem
 *
 * @wordpress-plugin
 * Plugin Name:       GFireM Fields
 * Description:       Add a set of extra fields to formidable to improve your work
 * Version:           1.0.0
 * Author:            gfirem
 * License:           Apache License 2.0
 * License URI:       http://www.apache.org/licenses/
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'gfirem' ) ) {
	
	require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'gfirem_fs.php';
	gfirem_fs::get_instance();
	
	class gfirem {
		
		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;
		
		/**
		 * Initialize the plugin.
		 */
		private function __construct() {
			$this->constants();
			$this->load_plugin_textdomain();
			require_once GFIREM_CLASSES_PATH . 'gfirem_override.php';
			require_once GFIREM_CLASSES_PATH . '/include/WP_Requirements.php';
			require_once GFIREM_CLASSES_PATH . 'gfirem_requirements.php';
			$this->requirements = new gfirem_requirements( 'gfirem-locale' );
			if ( $this->requirements->satisfied() ) {
				require_once GFIREM_CLASSES_PATH . 'gfirem_manager.php';
				new gfirem_manager();
			} else {
				$fauxPlugin = new WP_Faux_Plugin( _gfirem( 'GFireM Fields' ), $this->requirements->getResults() );
				$fauxPlugin->show_result( GFIREM_BASE_NAME );
			}
		}
		
		private function constants() {
			define( 'GFIREM_BASE_NAME', plugin_basename( __FILE__ ) );
			define( 'GFIREM_BASE_FILE', trailingslashit( wp_normalize_path( plugin_dir_path( __FILE__ ) ) ) . 'gfirem.php' );
			define( 'GFIREM_URL_PATH', trailingslashit( wp_normalize_path( plugin_dir_url( __FILE__ ) ) ) );
			define( 'GFIREM_CSS_PATH', GFIREM_URL_PATH . 'assets/css/' );
			define( 'GFIREM_JS_PATH', GFIREM_URL_PATH . 'assets/js/' );
			define( 'GFIREM_VIEW_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR );
			define( 'GFIREM_CLASSES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR );
			define( 'GFIREM_FIELDS_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR );
			define( 'GFIREM_TEMPLATES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR  );
		}
		
		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			
			return self::$instance;
		}
		
		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'gfirem-locale', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}
	}
	
	add_action( 'plugins_loaded', array( 'gfirem', 'get_instance' ), 1 );
}
