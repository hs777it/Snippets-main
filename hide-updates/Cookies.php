//Set Cookies
<?php

	add_action( 'init', 'setting_my_first_cookie' );
	function setting_my_first_cookie() {
	 setcookie( $v_username, $v_value, 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}
	
?>

//Get Cookies
<?php

	if(!isset($_COOKIE[$v_username])) {
	  echo "The cookie: '" . $v_username . "' is not set.";
	} else {
	  echo "The cookie '" . $v_username . "' is set.";
	  echo "Cookie is:  " . $_COOKIE[$v_username];
	}

?>

//Delete Cookies
<?php

  unset( $_COOKIE[$v_username] );
  setcookie( $v_username, '', time() - ( 15 * 60 ) );

?>

// to redirect your visitors to your homepage
wp_redirect( home_url(), 302 );
exit;


=============================================================================

<?php

if (!isset($_COOKIE['cookie']))
{
    header('Location: http://www.example.com/index2.php');
    exit;
}

?>



===============================================================================

Just Create a new folder in your root directory.
create index.php (or some other name) in that folder, and add the following code

	$admin_cookie_code="2145446497812";
	setcookie("CMSAdminSession",$admin_cookie_code,0,"/");
	header("Location: ../administrator/index.php");

Then add the following code inside administrator/index.php . 

if($_COOKIE['JoomlaAdminSession']!= "159753987456321")
{
    header('Location:../index.php');
}