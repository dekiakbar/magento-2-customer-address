<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model;

use Deki\CustomerAddress\Api\CityRepositoryInterface;
use Deki\CustomerAddress\Api\Data\CityInterfaceFactory;
use Deki\CustomerAddress\Api\Data\CitySearchResultsInterfaceFactory;
use Deki\CustomerAddress\Model\ResourceModel\City as ResourceCity;
use Deki\CustomerAddress\Model\ResourceModel\City\CollectionFactory as CityCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class CityRepository implements CityRepositoryInterface
{

    protected $dataObjectHelper;

    protected $cityFactory;

    protected $extensionAttributesJoinProcessor;

    protected $extensibleDataObjectConverter;
    protected $dataCityFactory;

    protected $resource;

    protected $cityCollectionFactory;

    protected $dataObjectProcessor;

    private $collectionProcessor;

    private $storeManager;

    protected $searchResultsFactory;


    /**
     * @param ResourceCity $resource
     * @param CityFactory $cityFactory
     * @param CityInterfaceFactory $dataCityFactory
     * @param CityCollectionFactory $cityCollectionFactory
     * @param CitySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceCity $resource,
        CityFactory $cityFactory,
        CityInterfaceFactory $dataCityFactory,
        CityCollectionFactory $cityCollectionFactory,
        CitySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->cityFactory = $cityFactory;
        $this->cityCollectionFactory = $cityCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCityFactory = $dataCityFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Deki\CustomerAddress\Api\Data\CityInterface $city
    ) {
        /* if (empty($city->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $city->setStoreId($storeId);
        } */
        
        $cityData = $this->extensibleDataObjectConverter->toNestedArray(
            $city,
            [],
            \Deki\CustomerAddress\Api\Data\CityInterface::class
        );
        
        $cityModel = $this->cityFactory->create()->setData($cityData);
        
        try {
            $this->resource->save($cityModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the city: %1',
                $exception->getMessage()
            ));
        }
        return $cityModel;
    }

    /**
     * {@inheritdoc}
     */
    public function get($cityId)
    {
        $city = $this->cityFactory->create();
        $this->resource->load($city, $cityId);
        if (!$city->getId()) {
            throw new NoSuchEntityException(__('city with id "%1" does not exist.', $cityId));
        }
        return $city;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->cityCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Deki\CustomerAddress\Api\Data\CityInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Deki\CustomerAddress\Api\Data\CityInterface $city
    ) {
        try {
            $cityModel = $this->cityFactory->create();
            $this->resource->load($cityModel, $city->getCityId());
            $this->resource->delete($cityModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the city: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($cityId)
    {
        return $this->delete($this->get($cityId));
    }
}

