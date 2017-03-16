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
	  <td><label><?php _e_gfirem( "penColor" ) ?></label></td>
	  <td>
        <label for="label1_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( _gfirem( "Color used to draw the lines. Can be any color format accepted by context.fillStyle. Defaults to 'black'" ) ) ?></label> 

        <input type="text" name="field_options[pencolor_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['pencolor'])?>" class="pen-color-field" id="pencolor_<?php echo esc_attr( $field['id'] ) ?>">
    </td>
</tr>
<tr>
    <td><label><?php _e_gfirem( "Background color" ) ?></label></td>
    <td>
        <label for="label1_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( _gfirem( "Select background color, by default White" ) ) ?></label>
        <input type="hidden" name="backgroundcolor_<?php echo esc_attr( $field['id'] ) ?>" id="color_value"/>
        <input type="text" name="field_options[backgroundcolor_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['backgroundcolor'])?>" class="my-color-field" id="backgroundcolor_<?php echo esc_attr( $field['id'] ) ?>">
    </td>
</tr>

