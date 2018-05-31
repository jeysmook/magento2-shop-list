<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Customer\Account;

use Magento\Framework\View\Element\Html\Link\Current;

class Link extends Current
{
    /**
     * @return bool
     */
    public function isCurrent()
    {
        return $this->_request->getModuleName() === $this->getPath();
    }
}