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
	<?php foreach ( $this->global_settings_tabs as $global_settings_tab_key => $global_settings_tab_data ) : ?>
        <a href="?page=<?php echo gfirem_manager::get_slug(); ?>&tab=<?php echo "$global_settings_tab_key"; ?>" class="nav-tab <?php echo $active_tab == $global_settings_tab_key ? 'nav-tab-active' : ''; ?>"><?php echo $global_settings_tab_data['name'] ?></a>
	
	<?php endforeach; ?>
</h2>
