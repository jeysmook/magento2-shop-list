<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Index;

use Jeysmook\ShopList\Controller\AbstractListingAction;

class Index extends AbstractListingAction
{
    public function execute()
    {
        $this->_forward('index', 'listing', 'shoppinglist');
    }
}
