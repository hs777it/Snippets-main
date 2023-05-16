<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
                By hs777it@gmail.com / +965 60907666 			   
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */
/*
Plugin Name: My Toolset
*/

/** ---------------------------------------------- *
    * Redirect to: User LoggedIn,
    https://wordpress.stackexchange.com/questions/52114/admin-page-redirect
 * ---------------------------------------------- **/


// Redirect Entire Website Except Admin
add_action('wp_head', 'hswp_redirect_website');
function hswp_redirect_website(){
if ( ! is_admin() ) {
    wp_redirect( 'https://www.google.com' . $_SERVER['REQUEST_URI'], 301 );
    exit;
}


// Redirect login to a custom url
add_action( 'login_form_login', 'redirect_login' );
function redirect_login() {
    wp_redirect( home_url( '/login' ) );
    exit(); 
}
// Redirect registration to a custom url
add_action( 'login_form_register', 'redirect_register' );
function redirect_register() {
    wp_redirect( home_url( '/registration' ) );
    exit(); 
}


add_action('get_header', 'admin_redirect');
function admin_redirect() {
    if ( (!is_user_logged_in()) && (!is_page("my-account")) ) {
       wp_redirect( home_url('wp-admin') );
       exit;
    }
}

add_action( 'template_redirect', 'my_frontpage_redirect' );
function my_frontpage_redirect() {
    if ( ! is_user_logged_in() ) {
        if ( ! is_front_page() && ! is_page( 'my-password-reset' ) ) {
            wp_redirect( home_url() );
            exit();
        }
    }
}



// Redirect Users to pages
add_action('wp', 'hswp_redirect');
function hswp_redirect()
{
	if ( is_user_logged_in() && is_page('login') ) {  // page_id = 495
        wp_redirect(get_site_url().'/my-account/');
        exit;
    }
	if ( !is_user_logged_in() && is_page('my-account') ) { // page_id = 508
        wp_redirect(get_site_url().'/login/');
        exit;
    }

    if ( is_user_logged_in() && is_page('service') ) {   // page_id = 505
        wp_redirect(get_site_url().'/my-account/');
        exit;
    }
	if ( !is_user_logged_in() && is_page('service') ) {
        wp_redirect(get_site_url().'/login/');
        exit;
    }
}

// wp-admin / wp-login.php to custom login
add_action( 'login_form_login', 'redirect_login' );
function redirect_login() {
    wp_redirect( home_url( '/login' ) ); 
}
// wp-admin for non admins redirect to custom page
add_action( 'init', 'hswp_block_access' );
function hswp_block_access() {
  if ( is_user_logged_in() && is_admin() && !current_user_can( 'administrator' ) ) {  // if not work add && (defined('DOING_AJAX') && !DOING_AJAX)
    wp_redirect( home_url('/my-account') );
    exit;
  }
}

// $_GET['action'] to custom page
add_action('init','possibly_redirect');
function possibly_redirect(){
    global $pagenow;
    if( 'wp-login.php' == $pagenow ) {
      if ( isset( $_POST['wp-submit'] ) ||   // in case of LOGIN
        ( isset($_GET['action']) && $_GET['action']=='logout') ||   // in case of LOGOUT
        ( isset($_GET['checkemail']) && $_GET['checkemail']=='confirm') ||   // in case of LOST PASSWORD
        ( isset($_GET['checkemail']) && $_GET['checkemail']=='registered') ) return;    // in case of REGISTER
      else wp_redirect( home_url() ); // or wp_redirect(home_url('/login'));
      exit();
    }
}


// Disable Password Reset
add_filter ( 'allow_password_reset', 'hspw_disable_password_reset' );
function hspw_disable_password_reset() { 
    return false;
}

// Disable Password Reset and Redirect
add_action('init','hswp_redirect_user'); 
function hswp_redirect_user(){ 
    if (isset( $_GET['action'] )){  
        if ( in_array( $_GET['action'], array('lostpassword', 'retrievepassword') ) ) {
        wp_redirect( '/wp-login.php' ); exit;
        // Or
        //wp_redirect( wp_login_url(), 301 ); exit;
        }
    }
}

// Remove Lost Password Link
add_filter( 'gettext', 'remove_lostpassword_text' );
function remove_lostpassword_text ( $text ) {
if ($text == 'Lost your password?'){$text = '';} 
return $text; 
}

// Lost Password URL to custom page
add_filter( 'lostpassword_url',  'wdm_lostpassword_url', 10, 0 );
function wdm_lostpassword_url() {
    return site_url('/signin?action=lostpassword');
}

// Redirect after password reset
// https://wordpress.stackexchange.com/questions/82955/wordpress-redirect-after-password-reset/83153
add_action('after_password_reset', 'hswp_lost_password_redirect');
function hswp_lost_password_redirect() {
    wp_redirect( home_url() ); 
    exit;
}
// Or
add_action('login_headerurl', 'hswp_lost_password_redirect');
function hswp_lost_password_redirect() {

    // Check if have submitted
    $confirm = ( isset($_GET['action'] ) && $_GET['action'] == resetpass );

    if( $confirm ) {
        wp_redirect( home_url() );
        exit;
    }
}

// login_form_lostpassword, lostpassword_form
add_action( 'login_form_lostpassword', 'hswp_hook_header' );
add_action( 'lostpassword_form', 'hswp_hook_header' ) ;
function hswp_hook_header() {
    if ( in_array( $_GET['action'], array('lostpassword', 'retrievepassword') ) ) {
?>
        <style>
        .login-action-lostpassword .message { border-left: 17px solid #71306f; }
        .login #login_error, .login .message, .login .success { display: none;}
        #login .message, .login #nav { display: none !important;}
        #login .message, .login #nav { display: none !important;}
        .login #backtoblog {font-size: 13px;padding: 0px 24px;margin: 20px;text-align: center;}
        #backtoblog a {margin: 40px 0;background: #007cba;color: #ffffff !important;padding: 5px 10px !important;margin-top: 20px !important;}
        </style>

<?php 
    }  
}

// Login logo
// https://developer.wordpress.org/themes/functionality/custom-logo/
add_action( ‘login_enqueue_scripts’, ‘my_login_logo’ ); 
function my_login_logo() { 
?>
    <style>
        #login h1 a, .login h1 a {
        background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/new-logo.png);
        height:65px;
        width:320px;
        background-size: 320px 65px;
        background-repeat: no-repeat;
        padding-bottom: 30px;
        }
    </style>
<?php 
}
 
add_action( 'login_enqueue_scripts', 'my_login_logo_one' );
function my_login_logo_one() { 
?> 
    <style type="text/css"> 
        body.login div#login h1 a {
            background-image: url('logo.png');  
        padding-bottom: 30px; 
        } 
    </style>
<?php 
} 












// not tested
add_action( 'woocommerce_customer_reset_password', 'woocommerce_new_pass_redirect' );
function woocommerce_new_pass_redirect( $user ) {
    wp_redirect( get_permalink(woocommerce_get_page_id('my-account')));
    exit;
  }
// not tested
if ( $_GET[reset] = true && ( 'lost_password' == $args['form']) ) {
    $my_account_url = get_site_url() . '/my-account';
    echo "<script>window.setTimeout(function(){window.location.replace('$my_account_url')}, 5000)</script>";
}

// is_page array
if( is_page( array( 'about-us', 'contact' ) ){ // hide your header; }


