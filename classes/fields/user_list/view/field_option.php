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
<tr>
    <td><label><?php gfirem_manager::echo_translated( "Roles" ) ?></label></td>
    <td>
        <label for="label1_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Select user rol, by default Editor" ) ) ?></label>
        <select name="field_options[roles_<?php echo esc_attr( $field['id'] ) ?>]" class="frm_long_input" id="roles_<?php echo esc_attr( $field['id'] ) ?>">
			<?php wp_dropdown_roles( $field['roles'] ); ?>
        </select>
    </td>
</tr>
