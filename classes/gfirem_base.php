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
class gfirem_base {
	private $debug;
	public $is_paying;
	public $is_free;
	public $is_start;
	public $is_professional;
	public $is_premium_only;
	
	static $starter_plan_id = 'starter';
	static $professional_plan_id = 'professional';
	private $license;
	private $plan;
	
	public function __construct( $debug = false ) {
		//Comment the next line to disable the forced debug
//		$debug       = true;
		$this->debug = $debug;
		if ( ! $debug ) {
			$this->is_paying       = gfirem_fs::getFreemius()->is_paying();
			$this->is_free         = gfirem_fs::getFreemius()->is_free_plan();
			$this->is_start        = gfirem_fs::getFreemius()->is_plan( self::$starter_plan_id );
			$this->is_professional = gfirem_fs::getFreemius()->is_plan( self::$professional_plan_id );
			$this->is_premium_only = gfirem_fs::getFreemius()->is__premium_only();
			$this->license         = gfirem_fs::getFreemius()->get_plan();
			if ( ! empty( $this->license ) ) {
				$this->plan = $this->license->name;
			} else {
				$this->plan = 0;
			}
			
			return;
		} else if ( ! is_array( $debug ) ) {
			$debug = array( 'is_paying' => false, 'is_free_plan' => true, 'starter' => false, 'professional' => false, 'is_premium_only' => false, 'plan' => 'free' ); //Free
//			$debug = array( 'is_paying' => true, 'is_free_plan' => false, 'starter' => true, 'professional' => false, 'is_premium_only' => true, 'plan' => self::$starter_plan_id  ); //Starter
//			$debug = array( 'is_paying' => true, 'is_free_plan' => false, 'starter' => false, 'professional' => true, 'is_premium_only' => true, 'plan' => self::$professional_plan_id  );//Professional
		}
		$this->is_paying       = $debug['is_paying'];
		$this->is_free         = $debug['is_free_plan'];
		$this->is_start        = $debug['starter'];
		$this->is_professional = $debug['professional'];
		$this->is_premium_only = $debug['is_premium_only'];
		$this->plan            = $debug['plan'];
	}
	
	public function get_plan() {
		return $this->plan;
	}
	
	public function disable_class_tag( $tag, $plan = 'professional', $force = false ) {
		if ( $force || ( ! gfirem_fs::getFreemius()->is_plan( $plan ) ) ) {
			switch ( $tag ) {
				default:
					$class = 'gfirem-disabled';
			}
			
			return 'class="' . $class . '"';
		}
		
		return '';
	}
	
	
	public function disable_input_tag( $type, $plan = 'professional', $force = false ) {
		if ( $force || ( ! gfirem_fs::getFreemius()->is_plan( $plan ) ) ) {
			switch ( $type ) {
				case 'button':
					$attr = 'disabled';
					break;
				default:
					$attr = 'disabled="disabled"';
			}
			
			return $attr;
		}
		
		return '';
	}
	
	public function is_plan( $plan_id ) {
		if ( $this->debug ) {
			$result = ( $this->plan == $plan_id );
		} else {
			$result = gfirem_fs::getFreemius()->is_plan( $plan_id );
		}
		
		return $result;
	}
	
	public function needs_upgrade() {
		return ( $this->is_free || $this->is_start ) && ! $this->is_professional;
	}
}