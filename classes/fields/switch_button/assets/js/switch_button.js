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
	$(".gfirem_switch_button").each(function () {
		var current = $(this),
			id = current.attr('id'),
			checked = false;

		if (current.val() && current.val() == gfirem_switch_button.config[id].on_label) {
			checked = true;
		}

		var switch_options = {
			checked: checked,
			width: gfirem_switch_button.config[id].width,
			height: gfirem_switch_button.config[id].height,
			button_width: gfirem_switch_button.config[id].button_width,
			show_labels: gfirem_switch_button.config[id].width,
			labels_placement: gfirem_switch_button.config[id].labels_placement,
			on_label: gfirem_switch_button.config[id].on_label,
			off_label: gfirem_switch_button.config[id].off_label
		};

		current.switchButton(switch_options);
	});
});