
<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
                By hs777it@gmail.com / +965 60907666 			   
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */
/*
Plugin Name: My Toolset
*/


// Replace Posts label as News in Admin Panel 
add_filter( 'post_type_labels_post', 'change_post_labels' );
function change_post_labels( $args ) {
        foreach( $args as $key => $label ){
            $args->{$key} = str_replace( [ __( 'Posts' ), __( 'Post' ) ], __( 'News' ), $label );
        }
        return $args;
}

// Or
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

