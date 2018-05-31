<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Jeysmook\ShopList\Api\Data\ItemInterface;
use Jeysmook\ShopList\Api\Data\ItemInterfaceFactory;
use Jeysmook\ShopList\Api\ItemRepositoryInterface;
use Jeysmook\ShopList\Model\ResourceModel\Item as ItemResource;
use Jeysmook\ShopList\Model\ResourceModel\Item\Collection;
use Jeysmook\ShopList\Model\ResourceModel\Item\CollectionFactory;

class ItemRepository implements ItemRepositoryInterface
{
    /** @var ItemResource */
    private $resource;

    /** @var ListInterfaceFactory */
    private $itemFactory;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var ListInterface[] */
    private $instancesIds = [];

    public function __construct(
        ItemResource $resource,
        ItemInterfaceFactory $itemFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->resource = $resource;
        $this->itemFactory = $itemFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ItemInterface $item)
    {
        // validate entity before save
        $item->validateBeforeSave();

        try {
            $this->resource->save($item);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save the item: %1', $e->getMessage())
            );
        }

        $this->instancesIds[$item->getId()] = $item;

        return $item;
    }
    /**
     * {@inheritdoc}
     */
    public function getById(int $id)
    {
        if (isset($this->instancesIds[$id])) {
            return $this->instancesIds[$id];
        }

        $item = $this->create()->load($id);

        if (!$item->getId()) {
            throw new NoSuchEntityException(__('Item with id "%1" does not exist.', $id));
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ItemInterface $item)
    {
        $id = $item->getId();

        try {
            $this->resource->delete($item);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('Could not delete the item: %1', $e->getMessage())
            );
        }

        unset($this->instancesIds[$id]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(int $id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return $this->itemFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function createCollection()
    {
        return $this->collectionFactory->create();
    }
}
