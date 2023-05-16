<?php

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
	$files = glob(ABSPATH . 'pdf/*.*', GLOB_BRACE);
    //usort($files, function ($a, $b) { return filemtime($a) - filemtime($b); });  // sort array by date/time
    $list = array();
    foreach ($files as $file) {
        $file = str_replace(ABSPATH . 'pdf/', '', $file);
		$file = str_replace('.pdf', '', $file);
		if (is_numeric($file)) { array_push($list, $file); }
    }
    if (isset($_GET['latest'])) {
		//$last_file = $list[array_key_last($list)];
		$file = "http://" . $_SERVER['SERVER_NAME'] . "/wp/pdf/" . max($list) . '.pdf';
		wp_redirect($file);
		exit();
    }
}

