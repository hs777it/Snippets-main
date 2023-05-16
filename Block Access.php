<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
                By hs777it@gmail.com / +965 60907666 			   
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */
/*
Plugin Name: My Toolset
*/

/** ---------------------------------------------- *
    https://wordpress.stackexchange.com/questions/257913/stop-redirection-on-wp-admin-call-to-wp-login
    https://wordpress.stackexchange.com/questions/62889/disable-or-redirect-wp-login-php
 * ---------------------------------------------- **/

// Redirect registration to a custom url
add_action( 'login_form_register', 'redirect_register' );
function redirect_register() {
    wp_redirect( home_url( '/registration' ) );
    exit(); 
}

// Redirect logout
add_action('wp_logout','auto_redirect_after_logout');
    function auto_redirect_after_logout(){
        wp_redirect( get_site_url().'/login/' );
        exit();
}

// Redirect login wp-admin / wp-login.php to custom login
add_action( 'login_form_login', 'redirect_login' );
function redirect_login() {
    wp_redirect( home_url( '/login' ) ); 
    exit;
}

// Block Access to wp-admin for non admins and redirect to custom page
add_action( 'init', 'hswp_block_access' );
function hswp_block_access() {
  if ( is_user_logged_in() && is_admin() && !current_user_can( 'administrator' ) ) {  // if not work add && (defined('DOING_AJAX') && !DOING_AJAX)
    wp_redirect( home_url('/my-account') );
    exit;
  }
}

// wp-admin to 404
add_filter('auth_redirect_scheme', 'stop_redirect', 9999);
function stop_redirect($scheme) {
    if ( $user_id = wp_validate_auth_cookie( '',  $scheme) ) {
        return $scheme;
    }

    global $wp_query;
    $wp_query->set_404();
    get_template_part( 404 );
    exit();
}
add_action('init', 'remove_default_redirect');
function remove_default_redirect() {
    remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);
}


add_filter( 'registration_redirect', 'hswp_registration_redirect' );
function hswp_registration_redirect() {
    return home_url( '/my-page' );
}



/** ---------------------------------------------- ---------------------------------------------- ---------------------------------------------- */

// Custom Login
// https://codex.wordpress.org/Plugin_API/Filter_Reference/login_url#Examples
add_filter('login_url', 'custom_login_url', 10, 3);
function custom_login_url($login_url, $redirect, $force_reauth) {
    return home_url('/login/?redirect_to=' . $redirect);
}

// wp-login.php
add_action('init','custom_login');
function custom_login(){

    global $pagenow;
    
    if( 'wp-login.php' == $pagenow && !is_user_logged_in()) { //if( 'wp-login.php' == $pagenow && $_GET['action']!="logout") 
        
        global $wp_query;
        $wp_query->set_404();
        get_template_part( 404 );
        exit();
    }
}
// wp-login.php
function custom_login_page() {
    $new_login_page_url = home_url( '/login/' ); // new login page
    global $pagenow;
    if( $pagenow == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
       wp_redirect($new_login_page_url);
       exit;
    }
   } 
   if(!is_user_logged_in()){
    add_action('init','custom_login_page');
}


