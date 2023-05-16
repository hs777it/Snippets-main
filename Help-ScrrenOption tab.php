<?php

// Hide 'Help' tab
add_filter('contextual_help_list','contextual_help_list_remove');
function contextual_help_list_remove(){
    global $current_screen;
    $current_screen->remove_help_tabs();
}
// Hide 'Screen Options' tab
add_filter('screen_options_show_screen', 'remove_screen_options_tab');
function remove_screen_options_tab() {
    return false;
}

add_action('admin_head', 'hswp_remove_help_tabs');
function hswp_remove_help_tabs() {
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}



// hide the help tab with CSS 
function hide_help() {
    if(is_admin()){
    echo '<style type="text/css">
            #contextual-help-link-wrap { display: none !important; }
          </style>';
    }
}
add_action('admin_head', 'hide_help');