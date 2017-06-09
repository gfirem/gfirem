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
                <h3 class="frm_no_bg"><?php _e( "GFireM Tweak", 'gfirem-locale' ); ?></h3>
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <label for="field_options[page_break_save_button_<?php echo esc_attr( $field['id'] ) ?>]"><?php gfirem_manager::echo_translated( "Enable Save Now" ); ?></label>
        </td>
        <td>
            <input type="checkbox" <?php echo $enabled_save_now ?> name="field_options[page_break_save_button_<?php echo esc_attr( $field['id'] ) ?>]" id="field_options[page_break_save_button_<?php echo esc_attr( $field['id'] ) ?>]" value="1"/>
			<?php gfirem_manager::echo_translated( "This option will add a Save Now button side to it" ); ?>
        </td>
    </tr>
<?php endif; ?>