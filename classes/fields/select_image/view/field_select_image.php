<input type="hidden" id="field_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( $print_value ); ?>" class="file-upload-input"/>
<div class="gfirem_select_image" <?php do_action( 'frm_field_input_html', $field ) ?> field_id="<?php echo esc_attr( $field_name ) ?>" id="image_container_<?php echo esc_attr( $field['field_key'] ) ?>">
    <div class="dz-preview dz-complete dz-image-preview">
        <div <?php echo esc_attr( $showContainer ) ?> id="image_thumbnail_container_<?php echo esc_attr( $field_name ) ?>" class="dz-image"><img class="gfirem_select_image_zoom" id="image_thumbnail_<?php echo esc_attr( $field_name ) ?>" data-zoom-image="<?php echo esc_attr( $full_image_url[0] ) ?>" alt="<?php echo esc_attr( $attachment_title ) ?>" src="<?php echo esc_attr( $imageUrl ) ?>"></div>
        <div <?php echo esc_attr( $showContainer ) ?> id="image_link_container_<?php echo esc_attr( $field_name ) ?>" class="dz-details">
            <div class="dz-filename"><span data-dz-name=""><a id="image_link_<?php echo esc_attr( $field_name ) ?>" target="_blank" href="<?php echo esc_attr( $imageFullUrl ) ?>"><?php echo esc_attr( $attachment_title ) ?></a></span></div>
        </div>
        <div style="margin-top: 10px;">
            <input field_id="<?php echo esc_attr( $field_name ) ?>" id="upload_button_<?php echo esc_attr( $field['field_key'] ) ?>" name="<?php echo esc_attr( $field_name ) ?>" type="button" class="<?php echo esc_attr( $button_classes ) ?> select-image-btn btn btn-default" value="<?php echo esc_html( $button_name ) ?>"/>
        </div>
    </div>
</div>