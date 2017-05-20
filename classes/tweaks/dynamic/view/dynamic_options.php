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
    <tr class="frm_options_heading">
        <td colspan="2">
            <div class="menu-settings">
                <h3 class="frm_no_bg"><?php gfirem_manager::echo_translated( "GFireM Tweak" ); ?></h3>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <label for="field_options[dynamic_field_target_<?php echo esc_attr( $field['id'] ) ?>]"><?php gfirem_manager::echo_translated( "Filter Field" ); ?></label>
        </td>
        <td>
			<?php if ( ! empty( $fields_for_filter ) ): ?>
                <select id="field_options[dynamic_field_target_<?php echo esc_attr( $field['id'] ) ?>]" name="field_options[dynamic_field_target_<?php echo esc_attr( $field['id'] ) ?>]" data-fieldtype="lookup">
                    <option value="">&mdash; <?php gfirem_manager::echo_translated( 'Select a Field to use as filter' ) ?> &mdash;</option>
					<?php
					foreach ( $fields_for_filter as $fields ) {
						if ( ! empty( $field["form_select"] ) && $fields->id == $field["form_select"] ) {
							continue;
						}
						if ( ! empty( $field["get_values_field"] ) && $fields->id == $field["get_values_field"] ) {
							continue;
						}
						?>
                        <option value="<?php echo esc_attr( absint( $fields->id ) ) ?>"<?php selected( $fields->id, $field['dynamic_field_target'] ) ?>><?php echo FrmAppHelper::truncate( $fields->name, 30 ) ?></option>
						<?php
					} ?>
                </select>
                &ndash;&ndash;
                <input type="text" name="field_options[dynamic_field_target_value_<?php echo esc_attr( $field['id'] ) ?>]" id="field_options[dynamic_field_target_value_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr( $field['dynamic_field_target_value'] ) ?>"/>
			<?php else: ?>
				<?php gfirem_manager::echo_translated( "Need to set the 'Load Option from', then you can select other field to filter." ); ?>
			<?php endif; ?>
        </td>
    </tr>
	<?php if ( $field['type'] == 'data' ): ?>
        <tr>
            <td>
                <label for="field_options[dynamic_field_unique_filter_<?php echo esc_attr( $field['id'] ) ?>]"><?php gfirem_manager::echo_translated( "Filter Unique" ); ?></label>
            </td>
            <td>
                <input type="checkbox" <?php echo $show_filter_unique ?> name="field_options[dynamic_field_unique_filter_<?php echo esc_attr( $field['id'] ) ?>]" id="field_options[dynamic_field_unique_filter_<?php echo esc_attr( $field['id'] ) ?>]" value="1"/>
				<?php gfirem_manager::echo_translated( "This option trim all duplicates options" ); ?>
            </td>
        </tr>
	<?php endif; ?>
<?php endif; ?>