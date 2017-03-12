<input type="hidden" id="field_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( $print_value ); ?>" class="file-upload-input"/>
<div class="gfirem_select_image" <?php do_action( 'frm_field_input_html', $field ) ?> field_id="<?= $field_name ?>" id="image_container_<?= $field['field_key'] ?>">
    <div class="dz-preview dz-complete dz-image-preview">
        <div <?= $showContainer ?> id="image_thumbnail_container_<?= $field_name ?>" class="dz-image"><img id="image_thumbnail_<?= $field_name ?>" alt="<?= $attachment_title ?>" src="<?= $imageUrl ?>"></div>
        <div <?= $showContainer ?> id="image_link_container_<?= $field_name ?>" class="dz-details">
            <div class="dz-filename"><span data-dz-name=""><a id="image_link_<?= $field_name ?>" target="_blank" href="<?= $imageFullUrl ?>"><?= $attachment_title ?></a></span></div>
        </div>
        <div style="margin-top: 10px;">
            <input field_id="<?= $field_name ?>" id="upload_button_<?= $field['field_key'] ?>" name="<?= $field_name ?>" type="button" class="btn btn-default" value="<?php _e_gfirem( "Select Image" ) ?>" style="width: auto !important;"/>
        </div>
    </div>
</div>