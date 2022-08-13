<?php
/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model\Export\Filter;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Directory\Model\Config\Source\Country;

/**
 * @inheritdoc
 */
class CountryFilter extends AbstractSource
{
    /**
     * @var Country
     */
    private $country;

    public function __construct(
        Country $country
    ) {
        $this->country = $country;
    }
    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        return $this->country->toOptionArray();
    }
}
