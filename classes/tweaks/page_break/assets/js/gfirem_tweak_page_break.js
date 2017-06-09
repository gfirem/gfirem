/*
 * @package WordPress
 * @subpackage Formidable, gfirem
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
function add_button_in_multipage() {
	/* <fs_premium_only> */
	function add_button(form_id) {
		if (form_id) {
			var route_control = jQuery("input[type='hidden'][name='frm_page_order_" + form_id + "']");
			if (route_control && route_control.length > 0 && jQuery.inArray(route_control.val(), gfirem_tweak_page_break) !== -1) {
				if (jQuery("#save_now").length === 0) {
					jQuery("p>input[type='submit'],.frm_submit>[type='submit']").parent().append('<input type="submit" id="save_now" name="save_now" class="button-primary" value="Save Now">');
					jQuery("#save_now").click(function (e) {
						route_control.attr('name', 'save_now_page');
					});
				}
			}
		}
	}

	return {
		init: function () {
			var form_id_input = jQuery("input[type='hidden'][name='form_id']"),
				form = form_id_input.closest('form'),
				form_id = form_id_input.val();
			add_button(form_id);
			jQuery(document).bind('ajaxComplete ', function (event, xhr, settings) {
				if (settings.data.indexOf('frm_page_order_' + form_id) !== 0) {
					add_button(form_id);
				}
			});
		}
	};
	/* </fs_premium_only> */
}
jQuery(document).ready(function ($) {
	/* <fs_premium_only> */
	add_button_in_multipage().init();
	/* </fs_premium_only> */
});
