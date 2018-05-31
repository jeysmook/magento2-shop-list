/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
define([
    'jquery',
    'underscore',
    'Magento_Ui/js/modal/modal',
    'mage/translate'
], function ($, _, modal, $t) {
    'use strict';

    return {
        modalWindow: null,

        /**
         * Create popUp window for provided element
         *
         * @param {HTMLElement} element
         */
        createPopUp: function (element, options) {
            var defaultOptions = {
                'title': $t('Add to shopping list'),
                'type': 'popup',
                'modalClass': 'edit-item-popup popup-addto-shop-list',
                'responsive': true,
                'innerScroll': true,
                'buttons': []
            };

            if (!_.isObject(options)) {
                options = {};
            }

            this.modalWindow = element;
            modal(Object.assign(defaultOptions, options), $(this.modalWindow));
        },

        /**
         * Show login popup window
         */
        showModal: function () {
            $(this.modalWindow).modal('openModal');
        },

        /**
         * Show login popup window
         */
        hideModal: function () {
            $(this.modalWindow).modal('closeModal');
        }
    };
});
