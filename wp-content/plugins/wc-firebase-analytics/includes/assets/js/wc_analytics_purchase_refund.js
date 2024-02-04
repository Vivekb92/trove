//To set firebase analytics events
const analytics = firebase.analytics();
(function ($) {
    'use strict';
    if (typeof window.firebasePurchaserefund !== 'undefined') {

        // Initialize FirebaseApp
        if (!firebase.apps.length) {
            firebase.initializeApp(window.firebasePurchaserefund);
        }

        //Firebase Analytics to log event for add_to_cart
        analytics.logEvent('purchase_refund', {
            quantity: firebaseEcompurchase.refund_quantity,
            value: firebaseEcompurchase.refund_currency,
            currency: firebaseEcompurchase.refund_value,
            transaction_id: firebaseEcompurchase.refund_transaction_id,

        });

    }
})(jQuery);

