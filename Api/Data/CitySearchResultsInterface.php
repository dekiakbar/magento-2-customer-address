<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Api\Data;

interface CitySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get city list.
     * @return \Deki\CustomerAddress\Api\Data\CityInterface[]
     */
    public function getItems();

    /**
     * Set region_id list.
     * @param \Deki\CustomerAddress\Api\Data\CityInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
