<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Item;

use Magento\Framework\App\Action\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Jeysmook\ShopList\Api\CartServiceInterface;
use Jeysmook\ShopList\Api\ItemRepositoryInterface;
use Jeysmook\ShopList\Controller\AbstractItemAction;
use Jeysmook\ShopList\Helper\Data as Helper;

class MassAddToCart extends AbstractItemAction
{
    /** @var CartServiceInterface */
    private $cartService;

    public function __construct(
        Context $context,
        Helper $helper,
        Registry $registry,
        ItemRepositoryInterface $itemRepository,
        CartServiceInterface $cartService
    ) {
        $this->cartService = $cartService;

        parent::__construct($context, $helper, $registry, $itemRepository);
    }

    public function execute()
    {
        $listId = (int)$this->getRequest()->getParam('list_id');
        if ($listId > 0) {
            $redirectObject = new DataObject(['path' => '*/listing/view', 'args' => ['id' => $listId]]);
        } else {
            $redirectObject = new DataObject(['path' => '*/', 'args' => []]);
        }

        $itemIds = $this->getRequest()->getParam(self::MASS_FIELD_NAME);
        if (!empty($itemIds) && is_array($itemIds)) {
            $collection = $this->itemRepository->createCollection();
            $collection->addFieldToFilter('item_id', ['in' => $itemIds])
                ->addCustomerFilter($this->helper->getCustomer());

            $arrayItems = [];
            foreach ($collection as $item) {
                $arrayItems[] = $item;
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
