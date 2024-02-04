//To set firebase analytics events
const analytics = firebase.analytics();
(function ($) {
    'use strict';
    if (typeof window.firebaseSearchresult !== 'undefined') {

        // Initialize FirebaseApp
        if (!firebase.apps.length) {
            firebase.initializeApp(window.firebaseSearchresult);
        }

        //Firebase Analytics to log event for add_to_cart
        analytics.logEvent('view_search_results', {
            search_term: firebaseSearchresult.search_result,
            //item_location_id: firebaseAddtocart.product_id

        });

    }
})(jQuery);

