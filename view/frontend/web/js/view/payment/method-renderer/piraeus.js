define(
    [
        'Magento_Checkout/js/view/payment/default',
        'mage/url'
    ],
    function (Component,urlBuilder) {
        'use strict';
        return Component.extend({

            defaults: {
                template: 'Natso_Piraeus/payment/piraeus',
                redirectAfterPlaceOrder: false
            },

            afterPlaceOrder: function (url) {
                window.location.replace(urlBuilder.build('winbank/payment/redirect/'));
            }
        });
    }
);