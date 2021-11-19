<?php
/**
 * customer city and postcode autocomplete 
 * Copyright (C) 2021 Deki
 * 
 * This file is part of Deki/CustomerAddress.
 * 
 * Deki/CustomerAddress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Deki\CustomerAddress\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
  /**
   * {@inheritdoc}
   */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $cityTable = $setup->getTable('deki_customeraddress_city');
        $tableDekiCustomeraddressCity = $setup->getConnection()->newTable($cityTable);

        $tableDekiCustomeraddressCity->addColumn(
            'city_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $tableDekiCustomeraddressCity->addColumn(
            'country_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            4,
            ['nullable' => False],
            'country_id'
        );

        $tableDekiCustomeraddressCity->addColumn(
            'region_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false,'unsigned' => true,],
            'region_id'
        );

        $tableDekiCustomeraddressCity->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => False],
            'name'
        );

        $tableDekiCustomeraddressCity->addColumn(
            'postcode',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            20,
            [],
            'postcode'
        );

        $tableDekiCustomeraddressCity->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'updated_at'
        );

        $tableDekiCustomeraddressCity->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'created_at'
        );

        $setup->getConnection()->createTable($tableDekiCustomeraddressCity);

        $setup->getConnection()->addIndex(
            $setup->getTable($cityTable),
            $setup->getIdxName($cityTable, ['country_id']),
            ['country_id']
        );

        $setup->getConnection()->addIndex(
            $setup->getTable($cityTable),
            $setup->getIdxName($cityTable, ['region_id']),
            ['region_id']
        );

        $setup->getConnection()->addIndex(
            $setup->getTable($cityTable),
            $setup->getIdxName($cityTable, ['name']),
            ['name']
        );
        
        $setup->endSetup();
    }
}