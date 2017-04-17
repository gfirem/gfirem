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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ) {
	if ( class_exists( "FrmRegAction" ) ) {
		class gfirem_formidable_register_action_override extends FrmRegAction {
			function __construct() {
				parent::__construct();
			}
			
			function form( $form_action, $args = array() ) {
				$form = isset( $args['form'] ) ? $args['form'] : false;
				
				if ( ! $form ) {
					return;
				}
				
				global $wpdb;
				
				$fields = FrmField::getAll( $wpdb->prepare( 'fi.form_id=%d', $form->id ) . " and fi.type not in ('end_divider', 'divider', 'html', 'break', 'captcha', 'rte')", ' ORDER BY field_order' );
				
				$show_auto_login = FrmRegActionHelper::is_auto_login_visible( $form_action );
				
				$path_to_template = gfirem_manager::load_field_template( '_register_settings' );
				include "$path_to_template";
			}
		}
	}
}