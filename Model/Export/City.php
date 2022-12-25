<?php
/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Deki\CustomerAddress\Model\Export;

use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\ImportExport\Model\Export\Factory as ExportFactory;
use Magento\ImportExport\Model\Export\AbstractEntity;
use Deki\CustomerAddress\Model\ResourceModel\City as CityResource;
use Deki\CustomerAddress\Model\ResourceModel\City\Collection as CityCollection;

use Deki\CustomerAddress\Model\Export\CityCollectionFactoryInterface;
use Deki\CustomerAddress\Model\Export\ColumnProviderInterface;

use Magento\Store\Model\StoreManagerInterface;
use Magento\ImportExport\Model\ResourceModel\CollectionByPagesIteratorFactory;

/**
 * Export customer address city
 */
class City extends AbstractEntity
{
    /**
     * @var AttributeCollectionProvider
     */
    private $attributeCollectionProvider;

    /**
     * @var CityCollectionFactoryInterface
     */
    private $cityCollectionFactory;

    /**
     * @var ColumnProviderInterface
     */
    private $columnProvider;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param ExportFactory $collectionFactory
     * @param CollectionByPagesIteratorFactory $resourceColFactory
     * @param AttributeCollectionProvider $attributeCollectionProvider
     * @param CityCollectionFactoryInterface $cityCollectionFactory
     * @param ColumnProviderInterface $columnProvider
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        ExportFactory $collectionFactory,
        CollectionByPagesIteratorFactory $resourceColFactory,
        AttributeCollectionProvider $attributeCollectionProvider,
        CityCollectionFactoryInterface $cityCollectionFactory,
        ColumnProviderInterface $columnProvider,
        array $data = []
    ) {
        $this->attributeCollectionProvider = $attributeCollectionProvider;
        $this->cityCollectionFactory = $cityCollectionFactory;
        $this->columnProvider = $columnProvider;
        parent::__construct($scopeConfig, $storeManager, $collectionFactory, $resourceColFactory, $data);
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function getAttributeCollection()
    {
        return $this->attributeCollectionProvider->get();
    }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    public function export()
    {
        $writer = $this->getWriter();
        $writer->setHeaderCols($this->_getHeaderColumns());

        /** @var CityCollection $collection */
        $collection = $this->cityCollectionFactory->create(
            $this->getAttributeCollection(),
            $this->_parameters
        );
        
        foreach ($collection->getData() as $data) {
            $writer->writeRow($data);
        }

        return $writer->getContents();
    }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    protected function _getHeaderColumns()
    {
        return $this->columnProvider->getHeaders($this->getAttributeCollection(), $this->_parameters);
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function exportItem($item)
    {
        // will not implement this method as it is legacy interface
    }

    /**
     * @inheritdoc
     */
    public function getEntityTypeCode()
    {
        return 'customer_address_city';
    }

    /**
     * @inheritdoc
     */
    protected function _getEntityCollection()
    {
        // will not implement this method as it is legacy interface
    }
}
