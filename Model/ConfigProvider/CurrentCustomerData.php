<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\ConfigProvider;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Jeysmook\ShopList\Api\ListRepositoryInterface;
use Jeysmook\ShopList\Model\ConfigProviderInterface;

class CurrentCustomerData implements ConfigProviderInterface
{
    /** @var CustomerSession */
    private $customerSession;

    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    /** @var ListRepositoryInterface */
    private $listRepository;

    public function __construct(
        CustomerSession $customerSession,
        CustomerRepositoryInterface $customerRepository,
        ListRepositoryInterface $listRepository
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->listRepository = $listRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $isLoggedIn = $this->customerSession->isLoggedIn();

        $customerConfig = [
            'isLoggedIn' => $isLoggedIn
        ];

        if (true === $isLoggedIn) {
            $customerConfig['lists'] = $this->getLists();
        }

        return ['customer' => $customerConfig];
    }

    /**
     * Retrieve current customer
     *
     * @return CustomerInterface|null
     */
    private function getCustomer()
    {
        $customer = null;
        try {
            $customer = $this->customerRepository->getById(
                (int)$this->customerSession->getCustomerId()
            );
        } catch (\Exception $e) {}

        return $customer;
    }

    /**
     * Retrieve customer shop lists
     *
     * @return array
     */
    private function getLists()
    {
        $collection = $this->listRepository->createCollection();
        $collection->addStoreFilter($this->getCustomer()->getStoreId())
            ->addCustomerFilter($this->getCustomer()->getId());

        $collectionToArray = $collection->toArray();
        return $collectionToArray['items'];
    }
}
