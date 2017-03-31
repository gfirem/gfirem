<tr>
	<td><label><?php gfirem_manager::esc_echo_translated( 'Autocomplete value' ) ?></label></td>
    <td>
	    <label for="fac_autopopulate_value_<?php echo absint( $field['id'] ) ?>">
			<input type="checkbox" value="1" name="field_options[fac_autopopulate_value_<?php echo absint( $field['id'] ) ?>]" <?php checked($field['fac_autopopulate_value'], 1) ?> class="fac_autopopulate_value" id="fac_autopopulate_value_<?php echo absint( $field['id'] ) ?>" />
	        <?php gfirem_manager::esc_echo_translated( 'Dynamically retrieve the value from an Autocomplete field' ) ?>
		</label>
	</td>
</tr>
<tr class="frm_fac_autopopulate_value_section_<?php echo absint( $field['id'] ) . esc_attr( $field['fac_autopopulate_value'] ? '' : ' frm_hidden' )?>">
	<td>
		<label><?php gfirem_manager::esc_echo_translated( 'Get value from' ) ?></label>
	</td>
	<td><?php
	require( $this->view_path . 'get-options-from.php' );
	?></td>
</tr>
<tr class="frm_fac_autopopulate_value_section_<?php echo absint( $field['id'] ) . esc_attr( $field['fac_autopopulate_value'] ? '' : ' frm_hidden' )?>">
	<td><label><?php gfirem_manager::esc_echo_translated( 'Watch Autocomplete fields' ) ?></label></td>
	<td>
	    <a href="javascript:void(0)" id="fac_frm_add_watch_lookup_link_<?php echo absint( $field['id'] ) ?>" class="fac_frm_add_watch_lookup_row frm_add_watch_lookup_link frm_hidden">
			<?php gfirem_manager::esc_echo_translated( 'Watch Autocomplete fields' ) ?>
		</a>
		<div id="fac_frm_watch_lookup_block_<?php echo absint( $field['id'] ) ?>"><?php
			if ( empty( $field['fac_watch_lookup'] ) ) {
				$field_id = $field['id'];
				$row_key = 0;
				$selected_field = '';
				include( $this->view_path . 'watch-row.php' );
			} else {
				$field_id = $field['id'];
				foreach ( $field['fac_watch_lookup'] as $row_key => $selected_field ) {
					include( $this->view_path . 'watch-row.php' );
				}
			}
		?></div>
	</td>
</tr>