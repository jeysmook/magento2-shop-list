<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\Item;

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
        $returnValue = true;

        $this->_clearMessages();

        // validate required fields
        foreach (['list_id', 'product_id'] as $field) {
            if (empty($value->getData($field))) {
                $this->_addMessages(['invalid_data' => (string)__('The data is filled in incorrectly.')]);
                return false;
            }
        }

        foreach ($this->validators as $validator) {
            if (!$validator->isValid($value)) {
                $this->_addMessages($validator->getMessages());
                $returnValue = false;
            }
        }
        return $returnValue;
    }
}
