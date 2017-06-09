/*
 * @package WordPress
 * @subpackage Formidable, gfirem
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
/* <fs_premium_only> */
function switch_button_admin() {

	function start_color_picker_off() {
		jQuery('.off-color-field').wpColorPicker({
			change: function (event, ui) {
				var element = event.target;
				var color = ui.color.toString();
				$('#color_value').val(color);
			}
		});
	}

	function start_color_picker_on() {
		jQuery('.on-color-field').wpColorPicker({});
	}

	return {
		init: function () {
			if (document.getElementById('frm_build_form') !== null) {
				start_color_picker_off();
				start_color_picker_on();
				jQuery(document).bind('ajaxComplete ', function (event, xhr, settings) {
					if (settings.data.indexOf('frm_insert_field') !== 0 && settings.data.indexOf('switch_button') !== 0) {
						start_color_picker_off();
						start_color_picker_on();
					}
				});
			}
		}
	}
}
/* </fs_premium_only> */

jQuery(document).ready(function ($) {
	/* <fs_premium_only> */
	switch_button_admin().init();
	/* </fs_premium_only> */
});