jQuery(document).ready(function ($) {
	$('.my-color-field').wpColorPicker({
		change: function (event, ui) {
			var element = event.target;
			var color = ui.color.toString();
			$('#color_value').val(color);
		}
	});

	$('.pen-color-field').wpColorPicker({
		
	});

});