//To set firebase analytics events
const analytics = firebase.analytics();
(function ($) {
    'use strict';
    if (typeof window.firebaseViewproductlist !== 'undefined') {

        // Initialize FirebaseApp
        if (!firebase.apps.length) {
            firebase.initializeApp(window.firebaseViewproductlist);
        }

        //Firebase Analytics to log event for add_to_cart
        analytics.logEvent('view_item_list', {
            item_id: firebaseViewproductlist.product_cat_id,
            item_title: firebaseViewproductlist.product_cat_name,
            user_id: firebaseViewproductlist.user_id,
            user_email: firebaseViewproductlist.user_email
        });

    }
})(jQuery);

