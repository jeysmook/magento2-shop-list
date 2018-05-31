<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\SList\Validator;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\Validator\ValidatorInterface;

class Access extends AbstractValidator implements ValidatorInterface
{
    const CODE = 'invalid_access';
    const MESSAGE = 'You do not have access to this resource.';

    /** @var CustomerSession */
    private $customerSession;

    public function __construct(
        CustomerSession $customerSession
    ) {
        $this->customerSession = $customerSession;
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

        $sessionCustomerId = $this->customerSession->getCustomerId();
        if ($sessionCustomerId === null || (int)$sessionCustomerId !== (int)$value->getCustomerId()) {
            $this->_addMessages([self::CODE => (string)__(self::MESSAGE)]);
            return false;
        }

        return true;
    }
}
