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
	var mediaUploader;
	$('.gfirem_select_image').each(function () {
		var id = $(this).attr('field_id');
		$('input[name="' + id + '"][type="button"]').click(function (e) {
			e.preventDefault();
			id = $(this).attr('field_id');
			// If the uploader object has already been created, reopen the dialog
			if (mediaUploader && mediaUploader._listenId == 'select_image_' + id) {
				mediaUploader.open();
				return;
			}

			//TODO improve this code example. attachment.url show only the minimum size

			// Extend the wp.media object
			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Image',
				button: {
					text: 'Choose Image'
				}, multiple: false
			});
			mediaUploader._listenId = 'select_image_' + id;

			// When a file is selected, grab the URL and set it as the text field's value
			mediaUploader.on('select', function () {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				$('input[name="' + id + '"][type="hidden"]').val(attachment.id);
				$('[id="image_thumbnail_' + id + '"]').attr('src', attachment.url);
				$('[id="image_thumbnail_' + id + '"]').attr('alt', attachment.filename);
				$('[id="image_link_' + id + '"]').attr('href', attachment.url);
				$('[id="image_link_' + id + '"]').text(attachment.filename);
				$('[id="image_thumbnail_container_' + id + '"]').show();
				$('[id="image_link_container_' + id + '"]').show();
			});
			// Open the uploader dialog
			mediaUploader.open();
		});
	});
});