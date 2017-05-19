define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'piraeus',
                component: 'Natso_Piraeus/js/view/payment/method-renderer/piraeus'
            }
        );
        return Component.extend({});
    }
);
