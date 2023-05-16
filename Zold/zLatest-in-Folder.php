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
    usort($files, function ($a, $b) { return filemtime($a) - filemtime($b); });
    $list = array();
    foreach ($files as $file) {
        $file = str_replace(ABSPATH . 'pdf/', '', $file);
        array_push($list, $file);
    }
    if (isset($_GET['latest'])) {
		$file = "http://" . $_SERVER['SERVER_NAME'] . "/wp/pdf/" .$list[array_key_last($list)];//OR // echo $list[count($list) - 1];
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
