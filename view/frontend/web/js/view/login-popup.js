/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/form',
    'Magento_Customer/js/action/login',
    'Jeysmook_ShopList/js/model/login-popup',
    'mage/translate'
], function ($, ko, Component, loginAction, loginPopup, $t) {
    'use strict';

    return Component.extend({
        modalWindow: null,
        isLoading: ko.observable(false),

        defaults: {
            template: 'Jeysmook_ShopList/login-popup',
            autocomplete: 'off'
        },

        /**
         * Init popup login window
         */
        setModalElement: function (element) {
            if (loginPopup.modalWindow == null) {
                loginPopup.createPopUp(element);
            }
        },

        /**
         * Show login popup window
         */
        showModal: function () {
            if (this.modalWindow) {
                $(this.modalWindow).modal('openModal');
            }
        },

        /**
         * Provide login action
         *
         * @return {Boolean}
         */
        login: function (formUiElement, event) {
            var _this = this,
                loginData = {},
                formElement = $(event.currentTarget),
                formDataArray = formElement.serializeArray();

            event.stopPropagation();
            formDataArray.forEach(function (entry) {
                loginData[entry.name] = entry.value;
            });

            if (formElement.validation() && formElement.validation('isValid')) {

                this.isLoading(true);
                loginAction(loginData)
                    .always(function () {
                        _this.isLoading(false);
                    });
            }

            return false;
        }
    });
});
