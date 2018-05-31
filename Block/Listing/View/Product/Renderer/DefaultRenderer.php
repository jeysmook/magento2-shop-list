<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Listing\View\Product\Renderer;

use Magento\Framework\DataObject;

class DefaultRenderer extends AbstractRenderer
{
    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'Jeysmook_ShopList::listing/view/product/renderer/default.phtml';
    }
}
