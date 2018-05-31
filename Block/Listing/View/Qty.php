<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Listing\View;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\View\Element\Template;
use Jeysmook\ShopList\Api\Data\ItemInterface;

class Qty extends Template
{
    /** @var StockRegistryInterface */
    private $stockRegistry;

    public function __construct(
        Template\Context $context,
        StockRegistryInterface $stockRegistry,
        array $data = []
    ) {
        $this->stockRegistry = $stockRegistry;

        parent::__construct($context, $data);
    }

    /**
     * Product setter
     *
     * @param ProductInterface $product
     * @return $this
     */
    public function setProduct(ProductInterface $product)
    {
        $this->setData('product', $product);
        return $this;
    }

    /**
     * Product getter
     *
     * @return ProductInterface|null
     */
    public function getProduct()
    {
        $product = $this->getData('product');
        if ($product instanceof ProductInterface) {
            return $product;
        }

        return null;
    }

    /**
     * Item setter
     *
     * @param ItemInterface $item
     * @return $this
     */
    public function setItem(ItemInterface $item)
    {
        $this->setData('item', $item);
        return $this;
    }

    public function getItem()
    {
        $item = $this->getData('item');
        if ($item instanceof ItemInterface) {
            return $item;
        }

        return null;
    }

    /**
     * @return int
     */
    public function getMinAllowed()
    {
        $stock = $this->stockRegistry->getStockItem($this->getProduct()->getId());
        return $stock->getMinSaleQty() ?: 1;
    }

    /**
     * @return int
     */
    public function getMaxAllowed()
    {
        $stock = $this->stockRegistry->getStockItem($this->getProduct()->getId());
        return $stock->getMaxSaleQty() ?: 1;
    }

    /**
     * @return float
     */
    public function getQtyIncrements()
    {
        $stock = $this->stockRegistry->getStockItem($this->getProduct()->getId());
        return (float)($stock->getQtyIncrements() ?: 1);
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        if ($this->getProduct() === null || $this->getItem() === null) {
            return '';
        }

        return parent::_toHtml();
    }
}
