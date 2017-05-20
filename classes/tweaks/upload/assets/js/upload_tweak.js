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
	function load_dropzone_zoom() {
		var uploadFields = __frmDropzone;
		if (typeof uploadFields === 'undefined') {
			return;
		}
		for (var i = 0; i < uploadFields.length; i++) {
			startZoom(i);
		}
	}

	load_dropzone_zoom();

	jQuery(document).bind('ajaxComplete ', function (event, xhr, settings) {
		if (settings.data !== undefined && settings.data.indexOf('frm_add_form_row') !== -1 && settings.data.indexOf('field_id') !== -1 && settings.data.indexOf('i') !== -1) {
			var field_id = getParameterByName('field_id', '?' + settings.data);
			var sections = jQuery('div[id="frm_field_' + field_id + '_container"]').find('input[name="item_meta[' + field_id + '][row_ids][]"]');
			jQuery.each(sections, function (key, value) {
				var current_section = jQuery(this);
				var dropzone = jQuery(this).parent().find('div.frm_dropzone');
				jQuery.each(dropzone, function (key, value) {
					var dropzone_id = jQuery(this).attr('id');
					var dropzone_fieldId = jQuery(this).parent().find('input[type="hidden"][name^="item_meta[' + field_id + '][' + current_section.val() + ']"]').attr('data-frmfile');
					var dropzone_fieldName = 'item_meta[' + field_id + '][' + current_section.val() + '][' + dropzone_fieldId + ']';
					var field_settings = get_field_configurations(dropzone_fieldId);
					if (field_settings === false) {
						return false;
					}
					execute_selector('#' + dropzone_id, dropzone_fieldName, field_settings.uploadMultiple);
				});
			});
		}
	});

	function startZoom(i) {
		var uploadFields = __frmDropzone;
		var selector = '#' + uploadFields[i].htmlID + '_dropzone';
		var fieldName = uploadFields[i].fieldName;
		var fmr_action = getParameterByName('frm_action');
		if (fmr_action === 'edit') {
			if (selector.indexOf('-0_dropzone') !== -1) {
				return false;
			}
		}
		//Load zoom for existing fields
		if (typeof uploadFields[i].mockFiles !== 'undefined') {
			if (uploadFields[i].mockFiles.length > 0) {
				init_zoom(fieldName, uploadFields[i].mockFiles, uploadFields[i].uploadMultiple);
			}
		}
		execute_selector(selector, fieldName, uploadFields[i].uploadMultiple);
	}

	function execute_selector(selector, fieldName, uploadMultiple) {
		var field = jQuery(selector);
		var form = field.closest('form');
		var dropzone = Dropzone.forElement(selector);
		dropzone.on('success', function (file, response) {
			var mediaIDs = jQuery.parseJSON(response);
			if (mediaIDs.length > 0) {
				request_attachment_url(mediaIDs, fieldName, false, form);
			}
		});
		dropzone.on('successmultiple', function (files, response) {
			var mediaIDs = jQuery.parseJSON(response);
			request_attachment_url(mediaIDs, fieldName, true, form);
		});
		dropzone.on('removedfile', function (file) {
			if (file.accepted !== false && uploadMultiple !== true) {
				jQuery('input[name="' + fieldName + '"]').val('');
				jQuery.each($('.zoomContainer'), function (key, value) {
					var style = jQuery(this).find('.zoomWindow').attr('style');
					if (style.indexOf(file.name) !== -1) {
						jQuery(this).remove();
					}
				});
				$('input[name="' + fieldName + '"]').removeData('elevateZoom').removeData('zoomImage');
			}
		});
	}

	function get_field_configurations(field_id) {
		var uploadFields = __frmDropzone;
		for (var i = 0; i < uploadFields.length; i++) {
			if (field_id === uploadFields[i]['fieldID']) {
				return uploadFields[i];
			}
		}
		return false;
	}

	function getHiddenUploadHTML(field, mediaID, fieldName) {
		return '<input name="' + fieldName + '[]" type="hidden" value="' + mediaID + '" data-frmfile="' + field.fieldID + '" />';
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
		if (enable === 'enable') {
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
				init_zoom(fieldName, attachments, uploadMultiple);
			}
		}).always(function () {
			removeSubmitLoading(form, 'enable');
		});
	}

	function init_zoom(fieldName, mockFiles, uploadMultiple) {
		if (uploadMultiple !== undefined && uploadMultiple === true) {
			fieldName += '[]';
		}
		var imageContainer = jQuery('input[name="' + fieldName + '"]').parent();
		imageContainer.find('.dz-details').hide();
		jQuery.each(imageContainer.find('.dz-image>img'), function (key, value) {
			var img_for_zoom = jQuery(this);
			var img_title = jQuery(this).attr('alt');
			if (img_title !== undefined) {
				jQuery.each(mockFiles, function (key, value) {
					if (img_title.indexOf(value['name']) !== -1) {
						img_for_zoom.attr('data-zoom-image', value['file_url']);
						img_for_zoom.elevateZoom({scrollZoom: true, cursor: 'pointer'});
					}
				});
			}
		});
	}

	function getParameterByName(name, url) {
		if (!url) url = window.location.href;
		name = name.replace(/[\[\]]/g, "\\$&");
		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			results = regex.exec(url);
		if (!results) return null;
		if (!results[2]) return '';
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	}
	/* </fs_premium_only> */
});
