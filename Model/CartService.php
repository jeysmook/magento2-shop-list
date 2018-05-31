<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model;

use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Jeysmook\ShopList\Api\CartServiceInterface;
use Jeysmook\ShopList\Api\Data\ItemInterface;

class CartService implements CartServiceInterface
{
    /** @var CustomerCart */
    private $cart;

    /** @var ManagerInterface */
    private $eventManager;

    public function __construct(
        CustomerCart $cart,
        ManagerInterface $eventManager
    ) {
        $this->cart = $cart;
        $this->eventManager = $eventManager;
    }

    /**
     * {@inheritdoc}
     */
    public function addItemToCart(ItemInterface $item)
    {
        try {
            $this->addProductToCart($item);

            // save shopping cart
            $this->cart->save();
        } catch (\Exception $e) {
            throw new LocalizedException(__('An error occurred while adding the product to the shopping cart.'));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function addItemsToCart(array $items)
    {
        try {
            foreach ($items as $item) {
                $this->addProductToCart($item);
            }

            // save shopping cart
            $this->cart->save();
        } catch (\Exception $e) {
            throw new LocalizedException(__('An error occurred while adding the products to the shopping cart.'));
        }

        return true;
    }

    /**
     * Add product to cart
     *
     * @param ItemInterface $item
     * @return $this
     * @throws LocalizedException
     */
    private function addProductToCart(ItemInterface $item)
    {
        $params = $item->getByRequest();
        $params = json_decode($params, true);

        $qty = $item->getQty();
        if ($qty > 0) {
            // modify qty for grouped product
            if ($item->getProduct()->getTypeId() === Grouped::TYPE_CODE
                && isset($params['super_group'])
                && is_array($params['super_group'])) {
                foreach (array_keys($params['super_group']) as $id) {
                    $params['super_group'][$id] *= $qty;
                }
            } else { // modify qty for other products
                $params['qty'] = $qty;
            }
        }

        $this->eventManager->dispatch(
            'shop_list_before_add_to_cart',
            ['item' => $item, 'product' => $item->getProduct(), 'params' => $params]
        );

        // add product to cart
        $this->cart->addProduct($item->getProduct(), $params);

        return $this;
    }
}
