
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
    * Add Menu
 * ---------------------------------------------- **/
// https://developer.wordpress.org/reference/functions/add_menu_page/
// https://whiteleydesigns.com/editing-wordpress-admin-menus/

add_menu_page( string $page_title, string $menu_title, string $capability,
        string $menu_slug, callable $function = '', string $icon_url = '', 
        int $position = null );
        
// Add a custom menu item to the WordPress admin menu, for a user with administrator capability:

// Method 1:

// Register(Hook) a custom menu page.
add_action( 'admin_menu', 'wp1_register_my_custom_menu_page' );
// Action function for above hook
function wp1_register_my_custom_menu_page() {
    add_menu_page(
        __( 'Menu Title', 'textdomain' ),
        'Menu Name',
        'manage_options',
        'myplugin/myplugin-admin.php',
        '',
        plugins_url( 'myplugin/images/icon.png' ),2 // 'dashicons-welcome-widgets-menus'
    );
}


// Method 2:

// Register(Hook) a custom menu page.
add_action( 'admin_menu', 'wp2_register_my_custom_menu_page' );
// Adds a new top-level page to the administration menu. // Action function for above hook
function wp2_register_my_custom_menu_page(){
    add_menu_page( 
        __( 'Menu Title', 'textdomain' ),
        'Menu Name',
        'manage_options',
        'menu-slug',
        'my_custom_menu_page',
        plugins_url( 'myplugin/images/icon.png' ),2 // 'dashicons-welcome-widgets-menus'
    ); 
}
// Display a custom menu page
function my_custom_menu_page(){
    esc_html_e( 'Admin Page Test', 'textdomain' );  
}

// Method 2:
<?php
//* Register Projects Post Type
add_action( 'init', 'wd_projects_cpt' );
function wd_projects_cpt() {
	$labels = array(
		'name'               => _x( 'Projects', 'post type general name' ),
		'singular_name'      => _x( 'Project', 'post type singular name' ),
		'menu_name'          => _x( 'Projects', 'admin menu' ),
		'name_admin_bar'     => _x( 'Project', 'add new on admin bar' ),
		'add_new'            => _x( 'Add New', 'Project' ),
		'add_new_item'       => __( 'Add New Project' ),
		'new_item'           => __( 'New Project' ),
		'edit_item'          => __( 'Edit Project' ),
		'view_item'          => __( 'View Project' ),
		'all_items'          => __( 'All Projects' ),
		'search_items'       => __( 'Search Projects' ),
		'parent_item_colon'  => __( 'Parent Projects:' ),
		'not_found'          => __( 'No Projects found.' ),
		'not_found_in_trash' => __( 'No Projects found in Trash.' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Whiteley Designs Project Showcase.' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'project' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 11,
		'menu_icon'          => 'dashicons-portfolio',
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail' )
	);

	register_post_type( 'project', $args );
}



/** ---------------------------------------------- *
    * Remove Menu
 * ---------------------------------------------- **/
// https://developer.wordpress.org/reference/functions/remove_menu_page/

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


/** ---------------------------------------------- *
    * Rename Menu
 * ---------------------------------------------- **/
// https://wordpress.stackexchange.com/questions/9211/changing-admin-menu-labels
  function wd_admin_menu_rename() {
       global $menu; // Global to get menu array
       global $submenu; // Global to get submenu array
       $menu[5][0] = 'Portfolio'; // Change name of posts to portfolio
       $submenu['edit.php'][5][0] = 'All Portfolio Items'; // Change name of all posts to all portfolio items
  }
  add_action( 'admin_menu', 'wd_admin_menu_rename' );


  function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Contacts';
    $submenu['edit.php'][5][0] = 'Contacts';
    $submenu['edit.php'][10][0] = 'Add Contacts';
    $submenu['edit.php'][15][0] = 'Status'; // Change name for categories
    $submenu['edit.php'][16][0] = 'Labels'; // Change name for tags
    echo '';
}

function change_post_object_label() {
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = 'Contacts';
        $labels->singular_name = 'Contact';
        $labels->add_new = 'Add Contact';
        $labels->add_new_item = 'Add Contact';
        $labels->edit_item = 'Edit Contacts';
        $labels->new_item = 'Contact';
        $labels->view_item = 'View Contact';
        $labels->search_items = 'Search Contacts';
        $labels->not_found = 'No Contacts found';
        $labels->not_found_in_trash = 'No Contacts found in Trash';
    }
    add_action( 'init', 'change_post_object_label' );
    add_action( 'admin_menu', 'change_post_menu_label' );


// CUSTOMIZE ADMIN MENU ORDER
   function custom_menu_order($menu_ord) {
       if (!$menu_ord) return true;
       return array(
        'index.php', // this represents the dashboard link
        'edit.php', //the posts tab
        'upload.php', // the media manager
        'edit.php?post_type=page', //the posts tab
    );
   }
   add_filter('custom_menu_order', 'custom_menu_order');
   add_filter('menu_order', 'custom_menu_order');


///////////////////////////////////////////////////////////////////////////////////////////////////
add_filter( 'post_type_labels_post', 'news_rename_labels' );

/**
* Rename default post type to news
*
* @param object $labels
* @hooked post_type_labels_post
* @return object $labels
*/
function news_rename_labels( $labels )
{
    # Labels
    $labels->name = 'News';
    $labels->singular_name = 'News';
    $labels->add_new = 'Add News';
    $labels->add_new_item = 'Add News';
    $labels->edit_item = 'Edit News';
    $labels->new_item = 'New News';
    $labels->view_item = 'View News';
    $labels->view_items = 'View News';
    $labels->search_items = 'Search News';
    $labels->not_found = 'No news found.';
    $labels->not_found_in_trash = 'No news found in Trash.';
    $labels->parent_item_colon = 'Parent news'; // Not for "post"
    $labels->archives = 'News Archives';
    $labels->attributes = 'News Attributes';
    $labels->insert_into_item = 'Insert into news';
    $labels->uploaded_to_this_item = 'Uploaded to this news';
    $labels->featured_image = 'Featured Image';
    $labels->set_featured_image = 'Set featured image';
    $labels->remove_featured_image = 'Remove featured image';
    $labels->use_featured_image = 'Use as featured image';
    $labels->filter_items_list = 'Filter news list';
    $labels->items_list_navigation = 'News list navigation';
    $labels->items_list = 'News list';

    # Menu
    $labels->menu_name = 'News';
    $labels->all_items = 'All News';
    $labels->name_admin_bar = 'News';

    return $labels;
}


?>