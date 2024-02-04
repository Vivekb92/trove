<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;}
// Send Product data to Firebase analytics for add_to_cart log event
function action_woocommerce_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	// make action magic happen here...
	wp_enqueue_script( 'wc-analytic-add-to-cart', plugins_url( 'assets/js/wc_analytics_add_to_cart.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	$user_id    = '';
	$user_email = '';
	if ( is_user_logged_in() ) {
		$user       = wp_get_current_user();
		$user_id    = $user->ID;
		$user_email = $user->user_email;
	}
	$product          = wc_get_product( $product_id );
	$product_name     = $product->get_title();
	$product_category = get_the_terms( $product_id, 'product_cat' );
	foreach ( WC()->cart->get_cart() as $cart_item ) {
		if ( $cart_item['product_id'] == $id ) {
			$qty = $cart_item['quantity'];
			break; // stop the loop if product is found
		}
	}
	$product_price  = $product->get_price();
	$wc_currency    = get_woocommerce_currency();
	$total_quantity = WC()->cart->get_cart_contents_count();

	wp_localize_script(
		'wc-analytic-add-to-cart',
		'firebaseAddtocart',
		array(
			'product_id'       => $product_id,
			'product_name'     => $product_name,
			'product_category' => $product_category[0]->name,
			'product_qty'      => $quantity,
			'product_price'    => $product_price,
			'currency'         => $wc_currency,
			'total'            => $total_quantity,
			'user_id'          => $user_id,
			'user_email'       => $user_email,
		)
	);
};
add_action( 'woocommerce_add_to_cart', 'action_woocommerce_add_to_cart', 10, 6 );

// Send Product data to Firebase analytics for view_product log event
function action_woocommerce_view_product() {
	$user_id    = '';
	$user_email = '';
	if ( is_user_logged_in() ) {
		$user       = wp_get_current_user();
		$user_id    = $user->ID;
		$user_email = $user->user_email;
	}
	if ( is_product() ) {
		global $post;
		$single_pro_id = $post->ID;
		$product       = wc_get_product( $single_pro_id );
		$product_title = $product->get_title();
		$product_price = $product->get_price();
		$wc_currency   = get_woocommerce_currency();
		wp_enqueue_script( 'wc-analytic-view-product', plugins_url( 'assets/js/wc_analytics_view_product.js', __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_localize_script(
			'wc-analytic-view-product',
			'firebaseViewproduct',
			array(

				'product_id'    => $single_pro_id,
				'product_title' => $product_title,
				'product_price' => $wc_currency . $product_price,
				'user_id'       => $user_id,
				'user_email'    => $user_email,
			)
		);
	}
}
add_action( 'woocommerce_before_single_product_summary', 'action_woocommerce_view_product', 10 );

// Send Product data to Firebase analytics for view_product_list log event
function action_woocommerce_view_product_list() {
	$category_id   = '';
	$category_name = '';
	$user_id       = '';
	$user_email    = '';
	if ( is_user_logged_in() ) {
		$user       = wp_get_current_user();
		$user_id    = $user->ID;
		$user_email = $user->user_email;
	}
	if ( is_product_category() ) {

		$cate          = get_queried_object();
		$category_id   = $cate->term_id;
		$category_name = $cate->name;
	}
	wp_enqueue_script( 'wc-analytic-view-product-list', plugins_url( 'assets/js/wc_analytics_view_product_list.js', __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_localize_script(
			'wc-analytic-view-product-list',
			'firebaseViewproductlist',
			array(

				'product_cat_id'   => $category_id,
				'product_cat_name' => $category_name,
				'user_id'          => $user_id,
				'user_email'       => $user_email,
			)
		);
}
add_action( 'woocommerce_before_shop_loop', 'action_woocommerce_view_product_list', 10 );

// Send Product data to Firebase analytics for begin_checkout log event
function action_woocommerce_begin_checkout() {
	$user_id    = '';
	$user_email = '';
	if ( is_user_logged_in() ) {
		$user       = wp_get_current_user();
		$user_id    = $user->ID;
		$user_email = $user->user_email;
	}
	$coupon_raw     = WC()->cart->get_applied_coupons();
	$coupon         = end( $coupon_raw );
	$apply_coupon   = $coupon;
	$cart_count     = WC()->cart->get_cart_contents_count();
	$cart_currency  = get_woocommerce_currency();
	$cart_total     = WC()->cart->cart_contents_total;
	$payment_method = '';

	// print_r($apply_coupon);
	wp_enqueue_script( 'wc-analytic-begin-checkout', plugins_url( 'assets/js/wc_analytics_begin_checkout.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_localize_script(
		'wc-analytic-begin-checkout',
		'firebaseBegincheckout',
		array(

			'check_coupon'   => $apply_coupon,
			'check_currency' => $cart_currency,
			'check_total'    => $cart_count,
			'check_value'    => $cart_total,
			'payment_method' => $payment_method,
			'user_id'        => $user_id,
			'user_email'     => $user_email,
		)
	);
}

add_action( 'woocommerce_review_order_before_submit', 'action_woocommerce_begin_checkout', 10 );

// Send Product data to Firebase analytics for ecommerce_purchase log event
function action_woocommerce_ecommerce_purchase( $order_id ) {
	$user_id    = '';
	$user_email = '';
	if ( is_user_logged_in() ) {
		$user       = wp_get_current_user();
		$user_id    = $user->ID;
		$user_email = $user->user_email;
	}
	$order = wc_get_order( $order_id );

	$coupon_raw           = $order->get_used_coupons();
	$order_coupon         = end( $coupon_raw );
	$order_currency       = $order->get_currency();
	$order_total          = $order->get_item_count();
	$order_value          = $order->get_total();
	$order_tax            = $order->get_total_tax();
	$order_shipping       = $order->get_shipping_total();
	$order_payment_method = $order->get_payment_method();
	$order_transaction_id = $order->get_transaction_id();
	// $order_transaction_id = get_post_meta($order_id, '_transaction_id', true);

		wp_enqueue_script( 'wc-analytic-ecom-purchase', plugins_url( 'assets/js/wc_analytics_ecommerce_purchase.js', __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_localize_script(
			'wc-analytic-ecom-purchase',
			'firebaseEcompurchase',
			array(

				'order_coupon'         => $order_coupon,
				'order_currency'       => $order_currency,
				'order_value'          => $order_value,
				'order_total'          => $order_total,
				'order_tax'            => $order_tax,
				'order_payment_method' => $order_payment_method,
				'order_shipping'       => $order_shipping,
				'order_transaction_id' => $order_transaction_id,
				'user_id'              => $user_id,
				'user_email'           => $user_email,
			)
		);

}

add_action( 'woocommerce_payment_complete', 'action_woocommerce_ecommerce_purchase' );
// add_action( 'woocommerce_thankyou', 'action_woocommerce_ecommerce_purchase' );

// Send Product data to Firebase analytics for purchase_refund log event
function action_woocommerce_purchase_refund( $order_id, $refund_id ) {
	$order         = wc_get_order( $order_id );
	$order_refunds = $order->get_refunds();
	foreach ( $order_refunds as $refund ) {
		foreach ( $refund->get_items() as $item_id => $item ) {
			$refunded_quantity = $item->get_quantity(); // Quantity: zero or negative integer
		}
	}
	$refund_currency       = $order->get_currency();
	$refund_value          = $order->get_item_count();
	$refund_transaction_id = $order->get_transaction_id();
	// $refund_transaction_id = get_post_meta($order_id, '_transaction_id', true);
	wp_enqueue_script( 'wc-analytic-purchase-refund', plugins_url( 'assets/js/wc_analytics_purchase_refund.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_localize_script(
		'wc-analytic-purchase-refund',
		'firebasePurchaserefund',
		array(

			'refund_quantity'       => $refunded_quantity,
			'refund_currency'       => $refund_currency,
			'refund_value'          => $refund_value,
			'refund_transaction_id' => $refund_transaction_id,
		)
	);

}

add_action( 'woocommerce_order_refunded', 'action_woocommerce_purchase_refund', 10 );


// Send Product data to Firebase analytics for view_search_results log event
function action_woocommerce_view_search_results( $search_term ) {
	if ( is_search() ) {
		wp_enqueue_script( 'wc-analytic-view-search-results', plugins_url( 'assets/js/wc_analytics_view_search_results.js', __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_localize_script(
			'wc-analytic-view-search-results',
			'firebaseSearchresult',
			array(

				'search_result' => $search_term,
			)
		);
	}
}

add_action( 'get_search_query', 'action_woocommerce_view_search_results', 10 );
