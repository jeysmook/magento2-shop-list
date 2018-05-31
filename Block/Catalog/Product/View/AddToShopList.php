<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Catalog\Product\View;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Jeysmook\ShopList\Model\CompositeConfigProvider;

class AddToShopList extends Template
{
    /** @var CompositeConfigProvider */
    private $configProvider;

    /** @var SerializerInterface */
    private $serializer;

    /** @var Registry */
    private $registry;

    public function __construct(
        Template\Context $context,
        CompositeConfigProvider $configProvider,
        SerializerInterface $serializer,
        Registry $registry,
        array $data = []
    ) {
        $this->configProvider = $configProvider;
        $this->serializer = $serializer;
        $this->registry = $registry;

        parent::__construct($context, $data);
    }

    /**
     * Retrieve current product model
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->registry->registry('product');
    }

    /**
     * Retrieve serialized config
     *
     * @return string
     */
    public function getSerializedConfig()
    {
        $config = $this->configProvider->getConfig();
        $config['baseApiUrl'] = $this->getUrl('') . 'rest/V1/shop-list/';

        return $this->serializer->serialize($config);
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        if (!($this->getProduct() instanceof ProductInterface)) {
            return '';
        }

        return parent::_toHtml();
    }
}
