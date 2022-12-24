<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Api\Data;

interface CityAutocompleteSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get city list.
     *
     * @return \Deki\CustomerAddress\Api\Data\CityAutocompleteInterface[]
     */
    public function getItems();

    /**
     * Set region_id list.
     *
     * @param \Deki\CustomerAddress\Api\Data\CityAutocompleteInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
