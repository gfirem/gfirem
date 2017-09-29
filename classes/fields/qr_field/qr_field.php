<?php
/**
 * @package    WordPress
 * @subpackage Formidable, gfirem
 * @author     GFireM
 * @copyright  2017
 * @link       http://www.gfirem.com
 * @license    http://www.apache.org/licenses/
 *
 */
class qr_field extends gfirem_field_base {

    public $version = '1.0.0';
    public function __construct() {
        parent::__construct( 'qr_field', gfirem_manager::translate( 'QR Field' ),
            array(
                'button_title'  => gfirem_manager::translate( 'Generate QR' ),
            ),
            gfirem_manager::translate( 'Generate QR Code.' )

        );
        add_action( 'admin_footer', array( $this, 'add_script' ) );
        add_action( 'wp_footer', array( $this, 'add_script' ) );
    }

    /**
     * Add script needed to load the field
     *
     * @param $hook
     */
    public function add_script( $hook ) {

    }
    /**
     * Options inside the form
     *
     * @param $field
     * @param $display
     * @param $values
     */
    protected function inside_field_options( $field, $display, $values ) {
        if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
            include dirname( __FILE__ ) . '/view/field_option.php';
        }
    }

    protected function field_front_view( $field, $field_name, $html_id ) {

        $field['value'] = stripslashes_deep( $field['value'] );
        $html_id        = $field['id'];
        $print_value    = $field['default_value'];
        if ( ! empty( $field['value'] ) ) {
            $print_value = $field['value'];
        }

        $showContainer = '';
        if ( empty( $field['value'] ) ) {
            $showContainer = 'style = "display:none;"';
        }

        $button_name = FrmField::get_option( $field, 'button_title' );

        include dirname( __FILE__ ) . '/view/field_qr.php';

    }
    protected function field_admin_view( $value, $field, $attr ) {
        if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
            $value = $this->getMicroImage( $value );
        }

        return $value;
    }
    private function getMicroImage( $id ) {
        $result = '';
        $src    = wp_get_attachment_url( $id );
        if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
            if ( ! empty( $id ) && ! empty( $src ) ) {
                $result = wp_get_attachment_image( $id, array( 50, 50 ), true ) . " <a style='vertical-align: top;' target='_blank' href='" . $src . "'>" . gfirem_manager::translate( "Full Image" ) . "</a>";
            }
        }

        return $result;
    }

    protected function process_short_code( $id, $tag, $attr, $field ) {
        if ( gfirem_fs::getFreemius()->is_plan__premium_only( gfirem_fs::$starter ) ) {
            $internal_attr = shortcode_atts( array(
                'output' => 'img',
                'size'   => 'thumbnail',
                'html'   => '0',
            ), $attr );
            $result        = wp_get_attachment_url( $id );
            if ( $internal_attr['output'] == 'img' ) {
                $result = wp_get_attachment_image( $id, $internal_attr['size'] );
            }

            if ( $internal_attr['html'] == '1' ) {
                $result = "<a style='vertical-align: top;' target='_blank'  href='" . wp_get_attachment_url( $id ) . "' >" . $result . "</a>";
            }
            $replace_with = $result;
        }

        return $replace_with;
    }

}