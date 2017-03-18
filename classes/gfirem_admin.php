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
class gfirem_admin extends gfirem_base {
	private $loaded_fields = array();
	private $fields = array();
	
	public function __construct( $fields ) {
		parent::__construct();
		$this->loaded_fields = $fields[ $this->get_plan() ];
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_admin_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
	}
	
	/**
	 * Adding the Admin Page
	 */
	public function admin_menu() {
		add_menu_page( _gfirem( 'GFireM Fields' ), _gfirem( 'GFireM Fields' ), 'manage_options', gfirem_manager::get_slug(), array( $this, 'screen' ), 'dashicons-smiley' );
	}
	
	/**
	 * Handle menu callback. Here i load the form view with the header
	 */
	public function screen() {
		$this->fields = apply_filters( 'gfirem_register_field', array() );
		$active_tab   = 'generic';
		$option       = 'gfirem_options';
		if ( ! empty( $_GET['tab'] ) ) {
			$active_tab = $_GET['tab'];
		}
		foreach ( $this->fields as $global_settings_tab_key => $global_settings_tab_data ) {
			if ( ! empty( $active_tab ) && $active_tab != 'generic' && ! empty( $global_settings_tab_data->global_options )
			     && $global_settings_tab_key == $active_tab && array_key_exists( $global_settings_tab_key, $this->loaded_fields )
			) {
				$option = $global_settings_tab_key;
				break;
			}
		}
		include_once( GFIREM_VIEW_PATH . 'html_admin.php' );
	}
	
	/**
	 * Register all setting to the main plugins and register all modules
	 */
	public function register_admin_settings() {
		$this->fields = apply_filters( 'gfirem_register_field', array() );
		//Options for the general tab
		register_setting( 'gfirem_options', 'gfirem_options' );
		add_settings_section( 'section_general', _gfirem( 'Enabled Fields' ), array( $this, 'general_tab' ), 'gfirem_options' );
		add_settings_section( 'save_data', '', array( $this, "save_data" ), 'gfirem_options' );
		
		foreach ( $this->fields as $global_settings_tab_key => $global_settings_tab_data ) {
			if ( ! empty( $global_settings_tab_data->global_options ) && array_key_exists( $global_settings_tab_key, $this->loaded_fields ) ) {
				register_setting( $global_settings_tab_key, $global_settings_tab_key );
				add_settings_section( 'section_' . $global_settings_tab_key, '', array( $this, 'tab_content' ), $global_settings_tab_key );
			}
		}
	}
	
	/**
	 * Handle the main tab for the plugins where the user enabled and disable all the fields
	 */
	public function general_tab() {
		_e_gfirem( '<i>Select witch field will be active in your system.</i>' );
		foreach ( $this->fields as $global_settings_tab_key => $global_settings_tab_data ) {
			add_settings_field( 'enabled_field' . $global_settings_tab_key, $global_settings_tab_data->name, function () use ( $global_settings_tab_data ) {
				$this->enabled_field( $global_settings_tab_data );
			}, 'gfirem_options', 'section_general' );
			
		}
	}
	
	/**
	 * The function to print each field enabled/disabled checkbox
	 *
	 * @param $field
	 */
	public function enabled_field( $field ) {
		echo '<p ' . $this->disable_class_tag( 'p', $field->plan ) . '>';
		$this->get_view_for( 'enabled_' . $field->slug, 'checkbox', 'gfirem_options', array(), $field->plan );
		echo $field->description;
		echo '</p>';
		
	}
	
	/**
	 * Show the save button
	 */
	public function save_data() {
		submit_button( null, "primary", "gfirem_submit", false );
	}
	
	/**
	 * Echo the correct input, take in count extra tags and plan
	 *
	 * @param $setting
	 * @param string $type
	 * @param string $domain
	 * @param array $args
	 * @param string $plan
	 */
	private function get_view_for( $setting, $type = "text", $domain = 'gfirem_options', $args = array(), $plan = 'free' ) {
		$general_option = get_option( $domain );
		$data           = '';
		if ( ! empty( $general_option[ $setting ] ) ) {
			$data = $general_option[ $setting ];
		}
		
		$disable_for_plan = '';
		foreach ( gfirem_manager::$fields_loaded[ $this->get_plan() ] as $loaded_key => $loaded_field ) {
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
		echo "<input $disable_for_plan name='" . $domain . "[" . $setting . "]' id='gfirem_" . $setting . "' type='" . $type . "' " . $value . " " . $args_txt . " />";
	}
	
	/**
	 * Show the content inside the tab of field when it need a globals options
	 */
	public function tab_content() {
		foreach ( $this->fields as $global_settings_tab_key => $global_settings_tab_data ) {
			$active_tab = esc_attr( $_GET['tab'] );
			if ( ! empty( $active_tab ) && $active_tab != 'generic' && $global_settings_tab_key == $active_tab
			     && ! empty( $global_settings_tab_data->global_options ) && array_key_exists( $global_settings_tab_key, $this->loaded_fields )
			     && gfirem_manager::is_enabled( $global_settings_tab_key )
			) {
				$view_fnc = $global_settings_tab_data->global_options['view'][1];
				$global_settings_tab_data->$view_fnc();
			}
		}
	}
	
	/**
	 * Include styles in admin
	 *
	 * @param $hook
	 */
	public function enqueue_style($hook) {
		if($hook == 'toplevel_page_gfirem') {
			wp_enqueue_style( 'jquery' );
			wp_enqueue_style( 'gfirem', GFIREM_CSS_PATH . 'gfirem.css', array(), gfirem_manager::get_version() );
		}
	}
}