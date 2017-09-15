
<div class="gfirem_webcam" <?php do_action( 'frm_field_input_html', $field ) ?> field_id="<?php echo esc_attr( $field_name ) ?>" id="webcam_container_<?php echo esc_attr( $field['field_key'] ) ?>">
	<input data-action="store-snapshot" type="hidden" id="field_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( $print_value ); ?>" class="file-upload-input"/>
	<div <?php echo esc_attr( $showContainer ) ?> id="snap_container_<?php echo esc_attr( $html_id ) ?>" ><img  id="snap_thumbnail_<?php echo esc_attr( $field_name ) ?>"  alt="<?php echo esc_attr( $attachment_title ) ?>" src="<?php echo esc_attr( $imageFullUrl ) ?>"></div>
	<div id="my_camera_<?php echo esc_attr( $html_id ) ?>">	</div>
	<div id="pre_take_buttons" style="margin-top: 10px;">
		<input field_id="<?php echo esc_attr( $field_name ) ?>" id="webcam_button_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" type="button" class="select-image-btn btn btn-default" value="<?php echo esc_html( $button_name ) ?>"/>
	</div>
	<div id="post_take_buttons" style="display:none; margin-top: 10px;">
		<input field_id="<?php echo esc_attr( $field_name ) ?>" id="webcam_take_another_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" type="button" class="select-image-btn btn btn-default" value="&lt; &lt; <?php echo esc_html( 'Take Another' ) ?>"/>

	</div>

</div>