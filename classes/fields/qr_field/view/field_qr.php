<div class="gfirem_qr" <?php do_action( 'frm_field_input_html', $field ) ?> field_id="<?php echo esc_attr( $field_name ) ?>" id="qr_field_container_<?php echo esc_attr( $field['field_key'] ) ?>">
    <input data-action="store-qr" type="hidden" id="field_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( $print_value ); ?>" class="file-upload-input"/>
    <div <?php echo esc_attr( $showContainer ) ?> id="qr_container_<?php echo esc_attr( $html_id ) ?>" ><img  id="snap_thumbnail_<?php echo esc_attr( $field_name ) ?>"  alt="<?php echo esc_attr( $attachment_title ) ?>" src="<?php echo esc_attr( $imageFullUrl ) ?>"></div>
    <div align="left" id="my_qr_<?php echo esc_attr( $html_id ) ?>">
        <div class="qrfield-container">
            <input type="text" style="width: 250px;float: left;border:none; height: 45px" class="input-field" id="qr_string_<?php echo esc_attr( $html_id ) ?>"/>
            <button field_id="<?php echo esc_attr( $field_name ) ?>" id="generate_qr_button_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" type="button" class="select-image-btn btn btn-default"><span class="dashicons dashicons-controls-play"></span></button>

        </div>
        <div class="loader" style="display: none;" id="qr_loader_<?php echo esc_attr( $html_id ) ?>"></div>

    </div>
    <br>
    <div style="margin-top: 50px; margin-left: -340px; float: left">
        <img id="qr_code_result_<?php echo esc_attr( $html_id ) ?>"></img>
    </div>

</div>