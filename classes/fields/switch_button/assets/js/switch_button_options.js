/**
 * Created by Victor on 12/05/2017.
 */
jQuery(document).ready(function ($) {
	$('.off-color-field').wpColorPicker({
		change: function (event, ui) {
			var element = event.target;
			var color = ui.color.toString();
			$('#color_value').val(color);
		}
	});

	$('.on-color-field').wpColorPicker({});

});