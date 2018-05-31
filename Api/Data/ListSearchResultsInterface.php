<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ListSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get lists
     *
     * @return \Jeysmook\ShopList\Api\Data\ListInterface[]
     */
    public function getItems();

    /**
     * Set lists
     *
     * @param \Jeysmook\ShopList\Api\Data\ListInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
