<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Api;

interface ListManagementInterface
{
    /**
     * Add item to shopping list
     *
     * @param \Jeysmook\ShopList\Api\Data\ItemInterface $item
     * @param int $customerId
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     * @throws \Magento\Framework\Validator\Exception
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addToList(\Jeysmook\ShopList\Api\Data\ItemInterface $item, int $customerId);
}
