<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Listing;

use Jeysmook\ShopList\Api\Data\ListInterface;
use Jeysmook\ShopList\Block\AbstractListingBlock;
use Jeysmook\ShopList\Controller\AbstractListingAction;

class Form extends AbstractListingBlock
{
    /**
     * Retrieve current shop list
     *
     * @return ListInterface
     */
    public function getList()
    {
        return $this->registry->registry(AbstractListingAction::LIST_REGISTRY_KEY);
    }

    /**
     * Retrieve submit URL
     * 
     * @return string
     */
    public function getSubmitUrl()
    {
        $params = [];
        if (($id = $this->getList()->getId()) > 0) {
            $params['id'] = $id;
        }

        return $this->getUrl('*/*/savePost', $params);
    }

    /**
     * Retrieve back URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/');
    }
}
