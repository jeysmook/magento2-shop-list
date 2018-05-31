<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Jeysmook\ShopList\Api\Data\ItemInterface;
use Jeysmook\ShopList\Api\Data\ListInterface;
use Jeysmook\ShopList\Api\ItemRepositoryInterface;

class SList extends AbstractModel implements ListInterface
{
    /** @var SList\Validator */
    private $validator;

    /** @var ItemRepositoryInterface */
    private $itemRepository;

    public function __construct(
        Context $context,
        Registry $registry,
        SList\Validator $validator,
        ItemRepositoryInterface $itemRepository,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->validator = $validator;
        $this->itemRepository = $itemRepository;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\SList::class);
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
    public function afterSave()
    {
        parent::afterSave();

        $items = $this->getData(self::ITEMS);
        if (is_array($items)) {
            foreach ($items as $itemData) {
                if ($itemData instanceof ItemInterface) {
                    if (true === $itemData->getIsDuplicate() || $itemData->getId() === null) {
                        $itemData->setId(null);
                        $itemData->setListId($this->getId());
                        $this->itemRepository->save($itemData);
                    }
                } else if (is_array($itemData)) {
                    if (isset($itemData['item_id']) && $itemData['item_id'] > 0) {
                        $item = $this->itemRepository->getById($itemData['item_id']);
                        $item->addData($itemData);
                        $this->itemRepository->save($item);
                    }
                }
            }
        }

        return $this;
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
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerId(int $customerId)
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId(int $storeId)
    {
        $this->setData(self::STORE_ID, $storeId);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->setData(self::CREATED_AT, $createdAt);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Updated at setter
     *
     * @param string $updatedAt
     * @return ListInterface
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle(string $title)
    {
        $this->setData(self::TITLE, $title);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription(string $description)
    {
        $this->setData(self::DESCRIPTION, $description);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $keys = [])
    {
        $data = parent::toArray($keys);
        // needs for config
        $data['id'] = $this->getId();
        $data['products_count'] = $this->getProductsCount();
        $data['url'] = $this->getListUrl();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setItems(array $items)
    {
        $_items = [];
        foreach ($items as $item) {
            if ($item instanceof ItemInterface) {
                $_items[] = $item;
            }
        }

        $this->setData(self::ITEMS, $_items);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        if (!$this->getData(self::ITEMS)) {
            $collection = $this->itemRepository->createCollection();
            $collection->addFieldToFilter('list_id', ['eq' => (int)$this->getId()]);

            $items = [];
            foreach ($collection->load() as $item) {
                // check on product permissions
                if ($item->getProduct()->getStatus() && $item->getProduct()->isVisibleInSiteVisibility()) {
                    $items[] = $item;
                } else {
                    // remove item from shopping list
                    $this->itemRepository->delete($item);
                }
            }

            $this->setData(self::ITEMS, $items);
        }

        return $this->getData(self::ITEMS);
    }

    /**
     * {@inheritdoc}
     */
    public function getProductsCount()
    {
        if (!$this->getData('products_count')) {
            $this->setData('products_count', count($this->getItems()));
        }

        return (int)$this->getData('products_count');
    }

    /**
     * Retreive list URL
     *
     * @return null|string
     */
    public function getListUrl()
    {
        $listId = $this->getId();
        if ($listId > 0) {
            /** @todo modify url */
            return '/shoppinglist/view/id' . $listId;
        }

        return null;
    }
}
