<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Jeysmook\ShopList\Api\ListRepositoryInterface;
use Jeysmook\ShopList\Helper\Data;

abstract class AbstractListingBlock extends Template
{
    /** @var ListRepositoryInterface */
    protected $listRepository;

    /** @var Data */
    protected $helper;

    /** @var Registry */
    protected $registry;

    public function __construct(
        Template\Context $context,
        ListRepositoryInterface $listRepository,
        Data $helper,
        Registry $registry,
        array $data = []
    ) {
        $this->listRepository = $listRepository;
        $this->helper = $helper;
        $this->registry = $registry;

        parent::__construct($context, $data);
    }

    /**
     * Retrieve current customer
     *
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomer()
    {
        return $this->helper->getCustomer();
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        // check is public block
        if ($this->getIsPublicBlock()) {
            return parent::_toHtml();
        }

        // check if customer logged in
        if ($this->getCustomer() && $this->getCustomer()->getId()) {
            return parent::_toHtml();
        }

        return '';
    }

    /**
     * Retrieve formated Date
     *
     * @param string $date
     * @return string
     */
    public function getFormatedDate($date)
    {
        return $this->formatDate($date, \IntlDateFormatter::MEDIUM);
    }
}
