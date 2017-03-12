<?php

/**
 * @package WordPress
 * @subpackage Formidable,
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
class select_image extends gfirem_field_base {
	public function __construct() {
		parent::__construct( 'select_image', _gfirem( 'Select Image' ),
			array(
				'select_image_option1' => '0',
			),
			_gfirem( 'Show a field to select image from WP Media library' )
		);
	}
}