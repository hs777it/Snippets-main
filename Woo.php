<?php
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=
By hs777it@gmail.com / +965 60907666
=-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  */


/** ---------------------------------------------- *
    https://woocommerce.github.io/code-reference/packages/WooCommerce-Webhooks.html 
	https://www.businessbloomer.com/woocommerce-visual-hook-guide-single-product-page 
	https://rudrastyh.com/woocommerce/my-account-menu.html
	https://rudrastyh.com/woocommerce/checkout-fields.html
	https://rudrastyh.com/woocommerce/place-order-button-text.html
	https://njengah.com/woocommerce-change-checkout-button-text/
	https://stackoverflow.com/questions/42570982/adding-multiple-items-to-woocommerce-cart-at-once
 * ---------------------------------------------- **/

/*
Plugin Name: My Toolset
*/


// WooCommerce Skip Cart Code
add_filter('woocommerce_add_to_cart_redirect', 'skip_woo_cart');
function skip_woo_cart(){
    //global $woocommerce;
    return wc_get_checkout_url();
}


// Change WooCommerce Add To Cart Button Text
add_filter('woocommerce_product_single_add_to_cart_text', 'hswp_cart_text'); // single product page
add_filter('woocommerce_product_add_to_cart_text', 'hswp_cart_text'); // product archives(Collection) page
function hswp_cart_text(){
    return __('Subscribe', 'woocommerce');
}
// Or
function hswp_cart_text2(){
if (is_shop()) {
    $var = __('Subscribe', 'woocommerce');
}
return $var;
}

// Removing the coupon code section from the Checkout/Cart page
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
//Or
function hs_hide_coupon_field_on_woocommerce( $enabled ) {
	if ( is_checkout() || is_cart() ) {
		$enabled = false;
	}
	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hs_hide_coupon_field_on_woocommerce' );


// Removing View Cart
add_filter( 'wc_add_to_cart_message_html', '__return_null' );
//Or
add_filter( 'wc_add_to_cart_message_html', '__return_false');
//Or the normal way:
	//add_filter( 'wc_add_to_cart_message_html', 'empty_wc_add_to_cart_message');
	//function empty_wc_add_to_cart_message( $message, $products ) { 
	//    return ''; 
	//};



// Remove Tabs from My Account
add_filter ( 'woocommerce_account_menu_items', 'hswp_remove_account_links' );
function hswp_remove_my_account_links( $menu_links ){
 
	unset( $menu_links['edit-address'] ); // Addresses
	//unset( $menu_links['dashboard'] ); // Remove Dashboard
	//unset( $menu_links['payment-methods'] ); // Remove Payment Methods
	//unset( $menu_links['orders'] ); // Remove Orders
	//unset( $menu_links['downloads'] ); // Disable Downloads
	//unset( $menu_links['edit-account'] ); // Remove Account details tab
	//unset( $menu_links['customer-logout'] ); // Remove Logout link
 
	return $menu_links;
}
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= 
It was simple enough, but we have not finished yet, if you go to /my-account/edit-address/ directly,
 it will show you Addresses page. This should not happen, should it?
The first thought that came to my mind was to remove endpoints somehow.
 Dealing with $wp_rewrite or something like that. Please do not!

The above code is good. But when you want to remove both the menu item and its page as well,
 you do not need any coding. You can find all the default My Account subpages in 
 WooCommerce > Settings > Account. All you need is just to set a specific endpoint empty.
 =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */

// Rename Tabs in My Account
add_filter ( 'woocommerce_account_menu_items', 'hswp_rename_account_menu' );  
function hswp_rename_account_menu( $menu_links ){
	$menu_links['downloads'] = 'My Files';
	return $menu_links;
}

// Add My Account Menu Links
// CSS - Icon
//body.woocommerce-account ul li.woocommerce-MyAccount-navigation-link--anyuniquetext123 a:before{ content: "\f1fd" }
add_filter ( 'woocommerce_account_menu_items', 'hswp_one_more_link' );
function hswp_one_more_link( $menu_links ){
	
	$new = array( 'gift' => 'Gift for you' ); // we will hook "gift" later
 
	// or in case you need 2 links
	// $new = array( 'link1' => 'Link 1', 'link2' => 'Link 2' );
 
	// array_slice() is good when you want to add an element between the other ones
	$menu_links = array_slice( $menu_links, 0, 1, true ) 
	+ $new 
	+ array_slice( $menu_links, 1, NULL, true );
 
	return $menu_links;
}
add_filter( 'woocommerce_get_endpoint_url', 'hswp_hook_endpoint', 10, 4 );
function hswp_hook_endpoint( $url, $endpoint, $value, $permalink ){
	if( $endpoint === 'gift' ) {
		// ok, here is the place for your custom URL, it could be external
		$url = site_url();
	}
	return $url;
}

// Add Custom My Account Menu Tabs with their Own Pages
/*
 * Step 1. Add Link (Tab) to My Account menu
 */
add_filter ( 'woocommerce_account_menu_items', 'hswp_log_history_link', 40 );
function hswp_log_history_link( $menu_links ){
 
	$menu_links = array_slice( $menu_links, 0, 5, true ) 
	+ array( 'log-history' => 'Log history' )
	+ array_slice( $menu_links, 5, NULL, true );
 
	return $menu_links;
 
}
/*
 * Step 2. Register Permalink Endpoint
 */
add_action( 'init', 'hswp_add_endpoint' );
function hswp_add_endpoint() {
 
	// WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
	add_rewrite_endpoint( 'log-history', EP_PAGES );
 
}
/*
 * Step 3. Content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
 */
add_action( 'woocommerce_account_log-history_endpoint', 'hswp_my_account_endpoint_content' );
function hswp_my_account_endpoint_content() {
 
	// of course you can print dynamic content here, one of the most useful functions here is get_current_user_id()
	echo 'Last time you logged in: yesterday from Safari.';
 
}
/*
 * Step 4
 */
// Go to Settings > Permalinks and just push "Save Changes" button.


// Action Hooks that Allows to Add Any Content Before and After WooCommerce My Account Menu
add_action('woocommerce_before_account_navigation', 'hswp_content_before');
function hswp_content_before(){
	echo 'blah blah blah before';
}
 
add_action('woocommerce_after_account_navigation', 'hswp_content_after');
function hswp_content_after(){
	?>
	<p>blah blah blah after</p>
	<?php
}


 /* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */

// Woocommerce Checkout_fields
add_filter( 'woocommerce_checkout_fields' , 'hswp_print_all_fields' );
function hswp_print_all_fields( $fields ) {
 
	//if( !current_user_can( 'manage_options' ) )
	//	return; // in case your website is live
 
	print_r( $fields ); // wrap results in pre html tag to make it clearer
	exit;
}


//Method 1. Change button text with woocommerce_order_button_text hook
// Change checkout button text  "Place Order" to custom text in checkout page 
add_filter( 'woocommerce_order_button_text', 'hswp_change_checkout_button_text' );
function hswp_change_checkout_button_text( $button_text ) {
   return 'Submit'; 
   // return __( 'Your new button text here', 'woocommerce' ); 
}
// OR
// change button text if a specific product is in the cart
add_filter( 'woocommerce_order_button_text', 'misha_custom_button_text_for_product' );
function misha_custom_button_text_for_product( $button_text ) {
	$product_id = 18; // a specific product ID you would like to check
	if( WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product_id ) ) ) {
		$button_text = 'Submit';
	}
	return $button_text;
}

// Method 2. Change button text with woocommerce_order_button_html hook
// Change button text with woocommerce_order_button_html hook
/* 	The hook woocommerce_order_button_html accepts only one parameter which is the button HTML.
	This hook also allows you to create the button HTML from scratch. */
add_filter( 'woocommerce_order_button_html', 'misha_custom_button_html' );
function misha_custom_button_html( $button_html ) {
	$button_html = str_replace( 'Place order', 'Submit', $button_html );
	return $button_html;
}
// to create the button HTML from scratch
add_filter( 'woocommerce_order_button_html', 'hswp_custom_button_html' );
function hswp_custom_button_html( $button_html ) {
	$order_button_text = 'Submit';
	$button_html = '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' 
	. esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>';
}


// Change Button Text for Payment Gateways
/* 
	Even if a payment gateway doesn’t have a hook for that,
	it must support localization. So, 
	gettext hook will help us in this case.
*/
add_filter( 'gettext', 'hswp_custom_paypal_button_text', 20, 3 );
function hswp_custom_paypal_button_text( $translated_text, $text, $domain ) {
	if( $translated_text == 'Proceed to PayPal' ) {
		$translated_text = 'Pay with PayPal'; // new button text is here
	}
	return $translated_text;
}
 /* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */
 // Removing "Cart totals":
 add_filter( 'gettext', 'change_cart_totals_text', 20, 3 );
function change_cart_totals_text( $translated, $text, $domain ) {
    if( is_cart() && $translated == 'Cart totals' ){
        $translated = '';
    }
    return $translated;
}
// Replace (or change) "Cart totals":
add_filter( 'gettext', 'change_cart_totals_text', 20, 3 );
function change_cart_totals_text( $translated, $text, $domain ) {
    if( is_cart() && $translated == 'Cart totals' ){
        $translated = __('Your custom text', 'woocommerce');
    }
    return $translated;
}

 /* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */

// Add multiple Products to Cart
function wc_add_multiple_products_to_cart() {
// Make sure WC is installed, and add-to-cart qauery arg exists, and contains at least one comma.
if ( ! class_exists( 'WC_Form_Handler' ) || empty( $_REQUEST['add-to-cart'] ) || false === strpos( $_REQUEST['add-to-cart'], ',' ) ) {
	return;
}

// Remove WooCommerce's hook, as it's useless (doesn't handle multiple products).
// https://stackoverflow.com/questions/42570982/adding-multiple-items-to-woocommerce-cart-at-once
remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );
	$product_ids = explode( ',', $_REQUEST['add-to-cart'] );
	$count       = count( $product_ids );
	$number      = 0;

foreach ( $product_ids as $product_id ) {
	if ( ++$number === $count ) {
		// Ok, final item, let's send it back to woocommerce's add_to_cart_action method for handling.
		$_REQUEST['add-to-cart'] = $product_id;
		return WC_Form_Handler::add_to_cart_action();
	}

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
		$was_added_to_cart = false;
		$adding_to_cart    = wc_get_product( $product_id );

	if ( ! $adding_to_cart ) {
		continue;
	}

	$add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $adding_to_cart->product_type, $adding_to_cart );

	/*
		* Sorry.. if you want non-simple products, you're on your own.
		*
		* Related: WooCommerce has set the following methods as private:
		* WC_Form_Handler::add_to_cart_handler_variable(),
		* WC_Form_Handler::add_to_cart_handler_grouped(),
		* WC_Form_Handler::add_to_cart_handler_simple()
		*
		* Why you gotta be like that WooCommerce?
		*/
	if ( 'simple' !== $add_to_cart_handler ) {
		continue;
	}

	// For now, quantity applies to all products.. This could be changed easily enough, but I didn't need this feature.
	$quantity          = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( $_REQUEST['quantity'] );
	$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

	if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) ) {
		wc_add_to_cart_message( array( $product_id => $quantity ), true );
	}
}
}

// Fire before the WC_Form_Handler::add_to_cart_action callback.
add_action( 'wp_loaded','wc_add_multiple_products_to_cart', 15 );

/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */

 // Add multiple Products to Cart support variations:

 function woocommerce_maybe_add_multiple_products_to_cart() {
	// Make sure WC is installed, and add-to-cart qauery arg exists, and contains at least one comma.
	if ( ! class_exists( 'WC_Form_Handler' ) || empty( $_REQUEST['add-to-cart'] ) || false === strpos( $_REQUEST['add-to-cart'], ',' ) ) {
		return;
	}
  
	remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );
  
	$product_ids = explode( ',', $_REQUEST['add-to-cart'] );
	$count       = count( $product_ids );
	$number      = 0;
  
	foreach ( $product_ids as $product_id ) {
		if ( ++$number === $count ) {
			// Ok, final item, let's send it back to woocommerce's add_to_cart_action method for handling.
			$_REQUEST['add-to-cart'] = $product_id;
  
			return WC_Form_Handler::add_to_cart_action();
		}
  
		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
		$was_added_to_cart = false;
  
		$adding_to_cart    = wc_get_product( $product_id );
  
		if ( ! $adding_to_cart ) {
			continue;
		}
  
		if ( $adding_to_cart->is_type( 'simple' ) ) {
  
			// quantity applies to all products atm
			$quantity          = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( $_REQUEST['quantity'] );
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
  
			if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}
  
		} else {
  
			$variation_id       = empty( $_REQUEST['variation_id'] ) ? '' : absint( wp_unslash( $_REQUEST['variation_id'] ) );
			$quantity           = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_REQUEST['quantity'] ) ); // WPCS: sanitization ok.
			$missing_attributes = array();
			$variations         = array();
			$adding_to_cart     = wc_get_product( $product_id );
  
			if ( ! $adding_to_cart ) {
			  continue;
			}
  
			// If the $product_id was in fact a variation ID, update the variables.
			if ( $adding_to_cart->is_type( 'variation' ) ) {
			  $variation_id   = $product_id;
			  $product_id     = $adding_to_cart->get_parent_id();
			  $adding_to_cart = wc_get_product( $product_id );
  
			  if ( ! $adding_to_cart ) {
				continue;
			  }
			}
  
			// Gather posted attributes.
			$posted_attributes = array();
  
			foreach ( $adding_to_cart->get_attributes() as $attribute ) {
			  if ( ! $attribute['is_variation'] ) {
				continue;
			  }
			  $attribute_key = 'attribute_' . sanitize_title( $attribute['name'] );
  
			  if ( isset( $_REQUEST[ $attribute_key ] ) ) {
				if ( $attribute['is_taxonomy'] ) {
				  // Don't use wc_clean as it destroys sanitized characters.
				  $value = sanitize_title( wp_unslash( $_REQUEST[ $attribute_key ] ) );
				} else {
				  $value = html_entity_decode( wc_clean( wp_unslash( $_REQUEST[ $attribute_key ] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) ); // WPCS: sanitization ok.
				}
  
				$posted_attributes[ $attribute_key ] = $value;
			  }
			}
  
			// If no variation ID is set, attempt to get a variation ID from posted attributes.
			if ( empty( $variation_id ) ) {
			  $data_store   = WC_Data_Store::load( 'product' );
			  $variation_id = $data_store->find_matching_product_variation( $adding_to_cart, $posted_attributes );
			}
  
			// Do we have a variation ID?
			if ( empty( $variation_id ) ) {
			  throw new Exception( __( 'Please choose product options&hellip;', 'woocommerce' ) );
			}
  
			// Check the data we have is valid.
			$variation_data = wc_get_product_variation_attributes( $variation_id );
  
			foreach ( $adding_to_cart->get_attributes() as $attribute ) {
			  if ( ! $attribute['is_variation'] ) {
				continue;
			  }
  
			  // Get valid value from variation data.
			  $attribute_key = 'attribute_' . sanitize_title( $attribute['name'] );
			  $valid_value   = isset( $variation_data[ $attribute_key ] ) ? $variation_data[ $attribute_key ]: '';
  
			  /**
			   * If the attribute value was posted, check if it's valid.
			   *
			   * If no attribute was posted, only error if the variation has an 'any' attribute which requires a value.
			   */
			  if ( isset( $posted_attributes[ $attribute_key ] ) ) {
				$value = $posted_attributes[ $attribute_key ];
  
				// Allow if valid or show error.
				if ( $valid_value === $value ) {
				  $variations[ $attribute_key ] = $value;
				} elseif ( '' === $valid_value && in_array( $value, $attribute->get_slugs() ) ) {
				  // If valid values are empty, this is an 'any' variation so get all possible values.
				  $variations[ $attribute_key ] = $value;
				} else {
				  throw new Exception( sprintf( __( 'Invalid value posted for %s', 'woocommerce' ), wc_attribute_label( $attribute['name'] ) ) );
				}
			  } elseif ( '' === $valid_value ) {
				$missing_attributes[] = wc_attribute_label( $attribute['name'] );
			  }
			}
			if ( ! empty( $missing_attributes ) ) {
			  throw new Exception( sprintf( _n( '%s is a required field', '%s are required fields', count( $missing_attributes ), 'woocommerce' ), wc_format_list_of_items( $missing_attributes ) ) );
			}
  
		  $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
  
		  if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) ) {
			wc_add_to_cart_message( array( $product_id => $quantity ), true );
		  }
		}
	}
  }
  add_action( 'wp_loaded', 'woocommerce_maybe_add_multiple_products_to_cart', 15 );
 /* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */

// Change Currency Symbol
/* A currency is not linked to a language in WooCommerce, they are separate things,
  so you can use English with AED, or any other combination you want.
*/
//if(pll_current_language() == 'ar') {
add_filter('woocommerce_currency_symbol', 'hswp_currency_symbol', 10, 2);  
function hswp_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
        case 'AED': $currency_symbol = 'AED'; break;
		case 'EGP': $currency_symbol = 'ج.م'; break;
     }
     return $currency_symbol;
}
//}

 /* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */
 /* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */
 /* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */
 /* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */










add_action('woocommerce_after_shop_loop_item', 'ijab_read_more_link');
function ijab_read_more_link()
{?>
	<a href="<?php the_permalink();?>">Read More</a>
<?php }
/* =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-=  =-=-=-= */

/** ---------------------------------------------- *
 * woocommerce hooks
 * http://hookr.io/
 * ---------------------------------------------- **/

// define the woocommerce_after_add_to_cart_form callback
function action_woocommerce_after_add_to_cart_form()
{
    // make action magic happen here...
};
// add the action
add_action('woocommerce_after_add_to_cart_form', 'action_woocommerce_after_add_to_cart_form', 10, 0);

// WC Custom Add to Cart labels
//https://wordpress.org/plugins/wc-custom-add-to-cart-labels/
//https://www.webroomtech.com/category/woocommerce/





