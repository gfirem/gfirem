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

	$('.gfirem_webcam').each(function () {
		var field_container = $(this).find("[data-action=store-snapshot]"),
			identifier = field_container.attr('id');
		id = identifier.replace('field_', '');
		Webcam.set({
			width: 320,
			height: 240,
			image_format: 'jpeg',
			jpeg_quality: 90
		});
		Webcam.attach('#my_camera_' + id);
		$('#webcam_button_' + id).click(function (e) {

			Webcam.snap(function (data_uri) {
				// display results in page
				$('#field_' + id).val(data_uri);
				$('#my_snapshot_' + id).attr('src', data_uri);
			});
		});

	});
});
