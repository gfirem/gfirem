/*
 * @package WordPress
 * @subpackage Formidable, gfirem
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */

var wrappers = document.getElementsByClassName("date_time_field_container");
[].forEach.call(wrappers, process_fields);
function process_fields(item, index) {
	var field_container = item.querySelector("[class=gfirem_date_time_field_picker]");
	var id = field_container.getAttribute('id');

	var datetimefield = jQuery(".gfirem_date_time_field_picker").datetimepicker({
		format: date_time_field.config[id].inputFormat,
		inline: date_time_field.config[id].datetimepicker_inline == "true" ? true : false,
		timepicker: date_time_field.config[id].datetimepicker_timepicker == "true" ? true : false,
		defaultDate: date_time_field.now_date,
		defaultTime: date_time_field.now_time
	});
	jQuery.datetimepicker.setLocale(date_time_field.config[id].datetimepicker_lang);


}