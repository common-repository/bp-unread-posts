<?php
/*
Plugin Name: BP Unread Posts
Plugin URI: http://wordpress.org/extend/plugins/bp-unread-posts/
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=X7SZG3SM4JYGY
Description: Creates new post icon if there are new posts in the thread.
Version: 0.8.0
Author: Normen Hansen
Author URI: http://www.bitwaves.de/
*/

/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */
function bp_unread_posts_init() {
    require( dirname( __FILE__ ) . '/bp-unread-posts.php' );
}
add_action( 'bp_init', 'bp_unread_posts_init' );

// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
?>