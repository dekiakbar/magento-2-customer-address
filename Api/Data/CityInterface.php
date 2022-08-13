<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Api\Data;

interface CityInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /** @var string */
    public const COUNTRY_ID = 'country_id';
    
    /** @var string */
    public const UPDATED_AT = 'updated_at';
    
    /** @var string */
    public const CREATED_AT = 'created_at';
    
    /** @var string */
    public const REGION_ID = 'region_id';
    
    /** @var string */
    public const CITY_ID = 'city_id';
    
    /** @var string */
    public const POSTCODE = 'postcode';
    
    /** @var string */
    public const NAME = 'name';

    /**
     * Get city_id
     *
     * @return string|null
     */
    public function getCityId();

    /**
     * Set city_id
     *
     * @param string $cityId
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setCityId($cityId);

    /**
     * Get region_id
     *
     * @return string|null
     */
    public function getRegionId();

    /**
     * Set region_id
     *
     * @param string $regionId
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setRegionId($regionId);

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setName($name);

    /**
     * Get postcode
     *
     * @return string|null
     */
    public function getPostcode();

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setPostcode($postcode);

    /**
     * Get updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     *
     * @param string $updatedAt
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get created_at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     *
     * @param string $createdAt
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get country_id
     *
     * @return string|null
     */
    public function getCountryId();

    /**
     * Set country_id
     *
     * @param string $countryId
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     */
    public function setCountryId($countryId);
}
