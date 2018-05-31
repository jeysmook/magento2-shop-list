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
use Jeysmook\ShopList\Api\ItemRepositoryInterface;

class UniqueItem extends AbstractValidator implements ValidatorInterface
{
    const CODE = 'unique_item';
    const MESSAGE = 'You have already added this product to the shopping list.';

    /** @var ItemRepositoryInterface */
    private $itemRepository;

    public function __construct(
        ItemRepositoryInterface $itemRepository
    ) {
        $this->itemRepository = $itemRepository;
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
        $collection = $this->itemRepository->createCollection();
        $collection->addFieldToFilter('list_id', ['eq' => (int)$value->getListId()])
            ->addFieldToFilter('product_id', ['eq' => (int)$value->getProductId()])
            ->addFieldToFilter('by_request', ['eq' => $value->getByRequest()]);

        if ($value->getId() > 0) {
            $collection->addFieldToFilter('item_id', ['neq' => (int)$value->getId()]);
        }

        $isValid = $collection->getSize() === 0;
        if (false === $isValid) {
            $this->_addMessages([self::CODE => (string)__(self::MESSAGE)]);
        }

        return $isValid;
    }
}
