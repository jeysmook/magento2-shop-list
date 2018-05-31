<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

class SList extends AbstractDb
{
    /** @var DateTime */
    private $dateTime;

    public function __construct(
        Context $context,
        DateTime $dateTime,
        string $connectionName = null
    ) {
        $this->dateTime = $dateTime;
        parent::__construct($context, $connectionName);
    }


    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('jeysmook_shoplist_list', 'list_id');
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeSave(AbstractModel $object)
    {
        $object->setUpdatedAt($this->dateTime->date('Y-m-d H:i:s'));
        return parent::_beforeSave($object);
    }
}
