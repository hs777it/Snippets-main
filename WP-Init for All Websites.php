
<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
                By hs777it@gmail.com / +965 60907666 	
https://tehnoblog.org/how-to-change-font-in-wordpress-admin-dashboard/
https://tehnoblog.org/wordpress-how-to-add-custom-url-parameter-button-in-admin-dashboard-and-execute-function-call/		   
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */
/*
Plugin Name: My Toolset
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




/* Start WordPress Custom Font 
-------------------------------------------- >>> */
// WordPress Custom Font @ Admin
function admin_custom_font() {
    echo '<link href="https://fonts.googleapis.com/css?family=Mina:400,700" rel="stylesheet">' . PHP_EOL;
    echo '<style>body, #wpadminbar *:not([class="ab-icon"]), .wp-core-ui, .media-menu, .media-frame *, .media-modal *{font-family:"Mina",sans-serif !important;}</style>' . PHP_EOL;
}
add_action( 'admin_head', 'admin_custom_font' );

// WordPress Custom Font @ Admin Frontend Toolbar
function admin_custom_font_frontend_toolbar() {
    if(current_user_can('administrator')) {
        echo '<link href="https://fonts.googleapis.com/css?family=Mina:400,700" rel="stylesheet">' . PHP_EOL;
        echo '<style>#wpadminbar *:not([class="ab-icon"]){font-family:"Mina",sans-serif !important;}</style>' . PHP_EOL;
    }
}
add_action( 'wp_head', 'admin_custom_font_frontend_toolbar' );

// WordPress Custom Font @ Admin Login
function admin_custom_font_login_page() {
    if(stripos($_SERVER["SCRIPT_NAME"], strrchr(wp_login_url(), '/')) !== false) {
        echo '<link href="https://fonts.googleapis.com/css?family=Mina:400,700&" rel="stylesheet">' . PHP_EOL;
        echo '<style>body{font-family:"Mina",sans-serif !important;}</style>' . PHP_EOL;
    }
}
add_action( 'login_head', 'admin_custom_font_login_page' );
/* End WordPress Custom Font 
-------------------------------------------- <<< */

// Disable Admin Bar
add_filter('show_admin_bar', '__return_false');

// Disable Theme Editor & File Modifications 
add_action('init',function() {
    define( 'DISALLOW_FILE_EDIT', true ); //Disable File Editor
    define( 'DISALLOW_FILE_MODS',true ); //Disable File Modifications 
  }
);

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

// Custom Admin footer
function wpexplorer_remove_footer_admin () {
	echo '<span id="footer-thankyou">Built with love by <a href="//smartmedia-kw.com/" target="_blank">SmartMedia</a></span>';
}
add_filter( 'admin_footer_text', 'wpexplorer_remove_footer_admin' );
