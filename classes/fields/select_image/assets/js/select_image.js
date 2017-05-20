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
	var mediaUploader;

	$('.gfirem_select_image').each(function () {
		var id = $(this).attr('field_id'),
			old_full_size,
			elevateZoomConfig = {scrollZoom: false, cursor: 'pointer'};
		if (gfirem_select_image.config[id].image_url) {
			old_full_size = gfirem_select_image.config[id].image_url;
		}
		if (gfirem_select_image.config[id].activate_zoom && gfirem_select_image.config[id].activate_zoom === 'true') {
			if (gfirem_select_image.config[id].scroll_zoom && gfirem_select_image.config[id].scroll_zoom === 'true') {
				elevateZoomConfig['scrollZoom'] = true;
			}
			if (gfirem_select_image.action && gfirem_select_image.action === 'edit') {
				$('[id="image_thumbnail_' + id + '"]').elevateZoom(elevateZoomConfig);
			}
		}
		$("#image_thumbnail_" + id).elevateZoom();
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
				title: gfirem_select_image.config[id].library_title,
				button: {
					text: gfirem_select_image.config[id].library_button_title
				}, multiple: false
			});
			mediaUploader._listenId = 'select_image_' + id;

			// When a file is selected, grab the URL and set it as the text field's value
			mediaUploader.on('select', function () {
				var attachment = mediaUploader.state().get('selection').first().toJSON(),
					url, full_size, ez;
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
				if (attachment.sizes.full) {
					full_size = attachment.sizes.full.url;
				}
				else {
					if (attachment.sizes.medium) {
						full_size = attachment.sizes.medium.url;
					}
					else {
						if (attachment.sizes.thumbnail) {
							full_size = attachment.sizes.thumbnail.url;
						}
						else {
							full_size = attachment.url;
						}
					}
				}

				$('input[name="' + id + '"][type="hidden"]').val(attachment.id);
				$('[id="image_thumbnail_' + id + '"]').attr('src', url);
				$('[id="image_thumbnail_' + id + '"]').attr('alt', attachment.filename);
				$('[id="image_thumbnail_' + id + '"]').attr('data-zoom-image', full_size);
				$('[id="image_link_' + id + '"]').attr('href', url);
				$('[id="image_link_' + id + '"]').text(attachment.filename);
				$('[id="image_thumbnail_container_' + id + '"]').show();
				$('[id="image_link_container_' + id + '"]').show();

				if (gfirem_select_image.config[id].activate_zoom && gfirem_select_image.config[id].activate_zoom === 'true') {
					if (old_full_size) {
						$('.zoomContainer').remove();
						$('[id="image_thumbnail_' + id + '"]').removeData('elevateZoom').removeData('zoomImage');
					}
					$('[id="image_thumbnail_' + id + '"]').elevateZoom(elevateZoomConfig);
					old_full_size = full_size;

				}
			});
			mediaUploader.on('open', function () {
				if (gfirem_select_image['upload_as_default_tab'] && gfirem_select_image['upload_as_default_tab'] === '1' &&
					gfirem_select_image['upload_file_tab_string'] && gfirem_select_image['upload_image_tab_string']) {
					jQuery(".media-frame-router").find('a.media-menu-item').each(function () {
						var current = jQuery(this);
						if (current.text().trim() === gfirem_select_image['upload_file_tab_string'] || current.text().trim() === gfirem_select_image['upload_image_tab_string']) {
							current.click();
							return;
						}
					});
				}
			});
			// Open the uploader dialog
			mediaUploader.open();
		});
	});
	/* </fs_premium_only> */
});