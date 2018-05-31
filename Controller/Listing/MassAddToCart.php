<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Listing;

use Magento\Framework\App\Action\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Jeysmook\ShopList\Api\CartServiceInterface;
use Jeysmook\ShopList\Api\ListRepositoryInterface;
use Jeysmook\ShopList\Controller\AbstractListingAction;
use Jeysmook\ShopList\Helper\Data as Helper;

class MassAddToCart extends AbstractListingAction
{
    /** @var CartServiceInterface */
    private $cartService;

    public function __construct(
        Context $context, Helper
        $helper,
        Registry $registry,
        ListRepositoryInterface $listRepository,
        CartServiceInterface $cartService
    ) {
        $this->cartService = $cartService;

        parent::__construct($context, $helper, $registry, $listRepository);
    }

    public function execute()
    {
        $redirectObject = new DataObject(['path' => '*/', 'args' => []]);

        $listIds = $this->getRequest()->getParam(self::MASS_FIELD_NAME);
        if (!empty($listIds) && is_array($listIds)) {
            $collection = $this->listRepository->createCollection();
            $collection->addFieldToFilter('list_id', ['in' => $listIds])
                ->addStoreFilter($this->helper->getCustomer()->getStoreId())
                ->addCustomerFilter($this->helper->getCustomer()->getId());

            $arrayItems = [];
            foreach ($collection as $list) {
                $arrayItems = array_merge($arrayItems, $list->getItems());
            }

            try {
                $this->cartService->addItemsToCart($arrayItems);
                $this->messageManager->addSuccessMessage(__('(%1) item(s) were added successfully to cart.', count($arrayItems)));
            } catch (\Exception $e) {
                $this->messageManager->addSuccessMessage(__('Could not add items to cart.'));
            }
        }

        return $this->_redirect($redirectObject->getPath(), $redirectObject->getArgs());
    }
}
