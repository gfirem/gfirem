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

class user_list extends gfirem_field_base {
	
	public function __construct() {
		parent::__construct( 'user_list', _gfirem( 'User List' ),
			array( 'roles' => 'editor', ),
			_gfirem( 'Show list of user from selected role in frontend' )
		);
	}
	
	protected function inside_field_options( $field, $display, $values ) {
		include dirname( __FILE__ ) . '/view/field_option.php';
	}
	
	protected function field_front_view( $field, $field_name, $html_id ) {
		$field['value'] = stripslashes_deep( $field['value'] );
		$field['roles'] = stripslashes_deep( $field['roles'] );
		
		$users = $this->getUserList( $field['roles'] );
		include dirname( __FILE__ ) . '/view/field_front_view.php';
	}
	
	protected function process_short_code( $replace_with, $tag, $attr, $field ) {
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
		
		return $replace_with;
	}
	
	/**
	 * Get user list for given role.
	 *
	 * @param $roles
	 *
	 * @return array
	 */
	private function getUserList( $roles ) {
		global $wpdb;
		$users   = get_users( array( 'fields' => array( 'ID', 'user_login', 'display_name' ), 'role__in' => array( $roles ), 'blog_id' => $GLOBALS['blog_id'], 'orderby' => 'display_name' ) );
		$options = array( '' => '' );
		foreach ( $users as $user ) {
			$options[ $user->ID ] = ( ! empty( $user->display_name ) ) ? $user->display_name : $user->user_login;
		}
		
		return $options;
	}
	
	
}