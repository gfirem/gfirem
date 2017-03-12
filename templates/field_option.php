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
	<td>
		<label for="field_options[field_option_name_<?php echo esc_attr($field['id']) ?>]"><?php _e_gfirem( "Example option" ) ?></label>
		<span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="<?php _e_gfirem( "This is a formidable tooltip example" ) ?>"></span>
	</td>
	<td>
		<input type="checkbox" name="field_options[field_option_name_<?php echo esc_attr($field['id']) ?>]" id="field_options[field_option_name_<?php echo esc_attr($field['id']) ?>]" value="1"/>
	</td>
</tr>