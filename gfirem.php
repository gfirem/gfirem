<?php
/**
 *
 * @since             1.0.0
 * @package           gfirem
 *
 * @wordpress-plugin
 * Plugin Name:       GFireM Fields
 * Description:       Add a set of extra fields to formidable to improve your work
 * Version:           1.1.1
 * Author:            gfirem
 * License:           Apache License 2.0
 * License URI:       http://www.apache.org/licenses/
 *
 *
 * Copyright 2017 Guillermo Figueroa Mesa (email: gfirem@gmail.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'gfirem' ) ) {
	
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
			require_once GFIREM_CLASSES_PATH . 'gfirem_manager.php';
			new gfirem_manager();
		}
		
		private function constants() {
			define( 'GFIREM_CSS_PATH', plugin_dir_url( __FILE__ ) . 'assets/css/' );
			define( 'GFIREM_VIEW_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR );
			define( 'GFIREM_CLASSES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR );
			define( 'GFIREM_FIELDS_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR );
			define( 'GFIREM_TEMPLATES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR );
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
	
	require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'WP_Requirements.php';
	require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'gfirem_check_requirements.php';
	$gfirem_fields_requirements = new gfirem_check_requirements( 'gfirem-locale' );
	if ( $gfirem_fields_requirements->satisfied() ) {
		require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'gfirem_fs.php';
		gfirem_fs::get_instance();
		add_action( 'plugins_loaded', array( 'gfirem', 'get_instance' ), 1 );
	} else {
		$fauxPlugin = new WP_Faux_Plugin( 'GFireM Fields', $gfirem_fields_requirements->getResults() );
		$fauxPlugin->show_result( plugin_basename( __FILE__ ) );
	}
	
	
}
