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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Jeysmook\ShopList\Api\CartServiceInterface;
use Jeysmook\ShopList\Api\ItemRepositoryInterface;
use Jeysmook\ShopList\Controller\AbstractItemAction;
use Jeysmook\ShopList\Helper\Data as Helper;

class AddToCart extends AbstractItemAction
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
        $redirectObject = new DataObject(['path' => '*/', 'args' => []]);

        $item = $this->initItem();
        if ($item->getId() > 0) {
            $redirectObject->setPath('*/listing')->setArgs(['id' => $item->getListId()]);

            try {
                $this->cartService->addItemToCart($item);

                $this->messageManager->addSuccessMessage(__('(%1) item was added successfully to cart.', $item->getProduct()->getName()));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong, try again later.'));
            }
        }

        return $this->_redirect($redirectObject->getPath(), $redirectObject->getArgs());
    }
}
