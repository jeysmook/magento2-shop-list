<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Listing\View\Product\Renderer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Framework\DataObject;
use Magento\Framework\Pricing\Render;
use Magento\Framework\View\Element\Template;

abstract class AbstractRenderer extends Template implements RendererInterface
{
    /** @var ImageHelper */
    private $imageHelper;

    public function __construct(
        Template\Context $context,
        ImageHelper $imageHelper,
        array $data = []
    ) {
        $this->imageHelper = $imageHelper;

        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        if (null === $this->getProduct() || !$this->getProduct()->getShopListItemRef()) {
            return '';
        }

        return $this->toHtml();
    }

    /**
     * Product getter
     *
     * @return ProductInterface|null
     */
    public function getProduct()
    {
        $product = $this->getData('product');
        if ($product instanceof ProductInterface) {
            return $product;
        }

        return null;
    }

    /**
     * Product setter
     *
     * @param ProductInterface $product
     * @return $this
     */
    public function setProduct(ProductInterface $product)
    {
        $this->setData('product', $product);

        return $this;
    }

    /**
     * Retreive product image URL
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageHelper
            ->init($this->getProduct(), 'product_page_image_medium')
            ->getUrl();
    }

    /**
     * Retreive product price box
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getPriceHtml()
    {
        $arguments = [
            'display_label' => __('Price:'),
            'zone' => Render::ZONE_ITEM_LIST
        ];

        /** @var Render $priceRender */
        $priceRender = $this->getLayout()->createBlock(Render::class);
        $price = '';

        if ($priceRender) {
            $price = $priceRender->render(FinalPrice::PRICE_CODE, $this->getProduct(), $arguments);
        }

        return (string)$price;
    }

    /**
     * Retrieve by request for product
     *
     * @return array|null
     */
    public function getByRequest()
    {
        $byRequest = $this->getProduct()
            ->getShopListItemRef()
            ->getByRequest();

        return json_decode($byRequest, true);
    }

    /**
     * Retrive selected options
     *
     * @return DataObject[]
     */
    public function getOptions()
    {
        $byRequest = $this->getByRequest();
        $optionKey = md5(json_encode($byRequest) . $this->getProduct()->getId()) . 'loaded_options';

        $options = $this->getData($optionKey);
        if (is_array($options)) {
            return $options;
        }

        $options = [];

        $optionValueIds = [];
        if (isset($byRequest['options']) && is_array($byRequest['options'])) {
            $optionValueIds = $byRequest['options'];
        }

        foreach ($this->getProduct()->getOptions() as $option) {
            if (!isset($optionValueIds[$option->getOptionId()])) {
                continue;
            }

            $values = $optionValueIds[$option->getOptionId()];
            if (!is_array($values)) {
                $values = [$values];
            }

            if (array_key_exists($option->getOptionId(), $optionValueIds)) {
                $group = $option->groupFactory($option->getType())
                    ->setOption($option)
                    ->setProduct($this->getProduct());

                if (in_array($option->getType(), ['date_time', 'date', 'time'])) {
                    if (isset($values['day_part'])) {
                        $pmDayPart = 'pm' == strtolower($values['day_part']);
                        if (12 == $values['hour']) {
                            $values['hour'] = $pmDayPart ? 12 : 0;
                        } elseif ($pmDayPart) {
                            $values['hour'] += 12;
                        }
                    }

                    $date = isset($values['year']) ? $values['year'] : '';
                    $date .= isset($values['month']) ? '-' . $values['month'] : '';
                    $date .= isset($values['day']) ? '-' . $values['day'] : '';
                    $time = isset($values['hour']) ? ' ' . $values['hour'] : '';
                    $time .= isset($values['minute']) ? ':' . $values['minute'] : '';
                    $time .= isset($values['second']) ? ':' . $values['second'] : '';

                    $dateTime = $date . $time;
                    $options[] = new DataObject([
                        'label' => $option->getTitle(),
                        'value' => $group->getFormattedOptionValue($dateTime)
                    ]);
                } else if (in_array($option->getType(), ['drop_down', 'radio', 'checkbox', 'multiple'])) {
                    foreach ($option->getValues() as $value) {
                        if (in_array($value->getId(), $values)) {
                            $options[] = new DataObject([
                                'label' => $option->getTitle(),
                                'value' => $group->getFormattedOptionValue($value->getId())
                            ]);
                        }
                    }
                } else {
                    $options[] = new DataObject([
                        'label' => $option->getTitle(),
                        'value' => $group->getFormattedOptionValue($values[0])
                    ]);
                }
            }
        }

        $this->setData($optionKey, $options);

        return $options;
    }
}