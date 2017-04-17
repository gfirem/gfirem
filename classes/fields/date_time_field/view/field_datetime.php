<?php if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$professional ) ): ?>
    <div class="date_time_field_container">
        <input type="text" class="gfirem_date_time_field_picker" id="field_<?php echo esc_attr( $html_id ) ?>" name="<?php echo esc_attr( $field_name ) ?>" value="<?php echo esc_attr( $print_value ); ?>" <?php do_action( 'frm_field_input_html', $field ) ?> />
    </div>
<?php endif; ?>