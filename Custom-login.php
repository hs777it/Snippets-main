<?php
/*
Plugin Name: Login Screen
*/


// add a new logo to the login page
function wptutsplus_login_logo() { ?>
    <style type="text/css">
        .login #login h1 a {
            background-image: url( <?php echo plugins_url( 'media/cd-logo-directing-fullcol.png' , __FILE__ ); ?> );
            background-size: 300px auto;
            height: 70px;
        }
        .login #nav a, .login #backtoblog a {
            color: #27adab !important;
        }
        .login #nav a:hover, .login #backtoblog a:hover {
            color: #d228bc !important;
        }
        .login .button-primary {
            background: #27adab; /* Old browsers */
            background: -moz-linear-gradient(top, #27adab 0%, #135655 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#27adab), color-stop(100%,#135655)); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top, #27adab 0%,#135655 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top, #27adab 0%,#135655 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top, #27adab 0%,#135655 100%); /* IE10+ */
            background: linear-gradient(to bottom, #27adab 0%,#135655 100%); /* W3C */
        }
        .login .button-primary:hover {
            background: #85aaaa; /* Old browsers */
            background: -moz-linear-gradient(top, #85aaaa 0%, #208e8c 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#85aaaa), color-stop(100%,#208e8c)); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top, #85aaaa 0%,#208e8c 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top, #85aaaa 0%,#208e8c 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top, #85aaaa 0%,#208e8c 100%); /* IE10+ */
            background: linear-gradient(to bottom, #85aaaa 0%,#208e8c 100%); /* W3C */
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'wptutsplus_login_logo' );
?>