//To set firebase analytics events
const analytics = firebase.analytics();
(function ($) {
    'use strict';
    if (typeof window.firebaseAddtocart !== 'undefined') {

        // Initialize FirebaseApp
        if (!firebase.apps.length) {
            firebase.initializeApp(window.firebaseAddtocart);
        }

        //Firebase Analytics to log event for add_to_cart
        analytics.logEvent('add_to_cart', {
            item_id: firebaseAddtocart.product_id,
            quantity: firebaseAddtocart.product_qty,
            item_name: firebaseAddtocart.product_name,
            value: firebaseAddtocart.total,
            price: firebaseAddtocart.product_price,
            currency: firebaseAddtocart.currency,
            item_category: firebaseAddtocart.product_category,
            user_id: firebaseAddtocart.user_id,
            user_email: firebaseAddtocart.user_email

        });
    }
})(jQuery);

