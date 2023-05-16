
<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
                By hs777it@gmail.com / +965 60907666 			   
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */
/*
Plugin Name: My Toolset
*/

// https://codex.wordpress.org/Administration_Menus

/* Menu Plugins
//https://ar.wordpress.org/plugins/admin-menu-editor/
//https://wordpress.org/plugins/wp-custom-admin-interface/ 
-------------------------------------------------------------- */


/** ---------------------------------------------- *
    * Remove Menu
 * ---------------------------------------------- **/
// https://developer.wordpress.org/reference/functions/remove_menu_page/
// https://rudrastyh.com/wordpress/get-user-id.html

// You can use the following to debug:
add_action( 'admin_init', function () {
    echo '<pre>' . print_r( $GLOBALS[ 'menu' ], true) . '</pre>';
} );

// Removes some menus by page.
add_action( 'admin_init', 'hswp_remove_menus' );
function hswp_remove_menus(){
    remove_menu_page( 'index.php' );                  //Dashboard
    remove_menu_page( 'jetpack' );                    //Jetpack* 
    remove_menu_page( 'edit.php' );                   //Posts
    remove_menu_page( 'upload.php' );                 //Media
    remove_menu_page( 'edit.php?post_type=page' );    //Pages
    remove_menu_page( 'edit-comments.php' );          //Comments
    remove_menu_page( 'themes.php' );                 //Appearance
    //remove_menu_page( 'plugins.php' );                //Plugins
    remove_menu_page( 'users.php' );                  //Users
    remove_menu_page( 'tools.php' );                  //Tools
    remove_menu_page( 'options-general.php' );        //Settings 

    remove_submenu_page( 'plugins.php', 'plugin-editor.php' ); //Plugins Editor 
  }


// Bridge Theme  
  add_action( 'admin_init', function(){    

    remove_menu_page( 'bridge_core_dashboard' );
	remove_menu_page( 'edit.php?post_type=slides' );
	remove_menu_page( 'edit.php?post_type=carousels' );
	remove_menu_page( 'qode_theme_menu' );
	remove_menu_page( 'vc-general' );
	
} );

// 1. Get Current User ID (and username, email etc)
$current_user_id = get_current_user_id();

// or
$current_user = wp_get_current_user();
$current_user_id = $current_user->ID;

// 2. How to Get User ID by Email?
$the_user = get_user_by('email', 'admin@gmail.com');
$the_user_id = $the_user->ID;

// or
$the_user = get_user_by( 'id', 54 ); // 54 is a user ID
echo $the_user->user_email;

// or
$the_user = get_userdata( 54 );
echo $the_user->user_email;

// 3. Get User ID by Username (login name)
$the_user = get_user_by('login', 'admin');
$the_user_id = $the_user->ID;

// 4. Get User ID by First Name or by Last Name
global $wpdb;
$users = $wpdb->get_results( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'first_name' AND meta_value = 'Misha'" );
if( $users ) {
	foreach ( $users as $user ) {
		echo '<p>' . $user->user_id . '</p>';
	}
} else {
	echo 'There are no users with the specified first name.';
}

// 5. How to Get Author ID by Post ID?
$my_post = get_post( $id ); // $id - Post ID
echo $my_post->post_author; // print post author ID

// 6. How to Get a Customer ID from an Order in WooCommerce?
$customer_id = get_post_meta( 541, '_customer_user', true); // 541 is your order ID

// or
$order = wc_get_order( 541 ); // 541 is your order ID
$customer_id = $order->get_customer_id(); // or $order->get_user_id() – the same

// 8. Add the User ID column to the WordPress Users Table
/*
 * Adding the column
 */
function rd_user_id_column( $columns ) {
	$columns['user_id'] = 'ID';
	return $columns;
}
add_filter('manage_users_columns', 'rd_user_id_column');
 
/*
 * Column content
 */
function rd_user_id_column_content($value, $column_name, $user_id) {
	if ( 'user_id' == $column_name )
		return $user_id;
	return $value;
}
add_action('manage_users_custom_column',  'rd_user_id_column_content', 10, 3);
 
/*
 * Column style (you can skip this if you want)
 */
function rd_user_id_column_style(){
	echo '<style>.column-user_id{width: 5%}</style>';
}
add_action('admin_head-users.php',  'rd_user_id_column_style');