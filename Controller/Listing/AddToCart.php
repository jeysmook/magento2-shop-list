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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Jeysmook\ShopList\Api\CartServiceInterface;
use Jeysmook\ShopList\Api\ListRepositoryInterface;
use Jeysmook\ShopList\Controller\AbstractListingAction;
use Jeysmook\ShopList\Helper\Data as Helper;

class AddToCart extends AbstractListingAction
{
    /** @var CartServiceInterface */
    private $cartService;

    public function __construct(
        Context $context,
        Helper $helper,
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

        $list = $this->initList();
        if ($list->getId()) {
            $redirectObject->setPath('*/listing')->setArgs(['id' => $list->getId()]);

            try {
                $this->cartService->addItemsToCart($list->getItems());

                $this->messageManager->addSuccessMessage(__('Shopping list "%1" was added successfully to cart.', $list->getTitle()));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong, try again later.'));
            }
        }

        return $this->_redirect($redirectObject->getPath(), $redirectObject->getArgs());
    }
}
