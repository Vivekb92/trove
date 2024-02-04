//To set firebase analytics events
const analytics = firebase.analytics();
(function ($) {
    'use strict';
    if (typeof window.firebaseEcompurchase !== 'undefined') {

        // Initialize FirebaseApp
        if (!firebase.apps.length) {
            firebase.initializeApp(window.firebaseEcompurchase);
        }

        //Firebase Analytics to log event for add_to_cart
        analytics.logEvent('ecommerce_purchase', {
            coupon: firebaseEcompurchase.order_coupon,
            transaction_id: firebaseEcompurchase.order_transaction_id,
            value: firebaseEcompurchase.order_value,
            shipping: firebaseEcompurchase.order_shipping,
            tax: firebaseEcompurchase.order_tax,
            currency: firebaseEcompurchase.order_currency,
            number_of_items: firebaseEcompurchase.order_total,
            payment_method: firebaseEcompurchase.payment_method,
            user_id: firebaseEcompurchase.user_id,
            user_email: firebaseEcompurchase.user_email

        });

    }
})(jQuery);

