<?php
/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model\Export\Filter;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Directory\Model\Config\Source\Allregion;

/**
 * @inheritdoc
 */
class RegionFilter extends AbstractSource
{
    /**
     * @var Allregion
     */
    private $regions;

    public function __construct(
        Allregion $regions
    ) {
        $this->regions = $regions;
    }
    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        return $this->regions->toOptionArray();
    }
}
