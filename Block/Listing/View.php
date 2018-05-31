<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Listing;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Jeysmook\ShopList\Api\Data\ItemInterface;
use Jeysmook\ShopList\Api\Data\ListInterface;
use Jeysmook\ShopList\Block\AbstractListingBlock;
use Jeysmook\ShopList\Block\Listing\View\Product\Renderer\RendererInterface;
use Jeysmook\ShopList\Controller\AbstractListingAction;

class View extends AbstractListingBlock
{
    /**
     * Retrieve current shop list
     *
     * @return ListInterface
     */
    public function getList()
    {
        return $this->registry->registry(AbstractListingAction::LIST_REGISTRY_KEY);
    }

    /**
     * Retreive product renderer
     *
     * @param ProductInterface $product
     * @return RendererInterface
     * @throws LocalizedException
     */
    public function getProductRenderer(ProductInterface $product)
    {
        /** @var RendererInterface $renderer */
        $renderer = $this->getChildBlock('renderer_' . $product->getTypeId())
            ?: $this->getChildBlock('renderer_default');

        if ($renderer instanceof RendererInterface) {
            $renderer->setProduct($product);
            $renderer->setParentBlock($this);
            return $renderer;
        }

        throw new LocalizedException(__('Product renderer is not set.'));
    }

    /**
     * Render qty block
     *
     * @param ItemInterface $item
     * @return string
     */
    public function getQtyChildHtml(ItemInterface $item)
    {
        $qtyBlock = $this->getChildBlock('qty');
        if ($qtyBlock) {
            $qtyBlock->setItem($item);
            $qtyBlock->setProduct($item->getProduct());
            $qtyBlock->setParentBlock($this);

            return $qtyBlock->toHtml();
        }

        return '';
    }

    /**
     * Retrieve list items
     *
     * @return ItemInterface[]
     */
    public function getItems()
    {
        return $this->getList()->getItems();
    }

    /**
     * Retrieve delete item URL
     *
     * @param ItemInterface $item
     * @return string
     */
    public function getDeleteUrl(ItemInterface $item)
    {
        return $this->getUrl('*/item/delete', [
            'id' => $item->getId(),
            'list_id' => $this->getList()->getId()
        ]);
    }

    /**
     * Retrieve delete item URL
     *
     * @param ItemInterface $item
     * @return string
     */
    public function getAddToCartUrl(ItemInterface $item)
    {
        return $this->getUrl('*/item/addToCart', [
            'id' => $item->getId()
        ]);
    }

    /**
     * Retrieve update URL
     *
     * @return string
     */
    public function getUpdateUrl()
    {
        return $this->getUrl('*/listing/savePost', [
            'id' => $this->getList()->getId(),
            'back_action' => 'view'
        ]);
    }

    /**
     * Retrieve mass add to cart URL
     *
     * @return string
     */
    public function getMassAddToCartUrl()
    {
        return $this->getUrl('*/item/massAddToCart', [
            'list_id' => $this->getList()->getId()
        ]);
    }

    /**
     * Retrieve mass delete item URL
     *
     * @return string
     */
    public function getMassDeleteUrl()
    {
        return $this->getUrl('*/item/massDelete', [
            'list_id' => $this->getList()->getId()
        ]);
    }

    /**
     * Retrieve back URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/');
    }
}
