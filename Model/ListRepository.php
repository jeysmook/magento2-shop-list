<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Jeysmook\ShopList\Api\Data\ListInterface;
use Jeysmook\ShopList\Api\Data\ListInterfaceFactory;
use Jeysmook\ShopList\Api\Data\ListSearchResultsInterface;
use Jeysmook\ShopList\Api\Data\ListSearchResultsInterfaceFactory;
use Jeysmook\ShopList\Api\ListRepositoryInterface;
use Jeysmook\ShopList\Model\ResourceModel\SList as ListResource;
use Jeysmook\ShopList\Model\ResourceModel\SList\Collection;
use Jeysmook\ShopList\Model\ResourceModel\SList\CollectionFactory;

class ListRepository implements ListRepositoryInterface
{
    /** @var ListResource */
    private $resource;

    /** @var ListInterfaceFactory */
    private $listFactory;

    /** @var ListInterface[] */
    private $instancesIds = [];

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var SearchResultsInterface */
    private $searchResultsFactory;

    public function __construct(
        ListResource $resource,
        ListInterfaceFactory $listFactory,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory,
        ListSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->resource = $resource;
        $this->listFactory = $listFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ListInterface $list)
    {
        // validate entity before save
        $list->validateBeforeSave();

        try {
            $this->resource->save($list);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save the shipping list: %1', $e->getMessage())
            );
        }

        $this->instancesIds[$list->getId()] = $list;

        return $list;
    }
    /**
     * {@inheritdoc}
     */
    public function getById(int $id)
    {
        if (isset($this->instancesIds[$id])) {
            return $this->instancesIds[$id];
        }

        /** @var ListInterface $list */
        $list = $this->create()->load($id);

        if (!$list->getId()) {
            throw new NoSuchEntityException(__('Shopping list with id "%1" does not exist.', $id));
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ListInterface $list)
    {
        $id = $list->getId();

        try {
            $this->resource->delete($list);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('Could not delete the shipping list: %1', $e->getMessage())
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
    public function duplicate(ListInterface $list)
    {
        $duplicateEntity = $this->create();
        $duplicateEntity->setIsDuplicate(true);

        $fieldsMap = ['title', 'description', 'customer_id', 'store_id'];
        foreach ($fieldsMap as $key) {
            $duplicateEntity->setData($key, $list->getData($key));
        }

        // duplicate items
        $items = [];
        foreach ($list->getItems() as $item) {
            $item = clone $item;
            $item->setIsDuplicate(true);
            $items[] = $item;
        }
        $duplicateEntity->setItems($items);

        $this->save($duplicateEntity);

        return $duplicateEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var Collection $collection */
        $collection = $this->createCollection();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var ListSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return $this->listFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function createCollection()
    {
        return $this->collectionFactory->create();
    }
}
