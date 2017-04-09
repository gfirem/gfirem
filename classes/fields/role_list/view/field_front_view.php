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
?>
<select id='field_<?= $field['field_key'] ?>' name='item_meta[<?= $field['id'] ?>]'>
	<?php foreach ( $roles as $role => $details ) {
		$name = translate_user_role($details['name'] );
		$select = ( $role == $field['value'] ) ? 'selected="selected"' : "";
		echo '<option ' . $select . ' value="' . esc_attr($role) . '">' . esc_attr($name) . '</option>';
	} ?>
</select>
