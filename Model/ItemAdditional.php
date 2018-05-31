<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model;

use Magento\Framework\DataObject;
use Jeysmook\ShopList\Api\Data\ItemAdditionalInterface;

class ItemAdditional extends DataObject implements ItemAdditionalInterface
{
    /**
     * {@inheritdoc}
     */
    public function setNewShopTitle(string $title)
    {
        $this->setData(self::NEW_SHOP_TITLE, $title);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewShopTitle()
    {
        return $this->getData(self::NEW_SHOP_TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setComment(string $comment)
    {
        $this->setData(self::COMMENT, $comment);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComment()
    {
        return $this->getData(self::COMMENT);
    }
}
