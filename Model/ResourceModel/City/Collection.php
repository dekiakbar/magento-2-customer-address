<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model\ResourceModel\City;

use Magento\Framework\Exception\InvalidArgumentException;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'city_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Deki\CustomerAddress\Model\City::class,
            \Deki\CustomerAddress\Model\ResourceModel\City::class
        );
    }

    /**
     * Filter by query search and regionId
     *
     * @param string $query
     * @param int $regionId
     * @return $this
     * @throws InvalidArgumentException
     */
    public function searchByQueryRegionId($query, $regionId)
    {
        if (empty($query)) {
            throw new InvalidArgumentException(__("query can't be null"));
        }
        if (empty($regionId) || !is_numeric($regionId)) {
            throw new InvalidArgumentException(__("regionId can't be null and must be number"));
        }
        
        $this->addFieldToFilter('main_table.name', ["like" => "%".$query."%"]);
        $this->addFieldToFilter('main_table.region_id', ["eq" => $regionId]);
        
        return $this;
    }

    /**
     * Insert multiple city data
     *
     * @param array $cities
     * @return int
     * @throws InvalidArgumentException
     */
    public function insertMultiple($cities)
    {
        if (empty($cities)) {
            throw new InvalidArgumentException(__("can't insert NULL"));
        }

        return $this->getConnection()->insertMultiple($this->getMainTable(), $cities);
    }
}
