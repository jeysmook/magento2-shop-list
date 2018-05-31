<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Listing;

use Jeysmook\ShopList\Api\Data\ListInterface;
use Jeysmook\ShopList\Block\AbstractListingBlock;
use Jeysmook\ShopList\Model\ResourceModel\SList\Collection;

class SList extends AbstractListingBlock
{
    /**
     * Retrieve customer shop lists
     *
     * @return Collection
     */
    public function getLists()
    {
        $collection = $this->listRepository->createCollection();
        $collection->addStoreFilter($this->getCustomer()->getStoreId())
            ->addCustomerFilter($this->getCustomer()->getId());

        return $collection->load();
    }

    /**
     * Retreive save URL
     *
     * @param ListInterface|null $list
     * @return string
     */
    public function getSaveUrl(ListInterface $list = null)
    {
        $params = [];
        if ($list !== null) {
            $params['id'] = $list->getId();
        }

        return $this->getUrl('*/*/save', $params);
    }

    /**
     * Retrieve duplicate URL
     *
     * @param ListInterface $list
     * @return string
     */
    public function getDuplicateUrl(ListInterface $list)
    {
        return $this->getUrl('*/*/duplicate', ['id' => $list->getId()]);
    }

    /**
     * Retrieve delete URL
     *
     * @param ListInterface $list
     * @return string
     */
    public function getDeleteUrl(ListInterface $list)
    {
        return $this->getUrl('*/*/delete', ['id' => $list->getId()]);
    }

    /**
     * Retrieve view URL
     *
     * @param ListInterface $list
     * @return string
     */
    public function getViewUrl(ListInterface $list)
    {
        return $this->getUrl('*/*/view', ['id' => $list->getId()]);
    }

    /**
     * Retrieve add to cart URL
     *
     * @param ListInterface $list
     * @return string
     */
    public function getAddToCartUrl(ListInterface $list)
    {
        return $this->getUrl('*/*/addToCart', ['id' => $list->getId()]);
    }

    /**
     * Retrieve mass add to cart URL
     *
     * @return string
     */
    public function getMassAddToCartUrl()
    {
        return $this->getUrl('*/*/massAddToCart');
    }

    /**
     * Retrieve mass delete URL
     *
     * @return string
     */
    public function getMassDeleteUrl()
    {
        return $this->getUrl('*/*/massDelete');
    }

    /**
     * Retrieve back URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }
}
