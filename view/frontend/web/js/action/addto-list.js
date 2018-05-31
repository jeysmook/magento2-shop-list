/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
define([
    'mage/storage',
    'Jeysmook_ShopList/js/model/addto-list-config',
    'Magento_Ui/js/model/messageList',
    'mage/translate'
], function (storage, addtoListConfig, globalMessageList, $t) {
    'use strict';

    return function (payload, messageContainer) {
        var serviceUrl = addtoListConfig.baseApiUrl + 'addto-list';

        messageContainer = messageContainer || globalMessageList;

        return storage.post(
            serviceUrl, JSON.stringify({item: payload})
        ).done(function (responseList) {
            var isCreatedNew = true;
            var lists = addtoListConfig.lists().map(function (list) {
                if (list.id == responseList.id) {
                    if (responseList['products_count'] > 0) {
                        list['products_count'] = Number(responseList['products_count']);
                    } else {
                        list['products_count']++;
                    }
                    isCreatedNew = false;
                }
                return list;
            });

            if (true === isCreatedNew) {
                responseList['products_count'] = 1;
                lists.push(responseList);
            }

            addtoListConfig.lists(lists);

            messageContainer.addSuccessMessage({'message': $t('You have added successfully the product to shopping list.')});
        }).fail(function (xhr) {
            var response = xhr.responseJSON ? xhr.responseJSON : {'message': $t('Shopping list has not been updated. Please try again later.')};
            if (!response.message && response.messages && response.messages.error) {
                response.messages.error.map(function (err) {
                    messageContainer.addErrorMessage({message: err.message});
                });
            } else {
                messageContainer.addErrorMessage(response);
            }
        });
    };
});
