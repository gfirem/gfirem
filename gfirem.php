<?php
/**
 *
 * @since             1.0.0
 * @package           gfirem
 *
 * @wordpress-plugin
 * Plugin Name:       GFireM Fields
 * Description:       Add a set of extra fields to formidable and tweaks to improve your work!
 * Version:           2.0.0
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
	$classes_path = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
	require_once $classes_path . 'gfirem_fs.php';
	gfirem_fs::get_instance( $classes_path );

	class gfirem {

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		public static $assets;
		public static $view;
		public static $classes;
		public static $fields;
		public static $tweaks;
		public static $templates;

		/**
		 * Initialize the plugin.
		 */
		private function __construct() {
			self::$assets    = plugin_dir_url( __FILE__ ) . 'assets/css/';
			self::$view      = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;
			self::$classes   = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
			self::$fields    = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR;
			self::$tweaks    = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'tweaks' . DIRECTORY_SEPARATOR;
			self::$templates = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'WP_Requirements.php';
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'gfirem_check_requirements.php';
			$gfirem_fields_requirements = new gfirem_check_requirements( 'gfirem-locale' );
			if ( $gfirem_fields_requirements->satisfied() ) {
				$this->constants();
				$this->load_plugin_textdomain();
				require_once GFIREM_CLASSES_PATH . 'gfirem_manager.php';
				new gfirem_manager();
			} else {
				$fauxPlugin = new WP_Faux_Plugin( 'GFireM Fields', $gfirem_fields_requirements->getResults() );
				$fauxPlugin->show_result( plugin_basename( __FILE__ ) );
			}


		}

		private function constants() {
			define( 'GFIREM_CSS_PATH', self::$assets );
			define( 'GFIREM_VIEW_PATH', self::$view );
			define( 'GFIREM_CLASSES_PATH', self::$classes );
			define( 'GFIREM_FIELDS_PATH', self::$fields );
			define( 'GFIREM_TWEAKS_PATH', self::$tweaks );
			define( 'GFIREM_TEMPLATES_PATH', self::$templates );
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
