<?php
/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model\Export\Filter;

use Deki\CustomerAddress\Model\ResourceModel\City\Collection;
use Deki\CustomerAddress\Model\Export\FilterProcessorInterface;

/**
 * @inheritdoc
 */
class VarcharFilter implements FilterProcessorInterface
{
    /**
     * Process filter
     *
     * @param Collection $collection
     * @param string $columnName
     * @param array|string $value
     *
     * @return void
     */
    public function process(Collection $collection, string $columnName, $value): void
    {
        $collection->addFieldToFilter($columnName, ['like' => '%' . $value . '%']);
    }
}
