<?php
/**
 * @package WordPress
 * @subpackage Formidable, gfirem
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 */
 if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ): ?>
<tr>
	<td><label for="inputFormat_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Input Format" ) ?></label></td>
	<td>
		<label for="inputFormat_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Input Format of the TimePicker, by default 'Y/m/d H:i'. " ) ) ?></label>

		<input type="text" name="field_options[inputFormat_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['inputFormat'] ) ?>" id="inputFormat_<?php echo esc_attr( $field['id'] ) ?>">
	</td>

</tr>

<tr>
	<td><label for="datetimepicker_inline_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Inline Mode" ) ?></label></td>
	<td>
		<label for="datetimepicker_inline_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Inline Mode for TimePicker, by default 'False'. " ) ) ?></label>
		<select name="field_options[datetimepicker_inline_<?php echo esc_attr( $field['id'] ) ?>]" id="datetimepicker_inline_<?php echo esc_attr( $field['id'] ) ?>">
			<option value="false"><?php gfirem_manager::echo_translated( "False" ) ?></option>
			<option value="true"><?php gfirem_manager::echo_translated( "True" ) ?></option>

		</select>

	</td>

</tr>
<tr>
	<td><label for="datetimepicker_timepicker_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Enable Time Picker" ) ?></label></td>
	<td>
		<label for="datetimepicker_timepicker_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Enable Time Picker, by default is 'True'. " ) ) ?></label>

		<select name="field_options[datetimepicker_timepicker_<?php echo esc_attr( $field['id'] ) ?>]" id="datetimepicker_timepicker_<?php echo esc_attr( $field['id'] ) ?>">
			<option <?php selected( esc_attr( $field['datetimepicker_timepicker'] ), 'true' ) ?> value="true"><?php gfirem_manager::echo_translated( "True" ) ?></option>
			<option <?php selected( esc_attr( $field['datetimepicker_timepicker'] ), 'false' ) ?> value="false"><?php gfirem_manager::echo_translated( "False" ) ?></option>
		</select>
	</td>

</tr>
<tr>
	<td><label for="datetimepicker_lang_<?php echo esc_attr( $field['id'] ) ?>"><?php gfirem_manager::echo_translated( "Language" ) ?></label></td>
	<td>
		<label for="datetimepicker_lang_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( gfirem_manager::translate( "Language for Time Picker, by default is 'English'. " ) ) ?></label>

		<select name="field_options[datetimepicker_lang_<?php echo esc_attr( $field['id'] ) ?>]" id="datetimepicker_lang_<?php echo esc_attr( $field['id'] ) ?>">
			<option <?php selected( esc_attr( $field['datetimepicker_lang'] ), 'true' ) ?> value="en"><?php gfirem_manager::echo_translated( "English" ) ?></option>
			<option <?php selected( esc_attr( $field['datetimepicker_lang'] ), 'false' ) ?> value="es"><?php gfirem_manager::echo_translated( "Spanish" ) ?></option>
			<option <?php selected( esc_attr( $field['datetimepicker_lang'] ), 'false' ) ?> value="fr"><?php gfirem_manager::echo_translated( "French" ) ?></option>
		</select>
	</td>

</tr>
 <?php endif; ?>