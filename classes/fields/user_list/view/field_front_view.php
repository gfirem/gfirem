<?php
/**
 * @package WordPress
 * @subpackage Formidable,
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
?>
<select id='field_<?= $field['field_key'] ?>' name='item_meta[<?= $field['id'] ?>]'>
	<?php foreach ( $users as $id => $key ) {
		$select = ( $id == $field['value'] ) ? 'selected="selected"' : "";
		echo '<option ' . $select . ' value="' . $id . '">' . $key . '</option>';
	} ?>
</select>
