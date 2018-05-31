<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Api;

interface ItemRepositoryInterface
{
    /**
     * Get item by id
     *
     * @param int $id
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $id);

    /**
     * Save item
     *
     * @param \Jeysmook\ShopList\Api\Data\ItemInterface $item
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface
     * @throws \Magento\Framework\Validator\Exception
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Jeysmook\ShopList\Api\Data\ItemInterface $item);

    /**
     * Delete item by id
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $id);

    /**
     * Delete item
     *
     * @param \Jeysmook\ShopList\Api\Data\ItemInterface $item
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Jeysmook\ShopList\Api\Data\ItemInterface $item);

    /**
     * Create empty entity
     *
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface
     */
    public function create();

    /**
     * Create collection
     *
     * @return \Jeysmook\ShopList\Model\ResourceModel\Item\Collection
     */
    public function createCollection();
}
