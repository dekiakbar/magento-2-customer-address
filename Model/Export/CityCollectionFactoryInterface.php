<?php
/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model\Export;

use Magento\Framework\Exception\LocalizedException;
use Deki\CustomerAddress\Model\ResourceModel\City\Collection;
use Magento\Framework\Data\Collection as AttributeCollection;

/**
 * @api
 */
interface CityCollectionFactoryInterface
{
    /**
     * SourceItemCollection is used to gather all the data (with filters applied) which need to be exported
     *
     * @param AttributeCollection $attributeCollection
     * @param array $filters
     * @return Collection
     * @throws LocalizedException
     */
    public function create(AttributeCollection $attributeCollection, array $filters): Collection;
}
