/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
define([
    'jquery',
    'ko',
    'underscore',
    'Magento_Ui/js/form/form',
    'Jeysmook_ShopList/js/model/form-data-builder',
    'Jeysmook_ShopList/js/model/addto-list-config',
    'Jeysmook_ShopList/js/model/addto-list-popup',
    'Jeysmook_ShopList/js/action/addto-list',
    'mage/translate'
], function ($, ko, _, Component, formDataBuilder, addtoListConfig, addtoListPopup, addtoListAction, $t) {
    'use strict';

    return Component.extend({
        /** Modal window element */
        modalWindow: null,

        /** is loading flag */
        isLoading: ko.observable(false),

        /** is show add new list flag */
        isShowAddNewList: ko.observable(false),

        /** add to list form selector */
        addToListFormSelector: '#addto-shoplist-form',

        /** add to cart form selector */
        addToCartFormSelector: '#product_addtocart_form',

        defaults: {
            template: 'Jeysmook_ShopList/addto-list-popup'
        },

        /**
         * {@inheritDoc}
         */
        initialize: function () {
            this._super();
            this.isShowAddNewList.subscribe((function () { this.rebuildScrollbar(); }).bind(this));
            return this;
        },

        /**
         * Retrieve all customer lists
         *
         * @return {Array}
         */
        getLists: function () {
            return addtoListConfig.lists;
        },

        /**
         * Retrieve lists count
         *
         * @return {Number}
         */
        getListsLength: function () {
            return addtoListConfig.lists().length;
        },

        /**
         * Retrieve product data
         *
         * @return {Object}
         */
        getProduct: function () {
            return addtoListConfig.product;
        },

        /**
         * Retreive default qty
         */
        getDefaultQty: function() {
            return addtoListConfig.defaultQty;
        },

        /**
         * Retrieve products count for list
         *
         * @param list
         * @return {Number}
         */
        getProductsCount: function (list) {
            var count = 0;
            addtoListConfig.lists.map(function(item) {
                if (item.id == list.id) {
                    count = Number(item['products_count']);
                    return;
                }
            });
            return count;
        },

        /**
         * Rebuild scroll bar
         */
        rebuildScrollbar: function (seconds) {
            seconds = seconds || 0;
            if (window.jcf !== void 0) {
                var t = setTimeout(function () {
                    window.jcf.replaceAll();
                    clearTimeout(t);
                }, seconds);
            }
        },

        /**
         * Toggle is show add new list flag
         *
         * @return {exports}
         */
        toggleIsShowAddNewList: function () {
            this.isShowAddNewList(!this.isShowAddNewList());

            return this;
        },

        /**
         * Save to shop list handle
         */
        saveHandle: function () {
            var _this = this,
                $form = $(this.addToListFormSelector),
                $addToCartForm = $(this.addToCartFormSelector),
                payload;

            this.rebuildScrollbar();

            if ($form.validation()
                && $form.validation('isValid')) {
                payload = this.buildPayload($form, $addToCartForm);

                this.isLoading(true);
                addtoListAction(payload)
                    .done(function () {
                        _this.isShowAddNewList(false);
                    })
                    .always(function () {
                        _this.isLoading(false);
                    });
            }
        },

        /**
         * Build payload object
         *
         * @param $form
         * @param $addToCartForm
         * @return {Object}
         */
        buildPayload: function ($form, $addToCartForm) {
            var payload = formDataBuilder($form[0]);
            payload['by_request'] = formDataBuilder($addToCartForm[0]);
            delete payload['by_request']['form_key'];
            payload['by_request'] = JSON.stringify(payload['by_request']);
            return payload;
        },

        /**
         * Init popup login window
         */
        setModalElement: function (element) {
            if (addtoListPopup.modalWindow == null) {
                addtoListPopup.createPopUp(element);
            }

            this.rebuildScrollbar(1000);
        },

        /**
         * Close handle
         */
        closeHandle: function () {
            addtoListPopup.hideModal();
        },

        /**
         * Retrieve validate rules for qty input
         *
         * @return {String}
         */
        getRulesForQty: function () {
            var product = this.getProduct(),
                rules = {
                    required: true,
                    'required-number': true,
                    'validate-item-quantity':{
                        minAllowed: product['min_qty'],
                        maxAllowed: product['max_qty'],
                        qtyIncrements: product['qty_increments']
                    }
                };

            return JSON.stringify(rules);
        },
    });
});
