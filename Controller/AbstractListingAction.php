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
use Jeysmook\ShopList\Api\Data\ListInterface;
use Jeysmook\ShopList\Api\ListRepositoryInterface;
use Jeysmook\ShopList\Helper\Data as Helper;

abstract class AbstractListingAction extends AbstractAccount
{
    const LIST_REGISTRY_KEY = 'current_shopping_list';
    const MASS_FIELD_NAME = 'items';

    /** @var Helper */
    protected $helper;

    /** @var Registry */
    protected $registry;

    /** @var ListRepositoryInterface */
    protected $listRepository;

    public function __construct(
        Context $context,
        Helper $helper,
        Registry $registry,
        ListRepositoryInterface $listRepository
    ) {
        $this->helper = $helper;
        $this->registry = $registry;
        $this->listRepository = $listRepository;

        parent::__construct($context);
    }

    /**
     * Retrieve current shop list
     *
     * @return ListInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function initList()
    {
        $list = $this->registry->registry(self::LIST_REGISTRY_KEY);
        if ($list instanceof ListInterface) {
            return $list;
        }

        $listId = (int)$this->getRequest()->getParam('id');
        if ($listId > 0) {
            $list = $this->listRepository->getById($listId);
        } else {
            // add empty data object
            $list = $this->listRepository->create();
        }

        $this->registry->register(self::LIST_REGISTRY_KEY, $list);

        return $list;
    }
}
