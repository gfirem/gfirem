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
<div class="gfirem_container">
    <input type="text" class="mj_schedule_picker" id="field_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( $print_value ); ?>" <?php do_action( 'frm_field_input_html', $field ) ?> />
</div>