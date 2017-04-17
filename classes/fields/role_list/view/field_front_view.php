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
if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ): ?>
    <select id='field_<?= $field['field_key'] ?>' name='item_meta[<?= $field['id'] ?>]'>
		<?php foreach ( $roles as $role => $details ) {
			$name   = translate_user_role( $details['name'] );
			$select = ( $role == $field['value'] ) ? 'selected="selected"' : "";
			echo '<option ' . $select . ' value="' . esc_attr( $role ) . '">' . esc_attr( $name ) . '</option>';
		} ?>
    </select>
<?php endif; ?>