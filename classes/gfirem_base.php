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
	public $license;
	public $site;
	public $is_free;
	public $is_start;
	public $is_professional;
	public $is_premium_only;
	
	static $starter_plan_id = 'starter';
	static $professional_plan_id = 'professional';
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
			$this->site            = gfirem_fs::getFreemius()->get_site();
			$this->plan            = gfirem_fs::get_current_plan();
			
			
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
	
	/**
	 * Echo the correct input, take in count extra tags and plan
	 *
	 * @param $setting
	 * @param string $type
	 * @param string $option_domain
	 * @param array $args
	 * @param string $plan
	 */
	public function get_view_for( $setting, $type = "text", $option_domain = 'gfirem_options', $args = array(), $plan = 'free' ) {
		$general_option = get_option( $option_domain );
		$data           = '';
		if ( ! empty( $general_option[ $setting ] ) ) {
			$data = $general_option[ $setting ];
		}
		
		$disable_for_plan = '';
		$field_loaded = gfirem_manager::$fields_loaded;
		foreach ( $field_loaded[ $this->get_plan()  ] as $loaded_key => $loaded_field ) {
			if ( $setting != 'enabled_' . $loaded_key ) {
				$disable_for_plan = $this->disable_input_tag( $type, $plan );
			}
		}
		
		switch ( $type ) {
			case "checkbox":
				$value = checked( $data, 1, false ) . " value='1' ";
				break;
			default:
				$value = "value='" . $data . "'";
		}
		$args_txt = '';
		if ( ! empty( $args ) ) {
			foreach ( $args as $arg_key => $arg_val ) {
				$args_txt .= $arg_key . '="' . $arg_val . '"';
			}
		}
		echo "<input $disable_for_plan name='" . $option_domain . "[" . $setting . "]' id='gfirem_" . $setting . "' type='" . $type . "' " . $value . " " . $args_txt . " />";
	}
}