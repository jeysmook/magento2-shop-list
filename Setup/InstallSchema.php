<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $installer = $setup;

        /**
         * Create jeysmook_shoplist_list table
         */
        $tableName = $installer->getTable('jeysmook_shoplist_list');
        $table = $installer->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'list_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'List ID'
            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Customer ID'
            )->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Store ID'
            )->addColumn(
                'title',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Title'
            )->addColumn(
                'description',
                Table::TYPE_TEXT,
                '2m',
                ['nullable' => true],
                'Description'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Updated At'
            )->addIndex(
                $installer->getIdxName('jeysmook_shoplist_list', ['list_id']),
                ['list_id']
            )->addIndex(
                $installer->getIdxName('jeysmook_shoplist_list', ['customer_id']),
                ['customer_id']
            )->addIndex(
                $installer->getIdxName('jeysmook_shoplist_list', ['store_id']),
                ['store_id']
            )->addForeignKey(
                $installer->getFkName(
                    $tableName,
                    'customer_id',
                    'customer_entity',
                    'entity_id'
                ),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    $tableName,
                    'store_id',
                    'store',
                    'store_id'
                ),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )->setComment('Shopping list table');
        $installer->getConnection()->createTable($table);

        /**
         * Create jeysmook_shoplist_list_item table
         */
        $tableName = $installer->getTable('jeysmook_shoplist_list_item');
        $table = $installer->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'item_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Item ID'
            )->addColumn(
                'list_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'List ID'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Product ID'
            )->addColumn(
                'added_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Added At'
            )->addColumn(
                'by_request',
                Table::TYPE_TEXT,
                '2m',
                ['default' => null],
                'By Request Parameters'
            )->addColumn(
                'qty',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => 0],
                'Item Qty'
            )->addColumn(
                'additional_data',
                Table::TYPE_TEXT,
                '6m',
                ['nullable' => false],
                'Additional Data'
            )->addIndex(
                $installer->getIdxName('jeysmook_shoplist_list_item', ['list_id']),
                ['list_id']
            )->addIndex(
                $installer->getIdxName('jeysmook_shoplist_list_item', ['product_id']),
                ['product_id']
            )->addForeignKey(
                $installer->getFkName(
                    $tableName,
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    $tableName,
                    'list_id',
                    'jeysmook_shoplist_list',
                    'list_id'
                ),
                'list_id',
                $installer->getTable('jeysmook_shoplist_list'),
                'list_id',
                Table::ACTION_CASCADE
            )->setComment('Shopping List Items Table');
        $installer->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
