<?php

/**
 * Latest PDF
 */
// Latest PDF By Hussain Saad +965 60907666 hs777it@gmail.com
add_action('parse_request', 'wphs_latest_pdf');
function wphs_latest_pdf($request)
{
	date_default_timezone_set('Asia/Kuwait');
	$yesterday = new DateTime('yesterday');
  if (isset($_GET['pdf']) && $_GET['pdf'] == '20700EAD-276B-4C9E-A491-2B01C69061DB') {
	// $url = get_option( 'siteurl' )."/pdf/" . date('Y') . "/" . date("d-m-Y",strtotime("yesterday")) . ".pdf"; // date("d-n-Y")
	// $url = get_option( 'siteurl' )."/pdf/" . date('Y') . "/" . date('d.m.Y',strtotime("-1 days")) . ".pdf"; 
	$url = get_option( 'siteurl' )."/pdf/" . date('Y') . "/" . $yesterday->format('d-m-Y') . ".pdf"; 
    wp_redirect($url);
      exit;
  }
}

// Latest in Category By Hussain Saad +965 60907666 hs777it@gmail.com
add_action('parse_request', 'wpa_latest_in_category_redirect');
function wpa_latest_in_category_redirect($request)
{
  if (isset($_GET['latest_pdf'])) {
    $latest = new WP_Query(array(
      'cat' => 140, 
      'posts_per_page' => 1
    ));
    if ($latest->have_posts()) {
	  	$post_date = get_the_date( 'd-m-Y', $latest->post->ID );
		$post_date = date('d-m-Y', strtotime("+1 day")); //$post_date = date('d-m-Y', strtotime("+1 day", strtotime($date)));
		$url = get_option( 'siteurl' )."/pdf/" . date('Y') . "/" . $post_date . ".pdf"; 
   		wp_redirect($url);
      //wp_redirect(get_permalink($latest->post->ID));
      exit;
    }
  }
}
