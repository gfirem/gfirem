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
	<td><label for="height_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Height" ) ?></label></td>
	  <td>
        <label for="height_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Height of the signature pad, by default '150'. " ) ) ?></label>

        <input type="number" name="field_options[height_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['height'])?>"  id="height_<?php echo esc_attr( $field['id'] ) ?>">
    </td>

</tr>
<tr>
<td><label for="width_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Width" ) ?></label></td>
	  <td>
        <label for="width_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Width of the signature pad, by default '300'. " ) ) ?></label>

        <input type="number" name="field_options[width_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['width'])?>"  id="width_<?php echo esc_attr( $field['id'] ) ?>">
    </td>

</tr>
<tr>
	  <td><label for="pencolor_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "penColor" ) ?></label></td>
	  <td>
        <label for="pencolor_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Color used to draw the lines. Can be any color format accepted by context.fillStyle. Defaults to 'black'" ) ) ?></label>

        <input type="text" name="field_options[pencolor_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['pencolor'])?>" class="pen-color-field" id="pencolor_<?php echo esc_attr( $field['id'] ) ?>">
    </td>
</tr>
<tr>
    <td><label for="backgroundcolor_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Background color" ) ?></label></td>
    <td>
        <label for="backgroundcolor_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Select background color, by default White" ) ) ?></label>
        <input type="hidden" name="backgroundcolor_<?php echo esc_attr( $field['id'] ) ?>" id="color_value"/>
        <input type="text" name="field_options[backgroundcolor_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['backgroundcolor'])?>" class="my-color-field" id="backgroundcolor_<?php echo esc_attr( $field['id'] ) ?>">
    </td>
</tr>

