<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
                By hs777it@gmail.com / +965 60907666 			   
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */
/*
Plugin Name: My Toolset
*/

/** ---------------------------------------------- *
    PHP Snippet https://www.webroomtech.com/
    https://rudrastyh.com/wordpress/include-css-and-javascript.html
 * ---------------------------------------------- **/


// Add Code To The Header
add_action('wp_head', 'hswp_head');
function hswp_head(){

    global $post;
    if(is_page('page_slug')) {
        echo '<meta name="robots" content="noindex, nofollow" />';
        echo '<meta http-equiv="refresh" content="5; URL=https://www.google.com/" />';
    }

    echo '<style> YOUR CODE HERE </style>';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';
}

// Redirect Entire Website Except WordPress Admin
add_action('wp_head', 'hswp_redirect_website');
function hswp_redirect_website(){
if ( ! is_admin() ) {
    wp_redirect( 'https://www.google.com' . $_SERVER['REQUEST_URI'], 301 );
    exit;
    }
}
// Add Code To The WordPress Footer
add_action('wp_footer', 'hswp_footer');
function hswp_footer(){
    echo '<script> YOUR CODE HERE </script>';
}





add_action( 'login_form_lostpassword', 'hswp_hook_header' );
add_action( 'lostpassword_form', 'hswp_hook_header' ) ;



// Remove Version Number From CSS & JS
function hs_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
    }
add_filter( 'style_loader_src', 'hs_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'hs_remove_wp_ver_css_js', 9999 );

// Disable WP version
function disable_version() {
    return '';
 }
add_filter('the_generator','disable_version');
remove_action('wp_head', 'wp_generator');


// Hide Admin Toolbar
add_filter( 'show_admin_bar', '__return_false' );
// Or Hide Admin Toolbar
add_filter( 'show_admin_bar' , 'hswp_hide_admin_bar');
function hswp_hide_admin_bar() {
    return false;
}
// Hide Admin Toolbar for all users except Administrators
add_filter( 'show_admin_bar' , 'hswp_admin_bar');
function hswp_admin_bar($show_admin_bar) {
    return ( current_user_can( 'administrator' ) ) ? $show_admin_bar : false;
}

// Change translation
add_filter( 'gettext', function( $translation, $text, $domain ) {
	//if ( $domain == 'woocommerce' ) {
		if ( $text == "Packing Slip" ) { $translation = "Accountant"; }
	//}
	return $translation;
}, 10, 3 );

