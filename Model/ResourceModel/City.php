<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model\ResourceModel;

class City extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Constants related to specific db layer
     */
    const TABLE_NAME_SOURCE_ITEM = 'deki_customeraddress_city';
    const ID_FIELD_NAME = 'city_id';
    
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('deki_customeraddress_city', 'city_id');
    }

    /**
     * Truncate City table
     *
     * @return $this
     */
    public function truncateTable()
    {
        if ($this->getConnection()->getTransactionLevel() > 0) {
            $this->getConnection()->delete($this->getMainTable());
        } else {
            $this->getConnection()->truncateTable($this->getMainTable());
        }

        return $this;
    }
}
