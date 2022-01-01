<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model;

use Deki\CustomerAddress\Api\Data\CityInterface;

class City extends \Magento\Framework\Model\AbstractModel implements CityInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Deki\CustomerAddress\Model\ResourceModel\City::class);
    }

    /**
     * Get city_id
     * @return string|null
     */
    public function getCityId()
    {
        return $this->_getData(self::CITY_ID);
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
     * Get region_id
     * @return string|null
     */
    public function getRegionId()
    {
        return $this->_getData(self::REGION_ID);
    }

    /**
     * Set region_id
     * @param string $regionId
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setRegionId($regionId)
    {
        return $this->setData(self::REGION_ID, $regionId);
    }

    /**
     * Get name
     * @return string|null
     */
    public function getName()
    {
        return $this->_getData(self::NAME);
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
        return $this->_getData(self::POSTCODE);
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
        return $this->_getData(self::UPDATED_AT);
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
        return $this->_getData(self::CREATED_AT);
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

    /**
     * Get country_id
     * @return string|null
     */
    public function getCountryId()
    {
        return $this->_getData(self::COUNTRY_ID);
    }

    /**
     * Set country_id
     * @param string $countryId
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setCountryId($countryId)
    {
        return $this->setData(self::COUNTRY_ID, $countryId);
    }
}
