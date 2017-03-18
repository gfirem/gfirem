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
<div id="icon-options-general" class="icon32"><br></div>
<h2> <?php _e_gfirem( 'GFireM Fields' ); ?></h2>
<div style="overflow: auto;">
    <span style="font-size: 13px; float:right;"><?php _e_gfirem( 'By ' ); ?><a href="http://www.gfirem.com/" target="_new">GFireM</a>.</span>
</div>

<h2 class="nav-tab-wrapper">
    <a href="?page=<?php echo gfirem_manager::get_slug(); ?>&tab=generic" class="nav-tab <?php echo $active_tab == 'generic' ? 'nav-tab-active' : ''; ?>"><?php _e_gfirem( 'Settings' ) ?></a>
	<?php foreach ( $this->fields as $global_settings_tab_key => $global_settings_tab_data ) : ?>
		<?php if ( ! empty( $global_settings_tab_data->global_options ) && array_key_exists($global_settings_tab_key, $this->loaded_fields) && gfirem_manager::is_enabled($global_settings_tab_key) ) : ?>
            <a href="?page=<?php echo gfirem_manager::get_slug(); ?>&tab=<?php echo "$global_settings_tab_key"; ?>" class="nav-tab <?php echo $active_tab == $global_settings_tab_key ? 'nav-tab-active' : ''; ?>"><?php echo $global_settings_tab_data->global_options['name'] ?></a>
		<?php endif; ?>
	<?php endforeach; ?>
	<?php if ( $this->needs_upgrade() ) : ?>
        <a href="?page=<?php echo gfirem_manager::get_slug(); ?>-pricing" class="nav-tab"><font color="#b22222"><?php _e_gfirem( 'Go Pro!!!' ) ?></font></a>
	<?php endif; ?>
</h2>
