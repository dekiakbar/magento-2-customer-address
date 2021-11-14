<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Api\Data;

interface CityInterface
{

    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';
    const CITY_ID = 'city_id';
    const REGION_CODE = 'region_code';
    const POSTCODE = 'postcode';
    const NAME = 'name';

    /**
     * Get city_id
     * @return string|null
     */
    public function getCityId();

    /**
     * Set city_id
     * @param string $cityId
     * @return \Api\Data\CityInterface
     */
    public function setCityId($cityId);

    /**
     * Get region_code
     * @return string|null
     */
    public function getRegionCode();

    /**
     * Set region_code
     * @param string $regionCode
     * @return \Api\Data\CityInterface
     */
    public function setRegionCode($regionCode);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Api\Data\CityInterface
     */
    public function setName($name);

    /**
     * Get postcode
     * @return string|null
     */
    public function getPostcode();

    /**
     * Set postcode
     * @param string $postcode
     * @return \Api\Data\CityInterface
     */
    public function setPostcode($postcode);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Api\Data\CityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Api\Data\CityInterface
     */
    public function setCreatedAt($createdAt);
}

