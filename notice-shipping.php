<?php

/**
 * Notice Shipping
 */
add_action( 'woocommerce_after_order_notes', 'woohs_shipping_notice' );
add_action( 'woocommerce_before_checkout_form', 'woohs_shipping_notice' );
 
function woohs_shipping_notice() {
echo '<p class="allow shipping-notice" style="color:red">التوصيل المجاني من 5 إلى 7 أيام عمل للتسليم بعد معالجة الطلب.</p>';
echo '<p class="allow shipping-notice" style="color:red">Free delivery 5-7 working days for delivery after order processing.</p>';
}
