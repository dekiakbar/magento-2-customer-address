<?php
/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model\Export;

use Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Framework\Data\Collection;
use Magento\ImportExport\Model\Export\Factory as CollectionFactory;
use Deki\CustomerAddress\Api\Data\CityInterface;
use Deki\CustomerAddress\Model\Export\Filter\CountryFilter;
use Deki\CustomerAddress\Model\Export\Filter\RegionFilter;

/**
 * @api
 */
class AttributeCollectionProvider
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var AttributeFactory
     */
    private $attributeFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param AttributeFactory $attributeFactory
     * @throws \InvalidArgumentException
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        AttributeFactory $attributeFactory
    ) {
        $this->collection = $collectionFactory->create(Collection::class);
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * Get city collection
     *
     * @return Collection
     */
    public function get(): Collection
    {
        if (count($this->collection) === 0) {
            /** @var \Magento\Eav\Model\Entity\Attribute $cityIdAttribute */
            $cityIdAttribute = $this->attributeFactory->create();
            $cityIdAttribute->setId(CityInterface::CITY_ID);
            $cityIdAttribute->setDefaultFrontendLabel(
                __("ID")
            );
            $cityIdAttribute->setAttributeCode(CityInterface::CITY_ID);
            $cityIdAttribute->setBackendType('int');
            $this->collection->addItem($cityIdAttribute);

            /** @var \Magento\Eav\Model\Entity\Attribute $cityNameAttribute */
            $cityNameAttribute = $this->attributeFactory->create();
            $cityNameAttribute->setId(CityInterface::NAME);
            $cityNameAttribute->setDefaultFrontendLabel(
                ucfirst(CityInterface::NAME)
            );
            $cityNameAttribute->setAttributeCode(CityInterface::NAME);
            $cityNameAttribute->setBackendType('varchar');
            $this->collection->addItem($cityNameAttribute);

            /** @var \Magento\Eav\Model\Entity\Attribute $postCodeAttribute */
            $postCodeAttribute = $this->attributeFactory->create();
            $postCodeAttribute->setId(CityInterface::POSTCODE);
            $postCodeAttribute->setBackendType('int');
            $postCodeAttribute->setDefaultFrontendLabel(
                ucfirst(CityInterface::POSTCODE)
            );
            $postCodeAttribute->setAttributeCode(CityInterface::POSTCODE);
            $this->collection->addItem($postCodeAttribute);

            /** @var \Magento\Eav\Model\Entity\Attribute $countryIdAttribute */
            $countryIdAttribute = $this->attributeFactory->create();
            $countryIdAttribute->setId(CityInterface::COUNTRY_ID);
            $countryIdAttribute->setDefaultFrontendLabel(
                __("Country")
            );
            $countryIdAttribute->setAttributeCode(CityInterface::COUNTRY_ID);
            $countryIdAttribute->setBackendType('int');
            $countryIdAttribute->setFrontendInput('select');
            $countryIdAttribute->setSourceModel(CountryFilter::class);
            $this->collection->addItem($countryIdAttribute);

            /** @var \Magento\Eav\Model\Entity\Attribute $regionIdAttribute */
            $regionIdAttribute = $this->attributeFactory->create();
            $regionIdAttribute->setId(CityInterface::REGION_ID);
            $regionIdAttribute->setDefaultFrontendLabel(
                __("Region")
            );
            $regionIdAttribute->setAttributeCode(CityInterface::REGION_ID);
            $regionIdAttribute->setBackendType('int');
            $regionIdAttribute->setFrontendInput('select');
            $regionIdAttribute->setSourceModel(RegionFilter::class);
            $this->collection->addItem($regionIdAttribute);
        }

        return $this->collection;
    }
}
