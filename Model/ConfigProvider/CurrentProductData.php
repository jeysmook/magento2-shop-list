<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\ConfigProvider;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Registry;
use Jeysmook\ShopList\Model\ConfigProviderInterface;

class CurrentProductData implements ConfigProviderInterface
{
    /** @var Registry */
    private $registry;

    /** @var ImageHelper */
    private $imageHelper;

    /** @var StockRegistryInterface */
    private $stockRegistry;

    public function __construct(
        Registry $registry,
        ImageHelper $imageHelper,
        StockRegistryInterface $stockRegistry
    ) {
        $this->registry = $registry;
        $this->imageHelper = $imageHelper;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $productConfig = [];

        if ($this->getProduct() instanceof ProductInterface) {
            $allowedFields = ['name', 'sku', 'entity_id'];
            foreach ($allowedFields as $fieldName) {
                $productConfig[$fieldName] = $this->getProduct()->getData($fieldName);
            }

            $productConfig['url'] = $this->getProduct()->getProductUrl();
            $productConfig['image'] = $this->imageHelper
                ->init($this->getProduct(), 'product_page_image_medium')
                ->getUrl();
            $stock = $this->stockRegistry->getStockItem($this->getProduct()->getId());
            $productConfig['min_qty'] = $stock->getMinSaleQty() ?: 1;
            $productConfig['max_qty'] = $stock->getMaxSaleQty() ?: 10000;
            $productConfig['qty_increments'] = (float)($stock->getQtyIncrements() ?: 1);
        }

        return ['product' => $productConfig];
    }

    /**
     * Retrieve current product
     *
     * @return ProductInterface|null
     */
    private function getProduct()
    {
        return $this->registry->registry('product');
    }
}
