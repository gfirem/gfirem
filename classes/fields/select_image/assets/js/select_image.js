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

			// Extend the wp.media object
			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: gfirem_select_image.config['field_' + id].library_title,
				button: {
					text: gfirem_select_image.config['field_' + id].library_button_title
				}, multiple: false
			});
			mediaUploader._listenId = 'select_image_' + id;

			// When a file is selected, grab the URL and set it as the text field's value
			mediaUploader.on('select', function () {
				var attachment = mediaUploader.state().get('selection').first().toJSON(),
					url;
				if (attachment.sizes.thumbnail) {
					url = attachment.sizes.thumbnail.url;
				}
				else {
					if (attachment.sizes.medium) {
						url = attachment.sizes.medium.url;
					}
					else {
						if (attachment.sizes.full) {
							url = attachment.sizes.full.url;
						}
						else {
							url = attachment.url;
						}
					}
				}

				$('input[name="' + id + '"][type="hidden"]').val(attachment.id);
				$('[id="image_thumbnail_' + id + '"]').attr('src', url);
				$('[id="image_thumbnail_' + id + '"]').attr('alt', attachment.filename);
				$('[id="image_link_' + id + '"]').attr('href', url);
				$('[id="image_link_' + id + '"]').text(attachment.filename);
				$('[id="image_thumbnail_container_' + id + '"]').show();
				$('[id="image_link_container_' + id + '"]').show();
			});
			// Open the uploader dialog
			mediaUploader.open();
		});
	});
});