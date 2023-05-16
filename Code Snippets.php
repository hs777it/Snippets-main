<?php

/**
 * Code Snippets
 *
 * &nbsp;
 * 
 * 
 */
// Remove WordPress Version From the Footer
remove_action('wp_head', 'wp_generator');
function ryu_remove_version() {
return '';
}
add_filter('the_generator', 'ryu_remove_version');
// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Remove Additional Traces
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'feed_links_extra', 3 );
remove_action('wp_head', 'feed_links', 2 );
remove_action('wp_head', 'parent_post_rel_link', 10, 0 );
remove_action('wp_head', 'start_post_rel_link', 10, 0 );
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Swap WordPress' Jquery Version with the Google Version
add_action('init','jquery_register');

function jquery_register() {
    if(!is_admin()) {
    wp_deregister_script('jquery');
    wp_register_script('jquery',('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'),false,null,true);
    wp_enqueue_script('jquery');
    }
}
// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Reduce WordPress Post Revisions
if(!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS',10);

// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Remove Bloat From Database
function remove_default_userfields( $contactmethods ) {
unset($contactmethods['aim']);
unset($contactmethods['jabber']);
unset($contactmethods['yim']);
return $contactmethods;
}
add_filter('user_contactmethods','remove_default_userfields',10,1);
// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Turn Off XML-RPC
add_filter('xmlrpc_enabled', '__return_false');
// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Lock Your Admin Panel For Subscribers and Contributors
function wpse23007_redirect(){
  if( is_admin() && !defined('DOING_AJAX') && ( current_user_can('subscriber') || current_user_can('contributor') ) ){
    wp_redirect(home_url());
    exit;
  }
}
add_action('init','wpse23007_redirect');
// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Change WordPress Logo ALT text in the Login Menu
function ryu_login_logo_url_title() {
  return 'Put Your Own Text Here';
}
add_filter( 'login_headertitle', 'ryu_login_logo_url_title' );
// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Get rid of the Emojis
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

