<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CityRepositoryInterface
{

    /**
     * Save city
     *
     * @param \Deki\CustomerAddress\Api\Data\CityInterface $city
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Deki\CustomerAddress\Api\Data\CityInterface $city
    );

    /**
     * Retrieve city
     *
     * @param string $cityId
     * @return \Deki\CustomerAddress\Api\Data\CityInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($cityId);

    /**
     * Retrieve city matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Deki\CustomerAddress\Api\Data\CitySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete city
     *
     * @param \Deki\CustomerAddress\Api\Data\CityInterface $city
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Deki\CustomerAddress\Api\Data\CityInterface $city
    );

    /**
     * Delete city by ID
     *
     * @param string $cityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($cityId);
}
