<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Api\Data;

interface ListInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const ID = 'list_id';
    const CUSTOMER_ID = 'customer_id';
    const STORE_ID = 'store_id';
    const TITLE = 'title';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DESCRIPTION = 'description';
    const ITEMS = 'items';
    /**#@-*/

    /**
     * Id setter
     *
     * @param int $id
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     */
    public function setId($id);

    /**
     * Id getter
     *
     * @return int|null
     */
    public function getId();

    /**
     * Customer id setter
     *
     * @param int $customerId
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     */
    public function setCustomerId(int $customerId);

    /**
     * Customer id getter
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Store id setter
     *
     * @param int $storeId
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     */
    public function setStoreId(int $storeId);

    /**
     * Store id getter
     *
     * @return int|null
     */
    public function getStoreId();

    /**
     * Created at setter
     *
     * @param string $createdAt
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     */
    public function setCreatedAt(string $createdAt);

    /**
     * Created at getter
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Updated at setter
     *
     * @param string $updatedAt
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     */
    public function setUpdatedAt(string $updatedAt);

    /**
     * Updated at getter
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Title setter
     *
     * @param string $title
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     */
    public function setTitle(string $title);

    /**
     * Title getter
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Description setter
     *
     * @param string $description
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     */
    public function setDescription(string $description);

    /**
     * Description getter
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set list items
     *
     * @param $items \Jeysmook\ShopList\Api\Data\ItemInterface[]
     * @return \Jeysmook\ShopList\Api\Data\ListInterface
     */
    public function setItems(array $items);

    /**
     * Retrieve list items
     *
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface[]
     */
    public function getItems();

    /**
     * Retrieve list items count
     *
     * @return int
     */
    public function getProductsCount();
}
