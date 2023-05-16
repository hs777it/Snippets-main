<?php


// https://smartmedia-kw.com/kfeoch/wp-content/plugins/bridge-core/modules/core-dashboard/assets/img/admin-logo-icon.png
add_action( 'admin_enqueue_scripts', function(){
    $css = <<<EOT
#adminmenu #toplevel_page_woocommerce .menu-icon-generic div.wp-menu-image::before {
    content: ' ';
    background: url('https://theme.zdassets.com/theme_assets/9312424/ee882d4d41ddd9481e1a562798a3c9c35f2c272a.png') no-repeat center;
    background-size: contain;
}
EOT;
wp_add_inline_style( 'woocommerce_admin_menu_styles', $css );}, 11 );


// OR
add_action("admin_menu", function () {
    foreach ($GLOBALS["menu"] as $position => $tab) {
        if ("woocommerce" === $tab["2"]) {
            $GLOBALS["menu"][$position][6] = "https://theme.zdassets.com/theme_assets/9312424/ee882d4d41ddd9481e1a562798a3c9c35f2c272a.png";
            break;
        }
    }
});