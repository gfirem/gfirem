/*
 * @package WordPress
 * @subpackage Formidable,
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
jQuery(document).ready(function ($) {
	$(".gfirem_date_time_field_picker").datetimepicker({
		format: 'Y/m/d H:i',
		inline: true,
		defaultDate: date_time_field.now_date,
		defaultTime: date_time_field.now_time,
		lang: date_time_field.language
	});
});