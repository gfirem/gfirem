/*
 * @package WordPress
 * @subpackage Formidable,
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
jQuery(document).ready(function ($) {
	var checked = false;
	if (gfirem_switch_button.print_value) {
		checked = true;
	}
	function set_field_value(is_checked) {
		if (gfirem_switch_button.field_id) {
			var field = $('#field_' + gfirem_switch_button.field_id);
			if (is_checked) {
				field.val('1');
			}
			else {
				field.val('0');
			}
		}
	}

	var switch_options = {
		checked: checked,
		width: 50,
		height: 20,
		button_width: 25,
		show_labels: true,
		labels_placement: "both",
		on_label: "ON",
		off_label: "OFF",
		on_callback: function () {
			set_field_value(true);
		},
		off_callback: function () {
			set_field_value(false);
		}
	};
	$(".gfirem_switch_button").switchButton(switch_options);
});