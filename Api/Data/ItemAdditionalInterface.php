<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Api\Data;

interface ItemAdditionalInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const NEW_SHOP_TITLE = 'new_shop_title';
    const COMMENT = 'comment';
    /**#@-*/

    /**
     * New shop title setter
     *
     * @param string $title
     * @return ItemInterface
     */
    public function setNewShopTitle(string $title);

    /**
     * New shop title getter
     *
     * @return string|null
     */
    public function getNewShopTitle();

    /**
     * Comment setter
     *
     * @param string $comment
     * @return ItemInterface
     */
    public function setComment(string $comment);

    /**
     * Comment getter
     *
     * @return string|null
     */
    public function getComment();
}
