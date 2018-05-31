<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Listing\View\Product\Renderer;

use Magento\Framework\DataObject;

class ConfigurableRenderer extends AbstractRenderer
{
    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'Jeysmook_ShopList::listing/view/product/renderer/default.phtml';
    }

    /**
     * Get allowed attributes
     *
     * @return array
     */
    public function getOptions()
    {
        $customOptions = parent::getOptions();

        $byRequest = $this->getByRequest();
        $attributeValueIds = isset($byRequest['super_attribute']) && is_array($byRequest['super_attribute']) ? $byRequest['super_attribute'] : [];

        $allowedAttributes = $this->getProduct()->getTypeInstance()
            ->getConfigurableAttributes($this->getProduct());
        foreach ($allowedAttributes as $attribute) {
            if (isset($attributeValueIds[$attribute->getAttributeId()])) {
                foreach ($attribute->getOptions() as $option) {
                    if ($attributeValueIds[$attribute->getAttributeId()] == $option['value_index']) {
                        array_unshift($customOptions, new DataObject([
                            'label' => $attribute->getLabel(),
                            'value' => $option['label']
                        ]));
                    }
                }
            }
        }

        return $customOptions;
    }
}
