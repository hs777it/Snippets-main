
<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
                By hs777it@gmail.com / +965 60907666 			   
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */
/*
Plugin Name: My Toolset
*/

// https://wordpress.org/plugins/admin-color-schemes/
// https://wordpress.stackexchange.com/questions/126697/wp-3-8-default-admin-colour-for-all-users
// https://wordpress.stackexchange.com/questions/244982/hooking-into-register-admin-color-schemes
// https://www.hongkiat.com/blog/wordpress-admin-color-scheme/
// https://premium.wpmudev.org/blog/customize-wordpress-admin-dashboard-interface/

// https://speckyboy.com/custom-color-palette-wordpress-gutenberg-editor/
// Set Default Admin Color Schemes 
function set_default_admin_color($user_id) {
	$args = array(
		'ID' => $user_id,
		'admin_color' => 'Blue'
	);
	wp_update_user( $args );
}
add_action('user_register', 'set_default_admin_color');

// Hide the color scheme picker from your users
if ( !current_user_can('manage_options') )
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );



////

add_filter( 'get_user_option_admin_color', 'update_user_option_admin_color', 5 );
function update_user_option_admin_color( $color_scheme ) {
    $color_scheme = 'blue';
    return $color_scheme;
}

/////


add_filter('get_user_option_admin_color', 'wpse_313419_conditional_admin_color'); 
function wpse_313419_conditional_admin_color($result) {
    // Dev: use 'light' color scheme
    if(get_site_url() == 'http://dev.example.com') {
        return 'light';
    // Staging: use 'blue' color scheme
    } elseif(get_site_url() == 'http://staging.example.com') {
        return 'blue';
    // Production (all other cases): use 'sunrise' color scheme
    } else {
        return 'sunrise';
    }
}

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
