<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\SList;

use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\Validator\ValidatorInterface;

class Validator extends AbstractValidator implements ValidatorInterface
{
    /** @var array */
    private $validators;

    public function __construct(
        array $validators
    ) {
        $this->validators = $validators;
    }

    /**
     * Validate list entity
     *
     * @param $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_clearMessages();

        // validate required fields
        foreach (['title', 'store_id', 'customer_id'] as $field) {
            if (empty($value->getData($field))) {
                $this->_addMessages(['invalid_data' => (string)__('The data is filled in incorrectly.')]);
                return false;
            }
        }

        $returnValue = true;
        foreach ($this->validators as $validator) {
            if (!$validator->isValid($value)) {
                $returnValue = false;
                $this->_addMessages($validator->getMessages());
            }
        }
        return $returnValue;
    }
}
