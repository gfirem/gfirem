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

	var switch_options = {
		checked: checked,
		width: 50,
		height: 20,
		button_width: 25,
		show_labels: true,
		labels_placement: "both",
		on_label: "ON",
		off_label: "OFF"
	};
	$(".gfirem_switch_button").switchButton(switch_options);
});