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
    <td><label for="library_title_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Library Title" ) ?></label></td>
    <td>
        <input class="frm_classes frm_long_input" type="text" name="field_options[library_title_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['library_title'] ) ?>" id="library_title_<?php echo esc_attr( $field['id'] ) ?>">
    </td>

</tr>
<tr>
    <td><label for="library_button_title_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Chose image text" ) ?></label></td>
    <td>
        <input class="frm_classes frm_long_input" type="text" name="field_options[library_button_title_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['library_button_title'] ) ?>" id="library_button_title_<?php echo esc_attr( $field['id'] ) ?>">
    </td>

</tr>
<tr>
    <td><label for="button_title_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Button text" ) ?></label></td>
    <td>
        <input class="frm_classes frm_long_input" type="text" name="field_options[button_title_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['button_title'] ) ?>" id="button_title_<?php echo esc_attr( $field['id'] ) ?>">
    </td>

</tr>
<tr>
    <td><label for="button_css_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Button Class" ) ?></label></td>
    <td>
        <input class="frm_classes frm_long_input" type="text" name="field_options[button_css_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['button_css'] ) ?>" id="button_css_<?php echo esc_attr( $field['id'] ) ?>">
    </td>

</tr>

