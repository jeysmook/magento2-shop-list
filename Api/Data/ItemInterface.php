<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Api\Data;

interface ItemInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const ID = 'item_id';
    const LIST_ID = 'list_id';
    const PRODUCT_ID = 'product_id';
    const QTY = 'qty';
    const ADDED_AT = 'added_at';
    const BY_REQUEST = 'by_request';
    const ADDITIONAL = 'additional_data';
    /**#@-*/

    /**
     * Id setter
     *
     * @param int $id
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface
     */
    public function setId($id);

    /**
     * Id getter
     *
     * @return int|null
     */
    public function getId();

    /**
     * List id setter
     *
     * @param int $listId
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface
     */
    public function setListId(int $listId);

    /**
     * List id getter
     *
     * @return int|null
     */
    public function getListId();

    /**
     * Product id setter
     *
     * @param int $productId
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface
     */
    public function setProductId(int $productId);

    /**
     * Product id getter
     *
     * @return int|null
     */
    public function getProductId();

    /**
     * Qty setter
     *
     * @param int $qty
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface
     */
    public function setQty(int $qty);

    /**
     * Qty getter
     *
     * @return int
     */
    public function getQty();

    /**
     * Added at setter
     *
     * @param string $addedAt
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface
     */
    public function setAddedAt(string $addedAt);

    /**
     * Added at getter
     *
     * @return int|null
     */
    public function getAddedAt();

    /**
     * Additional data setter
     *
     * @param \Jeysmook\ShopList\Api\Data\ItemAdditionalInterface $additional
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface
     */
    public function setAdditional(\Jeysmook\ShopList\Api\Data\ItemAdditionalInterface $additional);

    /**
     * Additional data getter
     *
     * @return \Jeysmook\ShopList\Api\Data\ItemAdditionalInterface
     */
    public function getAdditional();

    /**
     * By request setter
     *
     * @param string $byRequest
     * @return \Jeysmook\ShopList\Api\Data\ItemInterface
     */
    public function setByRequest(string $byRequest);

    /**
     * By request getter
     *
     * @return string|null
     */
    public function getByRequest();
}
