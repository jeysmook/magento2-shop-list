<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Api;

interface ListRepositoryInterface
{
    /**
     * Retrieve shop list by identify
     *
     * @param int $id
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $id);

    /**
     * Save shop list
     *
     * @param \Jeysmook\ShopList\Api\Data\ListInterface $list
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     * @throws \Magento\Framework\Validator\Exception
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Jeysmook\ShopList\Api\Data\ListInterface $list);

    /**
     * Delete shop list by id
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $id);

    /**
     * Delete shop list
     *
     * @param \Jeysmook\ShopList\Api\Data\ListInterface $list
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Jeysmook\ShopList\Api\Data\ListInterface $list);

    /**
     * Duplicate shop list
     *
     * @param \Jeysmook\ShopList\Api\Data\ListInterface $list
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     * @throws \Magento\Framework\Validator\Exception
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function duplicate(\Jeysmook\ShopList\Api\Data\ListInterface $list);

    /**
     * Retrieve shop lists matching the specified criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Jeysmook\ShopList\Api\Data\ListSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Create empty entity
     *
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     */
    public function create();

    /**
     * Create collection
     *
     * @return \Jeysmook\ShopList\Model\ResourceModel\SList\Collection
     */
    public function createCollection();
}
