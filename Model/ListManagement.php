<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Jeysmook\ShopList\Api\Data\ItemInterface;
use Jeysmook\ShopList\Api\ItemRepositoryInterface;
use Jeysmook\ShopList\Api\ListManagementInterface;
use Jeysmook\ShopList\Api\ListRepositoryInterface;

class ListManagement implements ListManagementInterface
{
    /** @var ListRepositoryInterface */
    private $listRepository;

    /** @var ItemRepositoryInterface */
    private $itemRepository;

    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    public function __construct(
        ListRepositoryInterface $listRepository,
        ItemRepositoryInterface $itemRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->listRepository = $listRepository;
        $this->itemRepository = $itemRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function addToList(ItemInterface $item, int $customerId)
    {
        // add to exists shop list
        if ($item->getListId() > 0) {
            $this->itemRepository->save($item);
        } else if ((int)$item->getListId() === -1) {
            $customer = $this->customerRepository->getById($customerId);
            $listData = [
                'title' => $item->getAdditional()->getNewShopTitle(),
                'customer_id' => (int)$customer->getId(),
                'store_id' => (int)$customer->getStoreId()
            ];

            $list = $this->listRepository->create();
            $list->addData($listData);
            $this->listRepository->save($list);

            $item->setListId($list->getId());
            $this->itemRepository->save($item);

            return $this->listRepository->save($list);
        } else {
            throw new LocalizedException(__('Please selected shopping list.'));
        }

        return $this->listRepository->getById($item->getListId());
    }
}
