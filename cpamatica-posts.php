<?php
/**
 * Plugin Name: Cpamatica Posts
 * Description: Cpamatica Posts
 * Author: Vlad Bigun
 * Author URI: https://t.me/vlad_bigun/
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CPAMATICA_POSTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CPAMATICA_POSTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');
require_once( CPAMATICA_POSTS_PLUGIN_DIR . 'src/Cpamatica.php' );
require_once( CPAMATICA_POSTS_PLUGIN_DIR . 'src/CpamaticaImage.php' );
require_once( CPAMATICA_POSTS_PLUGIN_DIR . 'src/CpamaticaPosts.php' );


add_action( 'init', [ 'Cpamatica', 'init' ] );

?>