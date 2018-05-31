/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
define([
    'ko',
    'underscore'
], function (ko, _) {
    'use strict';

    return {
        lists: ko.observableArray(window.addtoShopListConfig.customer.lists),
        product: window.addtoShopListConfig.product,
        customer: window.addtoShopListConfig.customer,
        baseApiUrl: window.addtoShopListConfig.baseApiUrl,
        defaultQty: window.addtoShopListConfig.product.min_qty
    };
});
