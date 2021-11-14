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
      $table_deki_customeraddress_city = $setup->getConnection()->newTable($setup->getTable('deki_customeraddress_city'));

      $table_deki_customeraddress_city->addColumn(
          'city_id',
          \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
          null,
          ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
          'Entity ID'
      );

      $table_deki_customeraddress_city->addColumn(
          'region_code',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          255,
          ['nullable' => False],
          'region_code'
      );

      $table_deki_customeraddress_city->addColumn(
          'name',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          255,
          ['nullable' => False],
          'name'
      );

      $table_deki_customeraddress_city->addColumn(
          'postcode',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          20,
          [],
          'postcode'
      );

      $table_deki_customeraddress_city->addColumn(
          'updated_at',
          \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
          null,
          [],
          'updated_at'
      );

      $table_deki_customeraddress_city->addColumn(
          'created_at',
          \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
          null,
          [],
          'created_at'
      );

      $setup->getConnection()->createTable($table_deki_customeraddress_city);
  }
}