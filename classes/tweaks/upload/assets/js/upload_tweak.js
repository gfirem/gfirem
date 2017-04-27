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
	$('.frm_dropzone').each(function () {
		var container = $(this);
		if (typeof __frmDropzone === 'undefined') {
			return;
		}
		var uploadFields = __frmDropzone;
		for (var i = 0; i < uploadFields.length; i++) {
			startZoom(i);
		}
	});

	function getHiddenUploadHTML(field, mediaID, fieldName) {
		return '<input name="' + fieldName + '[]" type="hidden" value="' + mediaID + '" data-frmfile="' + field.fieldID + '" />';
	}

	function startZoom(i) {
		var uploadFields = __frmDropzone;
		var selector = '#' + uploadFields[i].htmlID + '_dropzone';
		var fieldName = uploadFields[i].fieldName;

		var field = jQuery(selector);

		var max = uploadFields[i].maxFiles;
		if (typeof uploadFields[i].mockFiles !== 'undefined') {
			var uploadedCount = uploadFields[i].mockFiles.length;
			if (max > 0) {
				max = max - uploadedCount;
			}
		}

		var form = field.closest('form');
		var formID = '#' + form.attr('id');
		if (formID == '#undefined') {
			// use a class if there is not id for WooCommerce
			formID = 'form.' + form.attr('class').replace(' ', '.');
		}

		var dropzone = Dropzone.forElement(selector);

		dropzone.on('success', function (file, response) {
			var mediaIDs = jQuery.parseJSON(response);
			for (var m = 0; m < mediaIDs.length; m++) {
				if (uploadFields[i].uploadMultiple !== true) {
					request_attachment_url(mediaIDs, fieldName, false, form);
				}
			}
		});

		dropzone.on('successmultiple', function (files, response) {
			var mediaIDs = jQuery.parseJSON(response);
			request_attachment_url(mediaIDs, fieldName, true, form);
		});

		dropzone.on('removedfile', function (file) {
			if (file.accepted !== false && uploadFields[i].uploadMultiple !== true) {
				jQuery('input[name="' + fieldName + '"]').val('');
				$('.zoomContainer').remove();
				$('input[name="' + fieldName + '"]').removeData('elevateZoom').removeData('zoomImage');
			}
		});
	}

	function disableSubmitButton($form) {
		$form.find('input[type="submit"], input[type="button"], button[type="submit"]').attr('disabled', 'disabled');
	}

	function enableSubmitButton($form) {
		$form.find('input[type="submit"], input[type="button"], button[type="submit"]').removeAttr('disabled');
	}

	function showSubmitLoading(object) {
		if (!object.hasClass('frm_loading_form')) {
			object.addClass('frm_loading_form');
		}

		disableSubmitButton(object);
	}

	function removeSubmitLoading(object, enable) {
		object.removeClass('frm_loading_form');

		if (enable == 'enable') {
			enableSubmitButton(object);
		}
	}

	function request_attachment_url(media_ids, fieldName, uploadMultiple, form) {
		jQuery.ajax({
			type: 'GET',
			url: frm_js.ajax_url,
			data: {
				action: 'upload_tweak_attachment',
				media_is: media_ids,
				nonce: frm_js.nonce
			},
			beforeSend: function (xhr) {
				showSubmitLoading(form);
			},
			success: function (attachments) {
				attachments = jQuery.parseJSON(attachments);
				if (uploadMultiple === true) {
					for (var m = 0; m < attachments.length; m++) {
						set_zoom_image(fieldName + '[]', attachments[m]['id'], attachments[m]['url']);
					}
				}
				else {
					set_zoom_image(false, attachments[0]['id'], attachments[0]['url']);
				}
			}
		}).always(function () {
			removeSubmitLoading(form, 'enable');
		});
	}

	function set_zoom_image(field_name, id, url) {
		var imageContainer;
		if (field_name !== false) {
			imageContainer = jQuery('input[name="' + field_name + '"][value="' + id + '"]').parent();
		}
		else {
			imageContainer = jQuery('.frm_dropzone');
		}
		imageContainer.find('.dz-details').hide();
		var image_to_zoom = imageContainer.find('.dz-image>img');
		image_to_zoom.attr('data-zoom-image', url);
		image_to_zoom.elevateZoom({scrollZoom: true, cursor: 'pointer'});
	}
});
