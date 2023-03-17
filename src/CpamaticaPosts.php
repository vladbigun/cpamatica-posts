<?php

class CpamaticaPosts
{
    const api_posts_url = "https://my.api.mockaroo.com/posts.json";
    const api_posts_key = "413dfbf0";

    const post_type = "post";
    const post_default_count = "5";

    public static function get_api_posts(){
        $args['headers'] = ['X-API-Key' => self::api_posts_key];
        $response = wp_remote_get( self::api_posts_url, $args );
        $body = wp_remote_retrieve_body( $response );
        return json_decode($body);
    }

    public static function create_posts($data){
        foreach ($data as $item){
            $search_post = query_posts([
                'title' => $item->title,
                'post_type' => self::post_type,
            ]);
            if($search_post) break;

            $args = [
                'role' => 'administrator',
                'order' => 'DESC'
            ];
            $author = get_users( $args )[0];
            $category_id = wp_create_category($item->category);

            $random_date = date( 'Y-m-d H:i:s', rand(strtotime ("-1 month" , time()), time()) );

            $post_data = [
                'post_title'    => $item->title,
                'post_content'  => $item->content,
                'post_status'   => 'publish',
                'post_type'     => self::post_type,
                'post_category' => [$category_id],
                'meta_input'    => [
                    'rating' => $item->rating,
                    'link' => $item->site_link,
                ],
                'post_author' => $author->ID,
                'post_date' => $random_date,
            ];

            $post_id = wp_insert_post(  wp_slash( $post_data ) );

            $re = '/http(?:s?):\/\/.+?\.(?:jpg|jpeg|png)/s';
            preg_match_all($re, $item->image, $matches);
            $url_img = $matches[0][0];

            $attachment_id = CpamaticaImage::upload_file_by_url($url_img);
            set_post_thumbnail( $post_id, $attachment_id );
        }
    }
    public static function shortcode_posts($attr){
        $args = [
            'posts_per_page' => $attr['count'] ?? self::post_default_count,
            'post_type' => self::post_type,
        ];
        if(isset($attr['ids'])){
            $args['post__in'] =  explode( ',', $attr['ids'] );
        }
        switch ($attr['sort']) {
            case 'rating':
                $args['meta_key']  = 'rating';
                $args['orderby'] = 'meta_value_num';
                break;
            case 'title':
                $args['orderby'] = 'title';
                break;
            case 'date':
                $args['orderby'] = 'date';
                break;
        }
        $query = new WP_Query( $args );
        return include( CPAMATICA_POSTS_PLUGIN_DIR . 'views/shortcode_post.php' );
    }

    public static function posts_cron(){
        $data = self::get_api_posts();
        if(empty($data->error)){
            self::create_posts($data);
        }
    }
}