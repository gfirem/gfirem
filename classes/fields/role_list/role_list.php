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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class role_list extends gfirem_field_base {
	
	public function __construct() {
		parent::__construct( 'role_list', gfirem_manager::translate( 'Role List' ),
			array( 'default_role' => 'subscriber', ),
			gfirem_manager::translate( 'Show list of roles to selected in the frontend. It integrate with Formidable Registration' ),
			array(), gfirem_fs::$professional
		);
	}
	
	protected function inside_field_options( $field, $display, $values ) {
	
	}
	
	protected function field_front_view( $field, $field_name, $html_id ) {
		$field['value'] = stripslashes_deep( $field['value'] );
		$field['default_role'] = stripslashes_deep( $field['default_role'] );
		
		$roles = $this->get_wp_editable_roles();
		include dirname( __FILE__ ) . '/view/field_front_view.php';
	}

	public function get_wp_editable_roles(){
		$all_roles = wp_roles()->roles;

		/**
		 * Filters the list of editable roles.
		 *
		 * @since 2.8.0
		 *
		 * @param array $all_roles List of roles.
		 */
		$editable_roles = apply_filters( 'editable_roles', $all_roles );

		return $editable_roles;
	}
	
	protected function process_short_code( $replace_with, $tag, $attr, $field ) {
		if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ) {
			$internal_attr = shortcode_atts( array(
				'show' => 'id',
			), $attr );
			
			if ( $internal_attr['show'] == 'id' ) {
				return $replace_with;
			}
			
			$user = get_userdata( $replace_with );
			if ( ! empty( $user ) ) {
				$user_field = $internal_attr['show'];
				if ( property_exists( $user->data, $user_field ) ) {
					return esc_html( $user->$user_field );
				}
			}
		}
		return $replace_with;
	}
}