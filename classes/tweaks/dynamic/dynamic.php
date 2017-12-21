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
				'dynamic_field_target'        => '',
				'dynamic_field_target_value'  => '',
				'dynamic_field_unique_filter' => '',
			),
			__( 'New options to tweak your dynamic fields', 'gfirem-locale' ),
			array( 'name' => 'Dynamic/Lookup Field Tweaks', 'view' => array( $this, 'global_tab' ) ),
			gfirem_fs::$professional, true
		);
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ) {
			$field_global_options = get_option( $this->slug );
			if ( ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_filter_entries' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_filter_entries' ] == '1' ) ||
			     ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_unique_filter' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_unique_filter' ] == '1' )
			) {
				add_filter( 'frm_setup_new_fields_vars', array( $this, 'filter_dynamic_options__premium_only' ), 25, 2 );

				add_action( 'frm_field_options_form', array( $this, 'field_option_form__premium_only' ), 10, 3 );
				add_action( 'frm_update_field_options', array( $this, 'field_options__premium_only' ), 10, 3 );
			}
		}
	}

	public function field_option_form__premium_only( $field, $display, $values ) {
		if ( $field['type'] != 'data' && $field['type'] != 'lookup' ) {
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
		if ( ! empty( $field['get_values_form'] ) ) {
			$fields_for_filter = FrmField::get_all_for_form( $field['get_values_form'] );
		}
		$show_filter_unique = "";
		if ( $field['dynamic_field_unique_filter'] == "1" ) {
			$show_filter_unique = "checked='checked'";
		}

		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'dynamic_options.php';
	}

	public function field_options__premium_only( $field_options, $field, $values ) {
		if ( $field->type != 'data' && $field->type != 'lookup' ) {
			return $field_options;
		}

		foreach ( $this->defaults as $opt => $default ) {
			$field_options[ $opt ] = isset( $values['field_options'][ $opt . '_' . $field->id ] ) ? $values['field_options'][ $opt . '_' . $field->id ] : $default;
		}

		return $field_options;
	}


	public function filter_dynamic_options__premium_only( $values, $field ) {
		$field_global_options = get_option( $this->slug );
		if ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_filter_entries' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_filter_entries' ] == '1' ) {
			if ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_admin_filter' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_admin_filter' ] == '1' && current_user_can( 'manage_options' ) ) {
				return $values;
			}
			if ( ( $field->type == 'data' || $field->type == 'lookup' ) && ! empty( $values['options'] ) ) {
				$dynamic_field_target       = FrmField::get_option( $field, 'dynamic_field_target' );
				$dynamic_field_target_value = FrmField::get_option( $field, 'dynamic_field_target_value' );
				if ( ! empty( $dynamic_field_target ) && ! empty( $dynamic_field_target_value ) ) {
					$temp = null;
					if ( $field->type == 'data' ) {
						$temp                = $values;
						$temp['form_select'] = $dynamic_field_target;
						$field2_opts         = FrmProDynamicFieldsController::get_independent_options( $temp, $field );
					}
					if ( $field->type == 'lookup' ) {
						$temp                                    = $field;
						$temp->field_options['get_values_field'] = $dynamic_field_target;
						$field2_opts                             = $this->get_independent_lookup_field_values( $dynamic_field_target, $values );
					}
					if ( ! empty( $field2_opts ) ) {
						$position = 0;
						foreach ( $values['options'] as $id => $v ) {
							if($field->type === 'data') {
								$position = $id;
							}
							if ( ! empty( $v ) ) {
								$content = $this->replace_shortcode( null, $dynamic_field_target_value );
								if ( isset( $field2_opts[ $position ] ) && $field2_opts[ $position ] == $content ) {//Only include values where filtering field equals Yes
									continue;
								}
								if($field->type === 'lookup') {
									$position ++;
								}
								unset( $values['options'][ $position ] );
							}
						}
					}
					unset( $temp );
				}
			}
		}
		if ( ! empty( $field_global_options[ 'enabled_' . $this->slug . '_unique_filter' ] ) && $field_global_options[ 'enabled_' . $this->slug . '_unique_filter' ] == '1' && current_user_can( 'manage_options' ) ) {
			$dynamic_field_unique_filter = FrmField::get_option( $field, 'dynamic_field_unique_filter' );
			if ( ! empty( $dynamic_field_unique_filter ) ) {
				$values['options'] = array_unique( $values['options'] );
			}
		}

		return $values;
	}

	/**
	 * Get the meta values for an independent lookup field
	 *
	 * @since 2.01.0
	 *
	 * @param int $linked_field_id
	 * @param array $values
	 *
	 * @return array $options
	 */
	private function get_independent_lookup_field_values( $linked_field_id, $values ) {
		$linked_field = FrmField::getOne( $linked_field_id );
		if ( ! $linked_field ) {
			return array();
		}

		$args = array();

		if ( $this->need_to_filter_values_for_current_user( $values['id'], $values ) ) {
			$current_user = get_current_user_id();

			// If user isn't logged in, don't display any options
			if ( $current_user === 0 ) {
				return array();
			}

			$args['user_id'] = $current_user;
		}

		if ( FrmAppHelper::is_admin_page( 'formidable' ) ) {
			$args['limit'] = 500;
		}

		$options = FrmProEntryMeta::get_all_metas_for_field( $linked_field, $args );

		$options = $this->flatten_and_unserialize_meta_values( $options );
//		$this->get_unique_values( $options );
//		$this->order_values( $values['lookup_option_order'], $options );

		return $options;
	}

	/**
	 * Check if the values need to be filtered for the current user
	 *
	 * @return bool
	 */
	private function need_to_filter_values_for_current_user( $field_id, $field_options ) {
		$is_filter_needed = FrmField::is_option_true_in_array( $field_options, 'lookup_filter_current_user' ) && ! current_user_can( 'administrator' ) && ! FrmAppHelper::is_admin();

		return apply_filters( 'frm_lookup_is_current_user_filter_needed', $is_filter_needed, $field_id, $field_options );
	}

	/**
	 * If meta values are arrays (checkboxes, repeating fields, etc), flatten the values to a single-dimensional array
	 */
	private function flatten_and_unserialize_meta_values( $meta_values ) {
		$final_values = array();
		foreach ( $meta_values as $meta_val ) {

			$meta_val = maybe_unserialize( $meta_val );
			if ( is_array( $meta_val ) ) {
				$final_values = array_merge( $final_values, $meta_val );
			} else {
				$meta_val       = $this->decode_html_entities( $meta_val );
				$final_values[] = $meta_val;
			}
		}

		return $final_values;
	}

	/**
	 * Only get unique values in Lookup Fields
	 *
	 * @since 2.01.0
	 *
	 * @param array $final_values
	 */
	private function get_unique_values( &$final_values ) {
		$final_values = array_unique( $final_values );
		$final_values = array_values( $final_values );
	}

	/**
	 * Order the values in a Lookup Field
	 *
	 * @since 2.01.0
	 *
	 * @param string $order
	 * @param array $final_values
	 */
	private function order_values( $order, &$final_values ) {
		if ( ! $final_values ) {
			return;
		}

		if ( $order == 'ascending' || $order == 'descending' ) {
			natcasesort( $final_values );
			if ( $order == 'descending' ) {
				$final_values = array_reverse( $final_values );
			}
			$final_values = array_values( $final_values );
		}

		$final_values = apply_filters( 'frm_order_lookup_options', $final_values, $order );
	}

	/**
	 * Decode HTML entities recursively
	 */
	private function decode_html_entities( $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $key => $single_value ) {
				$value[ $key ] = self::decode_html_entities( $single_value );
			}
		} else {
			$value = html_entity_decode( $value );
		}

		return $value;
	}

	public function global_tab() {
		add_settings_field( 'section_dynamic', __( 'Filter Entries', 'gfirem-locale' ), array( $this, 'filter_entries' ), 'dynamic', 'section_dynamic' );
		add_settings_field( 'section_dynamic_unique', __( 'Unique Values', 'gfirem-locale' ), array( $this, 'unique_filter' ), 'dynamic', 'section_dynamic' );
		add_settings_field( 'section_dynamic_admin', __( 'Admin Filter', 'gfirem-locale' ), array( $this, 'admin_filter' ), 'dynamic', 'section_dynamic' );
	}

	public function filter_entries() {
		echo '<p ' . $this->disable_class_tag( 'p', gfirem_fs::$professional ) . '>';
		$this->get_view_for( 'enabled_' . $this->slug . '_filter_entries', 'checkbox', 'dynamic', array(), gfirem_fs::$professional );
		_e( 'If tick this you get a new options inside the dynamic/lookup field to filter the entries.', 'gfirem-locale' );
		echo '</p>';
	}

	public function admin_filter() {
		echo '<p ' . $this->disable_class_tag( 'p', gfirem_fs::$professional ) . '>';
		$this->get_view_for( 'enabled_' . $this->slug . '_admin_filter', 'checkbox', 'dynamic', array(), gfirem_fs::$professional );
		_e( 'If tick this the admin can avoid the above filter.', 'gfirem-locale' );
		echo '</p>';
	}

	public function unique_filter() {
		echo '<p ' . $this->disable_class_tag( 'p', gfirem_fs::$professional ) . '>';
		$this->get_view_for( 'enabled_' . $this->slug . '_unique_filter', 'checkbox', 'dynamic', array(), gfirem_fs::$professional );
		_e( 'If tick this a new option will be available to filter unique values to use in dynamic fields.', 'gfirem-locale' );
		echo '</p>';
	}
}