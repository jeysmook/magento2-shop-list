<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\SList\Validator;

use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\Validator\ValidatorInterface;
use Magento\Store\Model\StoreManagerInterface;

class Store extends AbstractValidator implements ValidatorInterface
{
    const CODE = 'invalid_store';
    const MESSAGE = 'The store identify is incorrect.';

    /** @var StoreManagerInterface */
    private $storeManager;

    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Validate value
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        $currentStoreId = $this->storeManager->getStore()->getId();
        if ($currentStoreId === null || (int)$currentStoreId !== (int)$value->getStoreId()) {
            $this->_addMessages([self::CODE => (string)__(self::MESSAGE)]);
            return false;
        }

        return true;
    }
}
