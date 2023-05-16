<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
By hs777it@gmail.com / +965 60907666
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */


/** ---------------------------------------------- *
    https://woocommerce.com/posts/customize-checkout-fields-woocommerce/
 * ---------------------------------------------- **/

/*
Plugin Name: My Toolset
*/

/**
 Remove all possible fields
**/
add_filter( 'woocommerce_checkout_fields', 'wc_remove_checkout_fields' );
function wc_remove_checkout_fields( $fields ) {

    // Billing fields
    unset( $fields['billing']['billing_company'] );
    unset( $fields['billing']['billing_email'] );
    unset( $fields['billing']['billing_phone'] );
    unset( $fields['billing']['billing_state'] );
    unset( $fields['billing']['billing_first_name'] );
    unset( $fields['billing']['billing_last_name'] );
    unset( $fields['billing']['billing_address_1'] );
    unset( $fields['billing']['billing_address_2'] );
    unset( $fields['billing']['billing_city'] );
    unset( $fields['billing']['billing_postcode'] );

    // Shipping fields
    unset( $fields['shipping']['shipping_company'] );
    unset( $fields['shipping']['shipping_phone'] );
    unset( $fields['shipping']['shipping_state'] );
    unset( $fields['shipping']['shipping_first_name'] );
    unset( $fields['shipping']['shipping_last_name'] );
    unset( $fields['shipping']['shipping_address_1'] );
    unset( $fields['shipping']['shipping_address_2'] );
    unset( $fields['shipping']['shipping_city'] );
    unset( $fields['shipping']['shipping_postcode'] );

    // Order fields
    unset( $fields['order']['order_comments'] );

    return $fields;
}

/**
 Make a required field not required
**/
add_filter( 'woocommerce_billing_fields', 'wc_unrequire_wc_phone_field');
    function wc_unrequire_wc_phone_field( $fields ) {
        $fields['billing_phone']['required'] = true;
        return $fields;
}

/**
Change input field labels and placeholders
**/
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
function custom_override_checkout_fields($fields)
 {
        unset($fields['billing']['billing_address_2']);
        $fields['billing']['billing_company']['placeholder'] = 'Business Name';
        $fields['billing']['billing_company']['label'] = 'Business Name';
        $fields['billing']['billing_first_name']['placeholder'] = 'First Name'; 
        $fields['shipping']['shipping_first_name']['placeholder'] = 'First Name';
        $fields['shipping']['shipping_last_name']['placeholder'] = 'Last Name';
        $fields['shipping']['shipping_company']['placeholder'] = 'Company Name'; 
        $fields['billing']['billing_last_name']['placeholder'] = 'Last Name';
        $fields['billing']['billing_email']['placeholder'] = 'Email Address ';
        $fields['billing']['billing_phone']['placeholder'] = 'Phone ';
    return $fields;
 }