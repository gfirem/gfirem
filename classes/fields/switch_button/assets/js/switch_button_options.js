/*
 * @package WordPress
 * @subpackage Formidable, gfirem
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
jQuery(document).ready(function ($) {
	/* <fs_premium_only> */
	$('.off-color-field').wpColorPicker({
		change: function (event, ui) {
			var element = event.target;
			var color = ui.color.toString();
			$('#color_value').val(color);
		}
	});

	$('.on-color-field').wpColorPicker({});
	/* </fs_premium_only> */
});
