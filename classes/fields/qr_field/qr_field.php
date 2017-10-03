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

        add_action( 'frm_before_destroy_entry', array( $this, 'process_destroy_entry' ), 10, 2 );
        add_action( "wp_ajax_generate_qr_code", array( $this, "generate_qr_code" ) );

    }

    /**
     * Destroy the attached image to the entry
     *
     * @param $id
     * @param $entry
     */
    public function process_destroy_entry( $id, $entry ) {
        $entry_with_meta = FrmEntry::getOne( $id, true );
        foreach ( $entry_with_meta->metas as $key => $value ) {
            $field_type = FrmField::get_type( $key );
            if ( $field_type == 'webcam' && ! empty( $value ) ) {
                wp_delete_attachment( $value, true );
            }
        }
    }




    public function generate_qr_code(){
        $message  = isset($_POST['message']) ? $_POST['message'] : 0;
        $key      = isset($_POST['key']) ? $_POST['key'] : 0;
        $value ='';

        include dirname( __FILE__ ) . '/classes/phpqrcode/qrlib.php';
        $upload_dir    = wp_upload_dir();
        $file_id       = $this->slug . '_'  . $key . '_' . time();
        $file_name     = $file_id . ".png";
        $full_path     = wp_normalize_path( $upload_dir['path'] . DIRECTORY_SEPARATOR . $file_name );
        $code = QRcode::png($message,$full_path);
        $success_upload = file_exists($full_path);
        if($success_upload){
            $wp_filetype   = wp_check_filetype( $full_path, null );
            $attachment    = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', $file_name ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
            $attachment_id = wp_insert_attachment( $attachment, $full_path );
            if ( ! is_wp_error( $attachment_id ) ) {
                require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
                $attachment_data = wp_generate_attachment_metadata( $attachment_id, $full_path );
                wp_update_attachment_metadata( $attachment_id, $attachment_data );
                $value                         = $attachment_id;
                $fields_collections[ $key ]    = $value;
                $_POST['item_meta'][ $key ]    = $value;//Used to update the current request
                $_REQUEST['item_meta'][ $key ] = $value;//Used to update the current request
            }
        }
        $imageUrl          = wp_get_attachment_image_url( $value );
        $full_image_url    = wp_get_attachment_image_src( $value, 'full' );
        $imageFullUrl      = wp_get_attachment_url($value);
        $str = json_encode(array('image_url'=>$imageUrl,'id'=>$value) );
        echo  "$str";
        die();
    }

    /**
     * Add script needed to load the field
     *
     * @param $hook
     */
    public function add_script( $hook = '', $image_url = '', $field_name ) {
        $base_url = plugin_dir_url( __FILE__ ) . 'assets/';
        wp_register_style( 'wpdocsPluginStylesheet',$base_url . 'qr.css' );
        wp_enqueue_style( 'wpdocsPluginStylesheet' );

        wp_enqueue_script( 'gfirem_qr', $base_url . 'qrcode.js', array( "jquery" ), $this->version, true );
        $params = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'ajaxnonce' => wp_create_nonce('fac_qr_code')
        );
        $signatureFields = FrmField::get_all_types_in_form( $this->form_id, $this->slug );
        foreach ( $signatureFields as $key => $field ) {
            foreach ( $this->defaults as $def_key => $def_val ) {
                $opt                                                          = FrmField::get_option( $field, $def_key );
                $params['config'][ 'field_' . $field->field_key ][ $def_key ] = ( ! empty( $opt ) ) ? $opt : $def_val;
            }
            if ( ! empty( $image_url ) ) {
                $params['config'][ 'item_meta[' . $field->id . ']' ]['image_url'] = $image_url;
            }
        }
        if ( ! empty( $_GET['frm_action'] ) ) {
            $params['action'] = FrmAppHelper::get_param( 'frm_action' );
        }
        wp_localize_script( 'gfirem_qr', 'gfirem_qr', $params );
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
        $imageUrl          = wp_get_attachment_image_url( $field['value'] );
        $full_image_url    = wp_get_attachment_image_src( $field['value'], 'full' );
        $imageFullUrl      = wp_get_attachment_url( $field['value'] );
        $attachment_title  = basename( get_attached_file( $field['value'] ) );
        $button_name = FrmField::get_option( $field, 'button_title' );
        $this->add_script( '', $imageUrl, $field_name );

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