<?php
/**
 * @package WordPress
 * @subpackage Formidable, gfirem
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
?>
<div id="signature-pad" class="gfirem-signature-pad" <?php do_action( 'frm_field_input_html', $field ) ?>>
	<input data-action="store-sign" type="hidden" id="field_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( $print_value ); ?>" >	
	<div class="gfirem-signature-pad-body">
		<canvas class="gfirem-signature-canvas"></canvas>
	</div>
	<div class="gfirem-signature-pad--footer">
		<a href="#" data-action="clear" class="gfirem-signature-clear "><span class="dashicons dashicons-image-rotate"></span> </a>
	</div>
</div>
