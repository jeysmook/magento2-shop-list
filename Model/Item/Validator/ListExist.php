<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\Item\Validator;

use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\Validator\ValidatorInterface;
use Jeysmook\ShopList\Api\ListRepositoryInterface;

class ListExist extends AbstractValidator implements ValidatorInterface
{
    const CODE = 'invalid_list';
    const MESSAGE = 'The shopping list does not exists.';

    /** @var ListRepositoryInterface */
    private $listRepository;

    public function __construct(
        ListRepositoryInterface $listRepository
    ) {
        $this->listRepository = $listRepository;
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

        try {
            $this->listRepository->getById((int)$value->getListId());
            return true;
        } catch (\Exception $e) {
            $this->_addMessages([self::CODE => (string)__(self::MESSAGE)]);
            return false;
        }
    }
}
