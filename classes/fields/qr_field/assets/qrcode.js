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


    $('.gfirem_qr').each(function () {

        var field_container = $(this).find("[data-action=store-qr]"),
            identifier = field_container.attr('id');
            id = identifier.replace('field_', '');
             $('#qr_loader_'+id).hide();
             $('#generate_qr_button_' + id).click(function (e) {
                 $('#qr_loader_'+id).show();
                 $('#qr_code_result_'+id).hide();
                 if (gfirem_qr.action && (gfirem_qr.action === 'edit' || gfirem_qr.action === 'update')) {
                     $('#qr_container_'+id).hide();

                 }
                 var message = $('#qr_string_'+id).val();
                 jQuery.ajax({
                     type: 'POST', url: gfirem_qr.ajaxurl,
                     data: {
                         action: 'generate_qr_code',
                         nonce: gfirem_qr.ajaxnonce,
                         message: message,
                         key:id
                     },
                     success: function (fields) {
                         $('#qr_loader_'+id).hide();

                         if (fields=="") {
                             console.log("Error");
                         }
                         else{
                             var obj = jQuery.parseJSON( fields );
                             if(obj.image_url!= undefined &&obj.image_url!= '' ){
                                 $('#qr_code_result_'+id).show();
                                 $('#field_' + id).val(obj.id);
                                 $('#qr_code_result_'+id).attr('src',obj.image_url);
                             }
                         }
                     }
                 });
            });
    });


})
