//To set firebase analytics events
const analytics = firebase.analytics();
(function ($) {
    'use strict';
    if (typeof window.firebaseViewproduct !== 'undefined') {

        // Initialize FirebaseApp
        if (!firebase.apps.length) {
            firebase.initializeApp(window.firebaseViewproduct);
        }
        //Firebase Analytics to log event for add_to_cart
        analytics.logEvent('view_item', {
            item_id: firebaseViewproduct.product_id,
            item_name: firebaseViewproduct.product_title,
            price: firebaseViewproduct.product_price,
            user_id: firebaseViewproduct.user_id,
            user_email: firebaseViewproduct.user_email

        });

    }
})(jQuery);

