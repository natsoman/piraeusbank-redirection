define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'mage/url'
    ],
    function ($,Component,urlBuilder) {
        'use strict';
        console.log(window.checkoutConfig.payment.piraeus.installments);
        return Component.extend({
            defaults: {
                template: 'Natso_Piraeus/payment/piraeus',
                redirectAfterPlaceOrder: false
            },
            afterPlaceOrder: function (url) {
                window.location.replace(urlBuilder.build('winbank/payment/redirect/'));
            },
            availableInstallments: function(){
                return window.checkoutConfig.payment.piraeus.installments;
            },
            hasInstallments: function(){
                if (window.checkoutConfig.payment.piraeus.installments.length == 0) {
                    return false;
                } else {
                    return true;
                }
            },
            getData: function() {
                if (window.checkoutConfig.payment.piraeus.installments.length == 0) {
                    return {
                        'method': this.item.method,
                        'additional_data': {}
                    };
                } else {
                    return {
                        'method': this.item.method,
                        'additional_data': {
                            'installments': $('select[name="piraeus-installments"]').val()
                        }
                    };
                }
            }
        });
    }
);