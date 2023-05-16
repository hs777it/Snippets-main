<?php

/**
 * Admin - Filter by Author
 *
 * &nbsp;
 * 
 * 
 */
function my_author_filter(){
    $screen = get_current_screen();
    global $post_type;
    if( $screen->id == 'edit-post' ){
        $my_args = array(
            'show_option_all'   => 'All Authors',
            'orderby'           => 'display_name',
            'order'             => 'ASC',
            'name'              => 'authors_admin_filter',
            'who'               => 'authors',
            'include_selected'  => true
        );
        if(isset($_GET['authors_admin_filter'])){
            $my_args['selected'] = (int)sanitize_text_field($_GET['authors_admin_filter']);
        }
        wp_dropdown_users($my_args);
    }
}
add_action('restrict_manage_posts','my_author_filter');
