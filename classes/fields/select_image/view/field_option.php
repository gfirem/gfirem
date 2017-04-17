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
<tr>
    <td><label for="activate_zoom_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Activate Zoom" ) ?></label></td>
    <td>
        <select name="field_options[activate_zoom_<?php echo esc_attr( $field['id'] ) ?>]" id="activate_zoom_<?php echo esc_attr( $field['id'] ) ?>">
            <option <?php selected( esc_attr( $field['activate_zoom'] ), 'true' ) ?> value="true"><?php gfirem_manager::echo_translated( "True" ) ?></option>
            <option <?php selected( esc_attr( $field['activate_zoom'] ), 'false' ) ?> value="false"><?php gfirem_manager::echo_translated( "False" ) ?></option>
        </select>
    </td>
</tr>
<tr>
    <td><label for="scroll_zoom_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Activate Scroll Zoom" ) ?></label></td>
    <td>
        <select name="field_options[scroll_zoom_<?php echo esc_attr( $field['id'] ) ?>]" id="scroll_zoom_<?php echo esc_attr( $field['id'] ) ?>">
            <option <?php selected( esc_attr( $field['scroll_zoom'] ), 'true' ) ?> value="true"><?php gfirem_manager::echo_translated( "True" ) ?></option>
            <option <?php selected( esc_attr( $field['scroll_zoom'] ), 'false' ) ?> value="false"><?php gfirem_manager::echo_translated( "False" ) ?></option>
        </select>
    </td>
</tr>
