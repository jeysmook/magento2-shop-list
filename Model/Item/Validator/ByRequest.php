<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\Item\Validator;

use Magento\Catalog\Api\ProductCustomOptionRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\Validator\ValidatorInterface;

class ByRequest extends AbstractValidator implements ValidatorInterface
{
    const CODE = 'invalid_by_request';
    const MESSAGE = 'Invalid selected product parameters.';

    /** @var ProductCustomOptionRepositoryInterface */
    private $customOptionRepository;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    public function __construct(
        ProductCustomOptionRepositoryInterface $customOptionRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->customOptionRepository = $customOptionRepository;
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

        $returnValue = true;

        $byRequest = $value->getByRequest();
        $byRequest = json_decode($byRequest, true);

        if ($byRequest === null) {
            $returnValue = false;
        }

        // validate required fields
        foreach (['product'] as $requiredField) {
            if (!isset($byRequest[$requiredField]) || empty($byRequest[$requiredField])) {
                $returnValue = false;
            }
        }

        try {
            $product = $this->productRepository->getById($byRequest['product']);
        } catch (\Exception $e) {
            $returnValue = false;
        }

        // validate custom options
        if (isset($product)
            && isset($byRequest['options'])
            && is_array($byRequest['options'])) {

            $optionIds = array_keys($byRequest['options']);
            foreach ($optionIds as $optionId) {
                try {
                    $this->customOptionRepository->get($product->getSku(), $optionId);
                } catch (\Exception $e) {
                    $returnValue = false;
                }
            }
        }

        if ($returnValue === false) {
            $this->_addMessages([self::CODE => __(self::MESSAGE)]);
        }

        return $returnValue;
    }
}
