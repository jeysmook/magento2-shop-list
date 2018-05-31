/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Ui/js/modal/alert',
    'Jeysmook_ShopList/js/model/login-popup',
    'Jeysmook_ShopList/js/model/addto-list-popup',
    'mage/translate'
], function ($, ko, Component, alert, loginPopup, addtoListPopup) {
    'use strict';

    var isVisibleAddToCartButton = $('#product-addtocart-button').length > 0;

    return Component.extend({
        /** add to cart form selector */
        addToCartFormSelector: '#product_addtocart_form',

        isVisible: ko.observable(isVisibleAddToCartButton),

        defaults: {
            template: 'Jeysmook_ShopList/catalog/product/addto-list',
        },

        /**
         * {@inheritDoc}
         */
        initialize: function () {
            this._super();
            return this;
        },

        /**
         * Validate add to cart product form
         *
         * @returns {Boolean}
         */
        validateAddToCartForm: function () {
            var $form = $(this.addToCartFormSelector);

            return $form.validation() && $form.validation('isValid');
        },

        /**
         * Add to shoppling list handle
         *
         * @returns {void}
         */
        addToShopListHandle: function () {
            if (false === window.isCustomerLoggedIn) {
                loginPopup.showModal();
            } else if(this.validateAddToCartForm()) {
                addtoListPopup.showModal();
            } else {
                alert({
                    title: $.mage.__('* Required Fields'),
                    content: $.mage.__('Please fill in the required fields.'),
                    modalClass: 'edit-item-popup popup-addto-shop-list'
                });
            }
        },
    });
});
