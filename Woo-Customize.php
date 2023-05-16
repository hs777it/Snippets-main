<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
By hs777it@gmail.com / +965 60907666
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */


/** ---------------------------------------------- *
    

 * ---------------------------------------------- **/

/*
Plugin Name: My Toolset
*/

// Add Button to Listing Actions in Orders
// Add your custom order status action button (for orders with "processing" status)
add_filter( 'woocommerce_admin_order_actions', 'add_custom_order_status_actions_button', 100, 2 );
function add_custom_order_status_actions_button( $actions, $order ) {
	if ( $order->get_status() == 'trash' ) {
			return;
		}
    // Display the button for all orders that have a 'processing' status
    //if ( $order->has_status( array( 'processing','completed' ) ) ) {

        // Get Order ID (compatibility all WC versions)
        $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
        // Set the action button
        $actions['certificate'] = array(
            'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=certificate&order_id=' . $order_id ), 'woocommerce-mark-order-status' ),
            'name'      => __( 'Print Certificate', 'woocommerce' ),
            'action'    => "view certificate", // keep "view" class for a clean button CSS
        );
    //}
    return $actions;
}

// Set Here the WooCommerce icon for your action button
add_action( 'admin_head', 'add_custom_order_status_actions_button_css' );
function add_custom_order_status_actions_button_css() {
    echo '<style>.view.certificate::after { font-family: woocommerce; content: "\f121" !important; }</style>';
}