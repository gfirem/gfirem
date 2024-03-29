<?php if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ): ?>
    <select class="fac_frm_get_values_form" id="fac_get_values_form_<?php echo absint( $field['id'] ) ?>" name="field_options[fac_get_values_form_<?php echo esc_attr( $field['id'] ) ?>]" data-fieldtype="<?php echo esc_attr( $field['type'] ) ?>">
        <option value="">&mdash; <?php _e( 'Select Form', 'formidable' ) ?> &mdash;</option>
		<?php foreach ( $lookup_args['form_list'] as $form_opts ) { ?>
            <option value="<?php echo absint( $form_opts->id ) ?>"<?php selected( $form_opts->id, $field['fac_get_values_form'] ) ?>><?php echo FrmAppHelper::truncate( $form_opts->name, 30 ) ?></option>
		<?php } ?>
    </select>
    <select id="fac_get_values_field_<?php echo absint( $field['id'] ) ?>" name="field_options[fac_get_values_field_<?php echo esc_attr( $field['id'] ) ?>]">
		<?php
		self::show_options_for_get_values_field( $lookup_args['form_fields'], $field );
		?>
    </select>
<?php endif; ?>