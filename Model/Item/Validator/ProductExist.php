<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\Item\Validator;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\Validator\ValidatorInterface;

class ProductExist extends AbstractValidator implements ValidatorInterface
{
    const CODE = 'invalid_product';
    const MESSAGE = 'The product does not exists.';

    /** @var ProductRepositoryInterface */
    private $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
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

        $returnValue = false;

        try {
            /** @var ProductInterface $product */
            $product = $this->productRepository->getById((int)$value->getProductId());
            if ($product->getStatus() == '1' && $product->isVisibleInSiteVisibility()) {
                $returnValue = true;
            }
        } catch (\Exception $e) {}

        if (false === $returnValue) {
            $this->_addMessages([self::CODE => (string)__(self::MESSAGE)]);
        }

        return $returnValue;
    }
}
