<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Api;

interface CartServiceInterface
{
    /**
     * Add item to cart
     *
     * @param \Jeysmook\ShopList\Api\Data\ItemInterface $item
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addItemToCart(\Jeysmook\ShopList\Api\Data\ItemInterface $item);

    /**
     * Add items to cart
     *
     * @param \Jeysmook\ShopList\Api\Data\ItemInterface[] $items
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addItemsToCart(array $items);
}
