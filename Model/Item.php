<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Jeysmook\ShopList\Api\Data\ItemAdditionalInterface;
use Jeysmook\ShopList\Api\Data\ItemAdditionalInterfaceFactory;
use Jeysmook\ShopList\Api\Data\ItemInterface;
use Jeysmook\ShopList\Api\Data\ListInterface;
use Jeysmook\ShopList\Api\ListRepositoryInterface;

class Item extends AbstractModel implements ItemInterface
{
    /** @var Item\Validator */
    private $validator;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var ListRepositoryInterface */
    private $listRepository;

    /** @var ItemAdditionalInterfaceFactory */
    private $additionalFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        Item\Validator $validator,
        ProductRepositoryInterface $productRepository,
        ListRepositoryInterface $listRepository,
        ItemAdditionalInterfaceFactory $additionalFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->validator = $validator;
        $this->productRepository = $productRepository;
        $this->listRepository = $listRepository;
        $this->additionalFactory = $additionalFactory;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Item::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function _getValidatorBeforeSave()
    {
        return $this->validator;
    }

    /**
     * {@inheritdoc}
     */
    public function validateBeforeSave()
    {
        $key = 'validate_before_save_flag';
        if (true !== $this->getData($key)) {
            $this->setData($key, true);
            return parent::validateBeforeSave();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave()
    {
        parent::beforeSave();

        // prepare data before save
        $additionalData = $this->getAdditional()->getData();

        // from post request
        if (is_array($this->getData('additional'))) {
            $additionalData = array_merge($additionalData, $this->getData('additional'));
        }

        $this->setData(self::ADDITIONAL, json_encode($additionalData));

        return $this;
    }

    /**
     * Retreive item product
     *
     * @return ProductInterface|null
     */
    public function getProduct()
    {
        $product = $this->getData('loaded_product');
        if (!$product) {
            $productId = (int)$this->getProductId();
            if ($productId > 0) {
                try {
                    $product = $this->productRepository->getById((int)$this->getProductId());
                    $product->setShopListItemRef($this);
                    $this->setData('loaded_product', $product);
                } catch (\Exception $e) {}
            }
        }

        return $product;
    }

    /**
     * Retrieve item list
     *
     * @return ListInterface|null
     */
    public function getList()
    {
        $list = $this->getData('loaded_list');
        if (!$list) {
            $listId = (int)$this->getListId();
            if ($listId > 0) {
                try {
                    $list = $this->listRepository->getById($listId);
                    $list->setShopListItemRef($this);
                    $this->setData('loaded_list', $list);
                } catch (\Exception $e) {}
            }
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->setData(self::ID, $id);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setListId(int $listId)
    {
        $this->setData(self::LIST_ID, $listId);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getListId()
    {
        return $this->getData(self::LIST_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductId(int $productId)
    {
        $this->setData(self::PRODUCT_ID, $productId);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setQty(int $qty)
    {
        $this->setData(self::QTY, $qty);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * {@inheritdoc}
     */
    public function setAddedAt(string $addedAt)
    {
        $this->setData(self::ADDED_AT, $addedAt);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddedAt()
    {
        return $this->getData(self::ADDED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setAdditional(ItemAdditionalInterface $additional)
    {
        $this->setData(self::ADDITIONAL, $additional);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdditional()
    {
        $data = $this->getData(self::ADDITIONAL);
        if (!($data instanceof ItemAdditionalInterface)) {
            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            if (!is_array($data)) {
                $data = [];
            }
            $this->setAdditional($this->additionalFactory->create()->setData($data));
        }

        return $this->getData(self::ADDITIONAL);
    }

    /**
     * {@inheritdoc}
     */
    public function setByRequest(string $byRequest)
    {
        $this->setData(self::BY_REQUEST, $byRequest);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getByRequest()
    {
        return $this->getData(self::BY_REQUEST);
    }
}
