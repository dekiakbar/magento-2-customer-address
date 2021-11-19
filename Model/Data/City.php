<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model\Data;

use Deki\CustomerAddress\Api\Data\CityInterface;

class City extends \Magento\Framework\Api\AbstractExtensibleObject implements CityInterface
{

    /**
     * Get city_id
     * @return string|null
     */
    public function getCityId()
    {
        return $this->_get(self::CITY_ID);
    }

    /**
     * Set city_id
     * @param string $cityId
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setCityId($cityId)
    {
        return $this->setData(self::CITY_ID, $cityId);
    }

    /**
     * Get region_code
     * @return string|null
     */
    public function getRegionCode()
    {
        return $this->_get(self::REGION_CODE);
    }

    /**
     * Set region_code
     * @param string $regionCode
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setRegionCode($regionCode)
    {
        return $this->setData(self::REGION_CODE, $regionCode);
    }

    /**
     * Get name
     * @return string|null
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get postcode
     * @return string|null
     */
    public function getPostcode()
    {
        return $this->_get(self::POSTCODE);
    }

    /**
     * Set postcode
     * @param string $postcode
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setPostcode($postcode)
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->_get(self::UPDATED_AT);
    }

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
