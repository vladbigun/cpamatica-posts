<?php

class CpamaticaImage
{
    public static function upload_file_by_url( $image_url ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $temp_file = download_url( $image_url);
        if( is_wp_error( $temp_file ) ) {
            return false;
        }
        $file = [
            'name'     => basename( $image_url ),
            'type'     => mime_content_type( $temp_file ),
            'tmp_name' => $temp_file,
            'size'     => filesize( $temp_file ),
        ];
        $sideload = wp_handle_sideload($file, ['test_form'   => false]);

        if( ! empty( $sideload[ 'error' ] ) ) {
            return false;
        }
        $attachment_id = wp_insert_attachment(
            array(
                'guid'           => $sideload[ 'url' ],
                'post_mime_type' => $sideload[ 'type' ],
                'post_title'     => basename( $sideload[ 'file' ] ),
                'post_content'   => '',
                'post_status'    => 'inherit',
            ),
            $sideload[ 'file' ]
        );
        if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
            return false;
        }
        wp_update_attachment_metadata(
            $attachment_id,
            wp_generate_attachment_metadata( $attachment_id, $sideload[ 'file' ] )
        );
        return $attachment_id;
    }

    public static function clear_url($url)
    {
        $re = '/http(?:s?):\/\/.+?\.(?:jpg|jpeg|png)/s';
        preg_match_all($re, $url, $return);
        return $return[0][0];
    }
}