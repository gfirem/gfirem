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
<div class="wrap">
	
	<?php include_once GFIREM_VIEW_PATH . 'html_admin_head.php'; ?>

    <form method="post" action="options.php">
		<?php wp_nonce_field( 'update-options' ); ?>
		<?php settings_fields( $option ); ?>
		<?php do_settings_sections( $option ); ?>
    </form>

</div>

