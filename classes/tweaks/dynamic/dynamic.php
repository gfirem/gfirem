<?php

/**
 * @package    WordPress
 * @subpackage Formidable, gfirem
 * @author     GFireM
 * @copyright  2017
 * @link       http://www.gfirem.com
 * @license    http://www.apache.org/licenses/
 *
 */
class dynamic extends gfirem_field_base {
	
	public function __construct() {
		parent::__construct( 'dynamic', __( 'Dynamic Field', 'gfirem-locale' ),
			array(
				'dynamic_field_target'       => '',
				'dynamic_field_target_value' => '',
			),
			__( 'New options to tweak your dynamic fields', 'gfirem-locale' ),
			array( 'name' => 'Dynamic Field Tweaks', 'view' => array( $this, 'global_tab' ) ),
			gfirem_fs::$professional, true
		);
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ) {
			$field_global_options = get_option( $this->slug );
			if ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_filter_entries' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_filter_entries' ] == '1' ) {
				add_filter( 'frm_setup_new_fields_vars', array( $this, 'filter_dynamic_options__premium_only' ), 25, 2 );
				
				add_action( 'frm_field_options_form', array( $this, 'field_option_form__premium_only' ), 10, 3 );
				add_action( 'frm_update_field_options', array( $this, 'field_options__premium_only' ), 10, 3 );
			}
		}
//		$this->slug = 'data';//HAck to include the options inside the existing fields
	}
	
	public function field_option_form__premium_only( $field, $display, $values ) {
		if ( $field['type'] != 'data' ) {
			return;
		}
		
		foreach ( $this->defaults as $k => $v ) {
			if ( ! isset( $field[ $k ] ) ) {
				$field[ $k ] = $v;
			}
		}
		
		$fields_for_filter = array();
		if ( ! empty( $field['form_select'] ) ) {
			$form_target       = FrmField::get_type( $field['form_select'], 'form_id' );
			$fields_for_filter = FrmField::get_all_for_form( $form_target );
		}
		
		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'dynamic_options.php';
	}
	
	public function field_options__premium_only( $field_options, $field, $values ) {
		if ( $field->type != 'data' ) {
			return $field_options;
		}
		
		foreach ( $this->defaults as $opt => $default ) {
			$field_options[ $opt ] = isset( $values['field_options'][ $opt . '_' . $field->id ] ) ? $values['field_options'][ $opt . '_' . $field->id ] : $default;
		}
		
		return $field_options;
	}
	
	
	public function filter_dynamic_options__premium_only( $values, $field ) {
		if ( $field->type == 'data' && ! empty( $values['options'] ) && ! current_user_can( 'manage_options' ) ) {
			$dynamic_field_target       = FrmField::get_option( $field, 'dynamic_field_target' );
			$dynamic_field_target_value = FrmField::get_option( $field, 'dynamic_field_target_value' );
			if ( ! empty( $dynamic_field_target ) && ! empty( $dynamic_field_target_value ) ) {
				$temp                = $values;
				$temp['form_select'] = $dynamic_field_target;
				$field2_opts         = FrmProDynamicFieldsController::get_independent_options( $temp, $field );
				foreach ( $values['options'] as $id => $v ) {
					$content = $this->replace_shortcode( null, $dynamic_field_target_value );
					if ( isset( $field2_opts[ $id ] ) && ( $v == '' || $field2_opts[ $id ] == $content ) ) {//Only include values where filtering field equals Yes
						continue;
					}
					unset( $values['options'][ $id ] );
				}
				unset( $temp );
			}
		}
		
		return $values;
	}
	
	public function global_tab() {
		add_settings_field( 'section_dynamic', __( 'Filter Entries', 'gfirem-locale' ), array( $this, 'filter_entries' ), 'dynamic', 'section_dynamic' );
	}
	
	public function filter_entries() {
		echo '<p ' . $this->disable_class_tag( 'p', gfirem_fs::$professional ) . '>';
		$this->get_view_for( 'enabled_' . $this->slug . '_filter_entries', 'checkbox', 'dynamic', array(), gfirem_fs::$professional );
		_e( 'If tick this you get a new options inside the dynamic field to filter the entries.', 'gfirem-locale' );
		echo '</p>';
	}
}