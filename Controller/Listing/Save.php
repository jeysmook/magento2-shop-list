<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Listing;

use Jeysmook\ShopList\Controller\AbstractListingAction;

class Save extends AbstractListingAction
{
    public function execute()
    {
        $list = $this->initList();

        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        if ($list->getId() > 0) {
            $this->_view->getPage()->getConfig()->getTitle()->set(__('Edit shopping list "%1"', $list->getTitle()));
        } else {
            $this->_view->getPage()->getConfig()->getTitle()->set(__('Create new shopping list'));
        }
        $this->_view->renderLayout();
    }
}
