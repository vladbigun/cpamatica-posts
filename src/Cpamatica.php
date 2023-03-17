<?php

class Cpamatica
{
    public static function init() {
        self::init_hooks();
        self::init_cron();
       // CpamaticaPosts::posts_cron();
    }

    private static function init_hooks()
    {
        add_action( 'cpamatica_get_posts_cron', ['CpamaticaPosts', 'posts_cron'] );
        add_shortcode( 'test_shortcode', array( 'CpamaticaPosts', 'shortcode_posts' ));
        add_action( 'wp_enqueue_scripts', array( 'Cpamatica', 'register_styles' ) );
    }

    private static function init_cron()
    {
        if ( ! wp_next_scheduled( 'cpamatica_get_posts_cron' ) ) {
            wp_schedule_event( time(), 'daily', 'cpamatica_get_posts_cron' );
        }
    }

    public static function register_styles() {
        wp_register_style( 'cpamatica-main', CPAMATICA_POSTS_PLUGIN_URL . 'css/main.css', array());
        wp_enqueue_style( 'cpamatica-main' );
    }
}