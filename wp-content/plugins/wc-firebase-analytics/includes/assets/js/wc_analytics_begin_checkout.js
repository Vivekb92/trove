//To set firebase analytics events
const analytics = firebase.analytics();
(function ($) {
    'use strict';
    if (typeof window.firebaseBegincheckout !== 'undefined') {

        // Initialize FirebaseApp
        if (!firebase.apps.length) {
            firebase.initializeApp(window.firebaseBegincheckout);
        }

        //Firebase Analytics to log event for add_to_cart
        analytics.logEvent('begin_checkout', {
            coupon: firebaseBegincheckout.check_coupon,
            currency: firebaseBegincheckout.check_currency,
            value: firebaseBegincheckout.check_value,
            number_of_items: firebaseBegincheckout.check_total,
            user_id: firebaseBegincheckout.user_id,
            user_email: firebaseBegincheckout.user_email

        });

        analytics.logEvent('add_payment_info', {
            value: firebaseBegincheckout.check_value,
            number_of_items: firebaseBegincheckout.check_total,
            payment_method: firebaseBegincheckout.payment_method,
            user_id: firebaseBegincheckout.user_id,
            user_email: firebaseBegincheckout.user_email
        });

    }
})(jQuery);

