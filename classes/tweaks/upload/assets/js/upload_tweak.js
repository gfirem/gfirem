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
		if (field.length < 1 || field.hasClass('dz-clickable')) {
			return;
		}

		var max = uploadFields[i].maxFiles;
		if (typeof uploadFields[i].mockFiles !== 'undefined') {
			var uploadedCount = uploadFields[i].mockFiles.length;
			if (max > 0) {
				max = max - uploadedCount;
			}
		}

		var form = field.closest('form');
		var submitButton = form.find('input[type="submit"], .frm_submit input[type="button"]');
		var loading = form.find('.frm_ajax_loading');

		field.dropzone({
			url: frm_js.ajax_url,
			addRemoveLinks: true,
			paramName: field.attr('id').replace('_dropzone', ''),
			maxFilesize: uploadFields[i].maxFilesize,
			maxFiles: max,
			uploadMultiple: uploadFields[i].uploadMultiple,
			dictDefaultMessage: uploadFields[i].defaultMessage,
			dictFallbackMessage: uploadFields[i].fallbackMessage,
			dictFallbackText: uploadFields[i].fallbackText,
			dictFileTooBig: uploadFields[i].fileTooBig,
			dictInvalidFileType: uploadFields[i].invalidFileType,
			dictResponseError: uploadFields[i].responseError,
			dictCancelUpload: uploadFields[i].cancel,
			dictCancelUploadConfirmation: uploadFields[i].cancelConfirm,
			dictRemoveFile: uploadFields[i].remove,
			dictMaxFilesExceeded: uploadFields[i].maxFilesExceeded,
			fallback: function () {
				// Force ajax submit to turn off
				jQuery(this.element).closest('form').removeClass('frm_ajax_submit');
			},
			init: function () {
				this.on('sending', function (file, xhr, formData) {
					formData.append('action', 'frm_submit_dropzone');
					formData.append('field_id', uploadFields[i].fieldID);
					formData.append('form_id', uploadFields[i].formID);
				});

				this.on('success', function (file, response) {
					var mediaIDs = jQuery.parseJSON(response);
					if (uploadFields[i].uploadMultiple !== true) {
						request_attachment_url(mediaIDs, fieldName, false);
					}
				});

				this.on('successmultiple', function (files, response) {
					var mediaIDs = jQuery.parseJSON(response);
					for (var m = 0; m < files.length; m++) {
						jQuery(files[m].previewElement).append(getHiddenUploadHTML(uploadFields[i], mediaIDs[m], fieldName));
					}
					request_attachment_url(mediaIDs, fieldName, true);
				});

				this.on('complete', function (file) {
					if (typeof file.mediaID !== 'undefined') {
						if (uploadFields[i].uploadMultiple) {
							jQuery(file.previewElement).append(getHiddenUploadHTML(uploadFields[i], file.mediaID, fieldName));
						}
						// Add download link to the file
						var fileName = file.previewElement.querySelectorAll('[data-dz-name]');
						for (var _i = 0, _len = fileName.length; _i < _len; _i++) {
							var node = fileName[_i];
							node.innerHTML = '<a href="' + file.url + '">' + file.name + '</a>';
						}
					}
				});

				this.on('removedfile', function (file) {
					if (file.accepted !== false && uploadFields[i].uploadMultiple !== true) {
						jQuery('input[name="' + fieldName + '"]').val('');
						$('.zoomContainer').remove();
						$('input[name="' + fieldName + '"]').removeData('elevateZoom').removeData('zoomImage');
					}
					if (file.accepted !== false && typeof file.mediaID !== 'undefined') {
						jQuery(file.previewElement).remove();
						var fileCount = this.files.length;
						this.options.maxFiles = uploadFields[i].maxFiles - fileCount;
					}
				});
			}
		});
	}

	function request_attachment_url(media_ids, fieldName, uploadMultiple) {
		jQuery.ajax({
			type: 'GET',
			url: frm_js.ajax_url,
			data: {
				action: 'upload_tweak_attachment',
				media_is: media_ids,
				nonce: frm_js.nonce
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
