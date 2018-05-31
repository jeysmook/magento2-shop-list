<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Listing;

use Jeysmook\ShopList\Controller\AbstractListingAction;

class View extends AbstractListingAction
{
    public function execute()
    {
        $list = $this->initList();
        if (!$list->getId()) {
            return $this->_redirect('*/');
        }

        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->getPage()->getConfig()->getTitle()->set(__('View shopping list "%1"', $list->getTitle()));
        $this->_view->renderLayout();
    }
}
