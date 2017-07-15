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
if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ):
	?>

    <tr>
        <td><label for="height_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Height" ) ?></label></td>
        <td>
            <input type="number" name="field_options[height_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['height'] ) ?>" id="height_<?php echo esc_attr( $field['id'] ) ?>">
        </td>

    </tr>
    <tr>
        <td><label for="width_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Width" ) ?></label></td>
        <td>
            <input type="number" name="field_options[width_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['width'] ) ?>" id="width_<?php echo esc_attr( $field['id'] ) ?>">
        </td>

    </tr>
    <tr>
        <td><label for="button_width_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Button Width" ) ?></label></td>
        <td>
            <input type="number" name="field_options[button_width_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['button_width'] ) ?>" id="button_width_<?php echo esc_attr( $field['id'] ) ?>">
        </td>

    </tr>
    <tr>
        <td><label for="on_label_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "On Text" ) ?></label></td>
        <td>
            <input type="text" name="field_options[on_label_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['on_label'] ) ?>" id="on_label_<?php echo esc_attr( $field['id'] ) ?>">
        </td>

    </tr>
    <tr>
        <td><label for="off_label_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Off Text" ) ?></label></td>
        <td>
            <input type="text" name="field_options[off_label_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['off_label'] ) ?>" id="off_label_<?php echo esc_attr( $field['id'] ) ?>">
        </td>

    </tr>
    <tr>
        <td><label for="labels_placement_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Text Placement" ) ?></label></td>
        <td>
            <label for="labels_placement_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Where the text will be shown, by default 'both'. " ) ) ?></label>
            <select name="field_options[labels_placement_<?php echo esc_attr( $field['id'] ) ?>]" id="labels_placement_<?php echo esc_attr( $field['id'] ) ?>">
				<?php
				foreach ( $label_placement_option as $key => $val ) {
					echo '<option ' . selected( esc_attr( $field['labels_placement'] ), $key ) . ' value="' . $key . '">' . $val . '</option>';
				}
				?>
            </select>
        </td>

    </tr>
	<tr>
		<td><label for="oncolor_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "On Color" ) ?></label></td>
		<td>
			<label for="oncolor_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Select background color when the button is on. Can be any color format accepted by context.fillStyle. Defaults to 'green'" ) ) ?></label>

			<input type="text" name="field_options[oncolor_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['oncolor'])?>" class="on-color-field" id="oncolor_<?php echo esc_attr( $field['id'] ) ?>">
		</td>
	</tr>
    <tr>
        <td><label for="offcolor_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "On Color" ) ?></label></td>
        <td>
            <label for="offcolor_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Select background color when the button is on. Can be any color format accepted by context.fillStyle. Defaults to 'green'" ) ) ?></label>

            <input type="text" name="field_options[offcolor_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['offcolor'])?>" class="on-color-field" id="offcolor_<?php echo esc_attr( $field['id'] ) ?>">
        </td>
    </tr>	
<?php endif; ?>