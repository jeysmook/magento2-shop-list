<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Item;

use Jeysmook\ShopList\Controller\AbstractItemAction;

class Index extends AbstractItemAction
{
    public function execute()
    {
        $this->_forward('index', 'listing', 'shoppinglist');
    }
}
