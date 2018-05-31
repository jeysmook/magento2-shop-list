<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\ResourceModel\Item;

use Magento\Customer\Model\Customer;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = 'item_id';

    /** @var string */
    protected $_eventPrefix = 'jeysmook_list_item_collection';

    /** @var string */
    protected $_eventObject = 'item_collection';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Jeysmook\ShopList\Model\Item::class, \Jeysmook\ShopList\Model\ResourceModel\Item::class);
    }

    /**
     * Add filter by customer identify
     *
     * @param int|Customer $identify
     * @return $this
     */
    public function addCustomerFilter($identify)
    {
        if ($identify instanceof Customer) {
            $identify = $identify->getId();
        }

        $this->getSelect()->join(
            ['lt' => $this->getTable('jeysmook_shoplist_list')],
            'main_table.list_id = lt.list_id AND lt.customer_id = ' . (int)$identify,
            []
        );

        return $this;
    }
}
