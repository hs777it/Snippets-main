<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
                By hs777it@gmail.com / +965 60907666 	
https://tehnoblog.org/how-to-change-font-in-wordpress-admin-dashboard/
https://tehnoblog.org/wordpress-how-to-add-custom-url-parameter-button-in-admin-dashboard-and-execute-function-call/
https://snippets.khromov.se/disabled-wordpress-customizer-preview/		   
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */
/*
Plugin Name: My Toolset
*/


/* Hide Wordpress Version Number
-------------------------------------------- >>> */
// Remove WordPress Meta Generator
remove_action('wp_head', 'wp_generator');

// Hide WordPress Version Info
add_filter('the_generator', 'hide_wordpress_version');
function hide_wordpress_version() {
	return '';
}


// Remove WordPress Version Number In URL Parameters From JS/CSS
function hide_wordpress_version_in_script($src, $handle) {
    $src = remove_query_arg('ver', $src);
	return $src;
}
if (!is_admin()) {
add_filter( 'style_loader_src', 'hide_wordpress_version_in_script', 10, 2 );
add_filter( 'script_loader_src', 'hide_wordpress_version_in_script', 10, 2 );
}
/* Hide Wordpress Version Number
-------------------------------------------- <<< */

// Disable Theme Editor & File Modifications 
add_action('init',function() {
    define( 'DISALLOW_FILE_EDIT', true ); //Disable File Editor
    define( 'DISALLOW_FILE_MODS',true ); //Disable File Modifications
    define('DISABLE_WP_CRON', false );
    define( 'WP_MEMORY_LIMIT', '999M' );
    
  }
);

// Block Access to /wp-admin for non admins.
add_action( 'init', 'hswp_block_access' );
function hswp_block_access() {
  if ( is_user_logged_in() && is_admin() && !current_user_can( 'administrator' ) ) {  // if not work add && (defined('DOING_AJAX') && !DOING_AJAX)
    wp_redirect( home_url('/my-account') );
    exit;
  }
}
