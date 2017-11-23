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
	public static $free = 0;
	public static $starter = 'starter';
	public static $professional = 'professional';
	public static $classes;

	public function __construct($classes) {
		$this->gfirem_fs();
		self::$classes = $classes;
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
				'has_addons'          => true,
				'has_paid_plans'      => true,
				'is_org_compliant'    => false,
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
		do_action( 'gfirem_fs_loaded' );
		return $gfirem_fs;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @param $classes
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance($classes) {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self($classes);
		}
		
		return self::$instance;
	}
	
	public static function get_current_plan() {
		$site = gfirem_fs::getFreemius()->get_site();
		if ( ! empty( $site ) ) {
			if ( ! empty( $site->plan ) ) {
				if ( ! empty( $site->plan ) ) {
					return $site->plan->name;
				} else {
					return 'free';
				}
			}
		}
		
		return 'free';
	}
}