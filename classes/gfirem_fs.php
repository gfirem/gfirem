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

if ( ! defined( 'WPINC' ) ) {
	die;
}

class gfirem_fs {
	
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
	
	public function __construct() {
		$this->gfirem_fs();
	}
	

	
	/**
	 * @return Freemius
	 */
	public static function getFreemius() {
		global $gfirem_fs;
		
		return $gfirem_fs;
	}
	
	// Create a helper function for easy SDK access.
	public function gfirem_fs() {
		global $gfirem_fs;
		
		if ( ! isset( $gfirem_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/include/freemius/start.php';
			
			$gfirem_fs = fs_dynamic_init( array(
				'id'                  => '848',
				'slug'                => 'gfirem',
				'type'                => 'plugin',
				'public_key'          => 'pk_47201a0d3289152f576cfa93e7159',
				'is_premium'          => true,
				'has_premium_version' => true,
				'has_addons'          => false,
				'has_paid_plans'      => true,
				'menu'                => array(
					'slug'       => 'gfirem',
					'first-path' => 'admin.php?page=gfirem',
					'support'    => false,
				),
				// Set the SDK to work in a sandbox mode (for development & testing).
				// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
				'secret_key'          => 'sk_r~jmr--.4&!Q@<Neu>y1>UV)PB.?n',
			) );
		}

		return $gfirem_fs;
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
}