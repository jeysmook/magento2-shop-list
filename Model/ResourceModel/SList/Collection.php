<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\ResourceModel\SList;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Api\Data\StoreInterface;

class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = 'list_id';

    /** @var string */
    protected $_eventPrefix = 'jeysmook_list_collection';

    /** @var string */
    protected $_eventObject = 'list_collection';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Jeysmook\ShopList\Model\SList::class, \Jeysmook\ShopList\Model\ResourceModel\SList::class);
    }

    /**
     * Add filter by customer
     *
     * @param $identify
     * @return $this
     */
    public function addCustomerFilter($identify)
    {
        if ($identify instanceof CustomerInterface) {
            $identify = $identify->getId();
        }

        $this->addFieldToFilter('customer_id', ['eq' => (int)$identify]);

        return $this;
    }

    /**
     * Add filter by store
     *
     * @param $identify
     * @return $this
     */
    public function addStoreFilter($identify)
    {
        if ($identify instanceof StoreInterface) {
            $identify = $identify->getId();
        }

        $this->addFieldToFilter('store_id', ['eq' => (int)$identify]);

        return $this;
    }
}
