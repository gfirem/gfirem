<div class="gfirem_qr" <?php do_action( 'frm_field_input_html', $field ) ?> field_id="<?php echo esc_attr( $field_name ) ?>" id="qr_field_container_<?php echo esc_attr( $field['field_key'] ) ?>">
    <input data-action="store-qr" type="hidden" id="field_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( $print_value ); ?>" class="file-upload-input"/>
    <div <?php echo esc_attr( $showContainer ) ?> id="qr_container_<?php echo esc_attr( $html_id ) ?>" ><img  id="snap_thumbnail_<?php echo esc_attr( $field_name ) ?>"  alt="<?php echo esc_attr( $attachment_title ) ?>" src="<?php echo esc_attr( $imageFullUrl ) ?>"></div>
    <div align="left" id="my_qr_<?php echo esc_attr( $html_id ) ?>">
       <div style="display: inline-block; width: 250px;"> <input type="text" width="250" id="qr_string_<?php echo esc_attr( $html_id ) ?>"></div>
        <div style="display: inline-block"><input field_id="<?php echo esc_attr( $field_name ) ?>" id="generate_qr_button_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" type="button" class="select-image-btn btn btn-default" value="<?php echo esc_html( $button_name ) ?>"/></div>
        <div class="loader" style="display: none;" id="qr_loader_<?php echo esc_attr( $html_id ) ?>"></div>
    </div>
    <div style="margin-top: 10px;">
        <img id="qr_code_result_<?php echo esc_attr( $html_id ) ?>"></img>
    </div>


</div>