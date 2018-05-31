<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Jeysmook\ShopList\Api\Data\ItemInterface;
use Jeysmook\ShopList\Api\ItemRepositoryInterface;
use Jeysmook\ShopList\Helper\Data as Helper;

abstract class AbstractItemAction extends AbstractAccount
{
    const ITEM_REGISTRY_KEY = 'current_item';
    const MASS_FIELD_NAME = 'items';

    /** @var Helper */
    protected $helper;

    /** @var Registry */
    protected $registry;

    /** @var ItemRepositoryInterface */
    protected $itemRepository;

    public function __construct(
        Context $context,
        Helper $helper,
        Registry $registry,
        ItemRepositoryInterface $itemRepository
    ) {
        $this->helper = $helper;
        $this->registry = $registry;
        $this->itemRepository = $itemRepository;

        parent::__construct($context);
    }

    /**
     * Retrieve current item
     *
     * @return ItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function initItem()
    {
        $item = $this->registry->registry(self::ITEM_REGISTRY_KEY);
        if ($item instanceof ItemInterface) {
            return $item;
        }

        $itemId = (int)$this->getRequest()->getParam('id');
        if ($itemId > 0) {
            $item = $this->itemRepository->getById($itemId);
        } else {
            // add empty data object
            $item = $this->itemRepository->create();
        }

        $this->registry->register(self::ITEM_REGISTRY_KEY, $item);

        return $item;
    }
}
