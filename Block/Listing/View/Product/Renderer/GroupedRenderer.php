<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Listing\View\Product\Renderer;

use Magento\Catalog\Api\Data\ProductInterface;

class GroupedRenderer extends AbstractRenderer
{
    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'Jeysmook_ShopList::listing/view/product/renderer/grouped.phtml';
    }

    /**
     * Retrieve grouped children with by request qty
     *
     * @return ProductInterface[]
     */
    public function getChildren()
    {
        $byRequest = $this->getByRequest();
        $optionKey = md5(json_encode($byRequest)) . 'loaded_children';

        if (!$this->getData($optionKey)) {
            $idsQty = [];
            if (isset($byRequest['super_group']) && is_array($byRequest['super_group'])) {
                $idsQty = $byRequest['super_group'];
            }

            $childs = [];
            $typeInstance = $this->getProduct()->getTypeInstance();
            foreach ($typeInstance->getAssociatedProducts($this->getProduct()) as $child) {
                if (isset($idsQty[$child->getId()]) && $idsQty[$child->getId()] > 0) {
                    $child->setByRequestQty($idsQty[$child->getId()]);
                    $childs[] = $child;
                }
            }

            $this->setData($optionKey, $childs);
        }

        return $this->getData($optionKey);
    }
}
