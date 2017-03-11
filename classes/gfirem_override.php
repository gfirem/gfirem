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

function _gfirem( $str ) {
	return __( $str, 'gfirem-locale' );
}

function _e_gfirem( $str ) {
	_e( $str, 'gfirem-locale' );
}