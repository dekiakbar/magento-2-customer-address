<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\FileSystemException;

class Csv extends AbstractHelper
{
    const CSV_FOLDER = "Files";
    const ALLOWED_FILE_EXTENSION = ".csv";
    const COLUMN_INDEX_OF_COUNTRY_ID = 0;
    const COLUMN_INDEX_OF_REGION_CODE = 1;
    const COLUMN_INDEX_OF_REGION_NAME = 2;
    const COLUMN_INDEX_OF_CITY_NAME = 3;
    const COLUMN_INDEX_OF_POST_CODE = 4;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csv;

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $moduleReader;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $driverFile;

    /**
     * @var \Magento\Directory\Model\Region
     */
    protected $regionModel;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Region
     */
    protected $resourceRegion;

    /**
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $regionFactory;

    /**
     * @var \Deki\CustomerAddress\Api\Data\CityInterfaceFactory
     */
    protected $cityInterfaceFactory;
    
    /**
     * @var \Deki\CustomerAddress\Model\CityRepository
     */
    protected $cityRepository;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $fileSystemIo;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\File\Csv $csv
     * @param \Magento\Framework\Module\Dir\Reader $moduleReader
     * @param \Magento\Framework\Filesystem\Driver\File $driverFile
     * @param \Magento\Directory\Model\Region $regionModel
     * @param \Magento\Directory\Model\ResourceModel\Region $resourceRegion,
     * @param \Magento\Directory\Model\RegionFactory $regionFactory,
     * @param \Deki\CustomerAddress\Api\Data\CityInterfaceFactory $cityInterfaceFactory
     * @param \Deki\CustomerAddress\Model\CityRepository $cityRepository
     * @param \Magento\Framework\Filesystem\Io\File $fileSystemIo
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Magento\Directory\Model\Region $regionModel,
        \Magento\Directory\Model\ResourceModel\Region $resourceRegion,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Deki\CustomerAddress\Api\Data\CityInterfaceFactory $cityInterfaceFactory,
        \Deki\CustomerAddress\Model\CityRepository $cityRepository,
        \Magento\Framework\Filesystem\Io\File $fileSystemIo
    ) {
        parent::__construct($context);
        $this->csv = $csv;
        $this->moduleReader = $moduleReader;
        $this->driverFile = $driverFile;
        $this->regionModel = $regionModel;
        $this->resourceRegion = $resourceRegion;
        $this->regionFactory = $regionFactory;
        $this->cityInterfaceFactory = $cityInterfaceFactory;
        $this->cityRepository = $cityRepository;
        $this->fileSystemIo = $fileSystemIo;
    }

    /**
     * Get Root path for CSV file
     *
     * @return string
     */
    public function getFilesDirectory()
    {
        $viewDir = $this->moduleReader->getModuleDir(
            null,
            'Deki_CustomerAddress'
        );
        
        return $viewDir.'/'.self::CSV_FOLDER;
    }

    /**
     * Get full path for available region
     *
     * @return array
     */
    public function getFileFullPathList()
    {
        return $this->driverFile->readDirectory($this->getFilesDirectory());
    }

    /**
     * Get all available region csv file
     *
     * @return array
     * @throws \Exception
     */
    public function getRegionList()
    {
        $paths = $this->getFileFullPathList();
        $files = [];
        foreach ($paths as $path) {
            if (strpos(strtolower($path), self::ALLOWED_FILE_EXTENSION) !== false) {
                $partsOfPath = explode('/', str_replace('\\', '/', $path));
                $fileName = $partsOfPath[count($partsOfPath) - 1];
                $fileInfo = $this->fileSystemIo->getPathInfo($fileName);
                $files[] = $fileInfo['filename'];
            }
        }

        return $files;
    }

    /**
     * Check if region file exist
     *
     * @param array $requestedRegion
     * @param array $availableRegions
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validateRequestedRegions($requestedRegion, $availableRegions)
    {
        $availableRegions = $this->getRegionList();
        $unsupportedTypes = array_diff($requestedRegion, $availableRegions);
        if ($unsupportedTypes) {
            throw new LocalizedException(
                __(
                    "The following requested region are not supported: '" . join("', '", $unsupportedTypes)
                    . "'." . PHP_EOL . 'Supported types: ' . join(", ", $availableRegions)
                )
            );
        }

        return true;
    }
    
    /**
     * Is regions file exist in file system
     *
     * @param string $path
     * @return bool
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function isRegionFileExist($path)
    {
        $isFile = $this->driverFile->isFile($path);
        $isExist = $this->driverFile->isExists($path);
        if (!$isFile || !$isExist) {
            throw new LocalizedException(__("File not found."));
        }
        return true;
    }

    /**
     * Import region and city by region file name
     *
     * @param string $regionFileName
     * @return bool
     * @throws LocalizedException
     */
    public function importRegion($regionFileName)
    {
        if (!isset($regionFileName)) {
            throw new LocalizedException(__("Argument regionFileName is missing."));
        }
        $fullPathRegionFile = $this->getFilesDirectory()."/".$regionFileName.self::ALLOWED_FILE_EXTENSION;
        $this->isRegionFileExist($fullPathRegionFile);
        $csvDatas = $this->csv->getData($fullPathRegionFile);

        $regionSaveState = false;
        $lastRegionCode = '';
        foreach ($csvDatas as $row => $csvData) {
            if ($row > 0) {
                if ($lastRegionCode !== $csvData[self::COLUMN_INDEX_OF_REGION_CODE]) {
                    $regionSaveState = false;
                }

                if ($regionSaveState === false) {
                    $regionId = $this->saveRegion($csvData);
                    $regionSaveState = true;
                    $lastRegionCode = $csvData[self::COLUMN_INDEX_OF_REGION_CODE];
                }
                $regionId = $this->regionModel->loadByCode(
                    $csvData[self::COLUMN_INDEX_OF_REGION_CODE],
                    $csvData[self::COLUMN_INDEX_OF_COUNTRY_ID]
                )->getId();
                $this->saveCity($csvData, $regionId);
            }
        }

        return true;
    }

    /**
     * Save csv data to region
     *
     * @param array $csvData
     * @return string
     * @throws \Exception
     */
    public function saveRegion(array $csvData)
    {
        $regionModel = $this->regionFactory->create();
        $regionModel->setCountryId($csvData[self::COLUMN_INDEX_OF_COUNTRY_ID]);
        $regionModel->setCode($csvData[self::COLUMN_INDEX_OF_REGION_CODE]);
        $regionModel->setDefaultName($csvData[self::COLUMN_INDEX_OF_REGION_NAME]);
        $this->resourceRegion->save($regionModel);
        return $regionModel->getId();
    }

    /**
     * Save csv data to city table
     *
     * @param array $csvData
     * @param string $regionId
     * @return \Deki\CustomerAddress\Model\Data\City
     * @throws \Exception
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function saveCity(array $csvData, $regionId)
    {
        $cityObject = $this->cityInterfaceFactory->create();
        $cityObject->setCountryId($csvData[self::COLUMN_INDEX_OF_COUNTRY_ID]);
        $cityObject->setRegionId($regionId);
        $cityObject->setName($csvData[self::COLUMN_INDEX_OF_CITY_NAME]);
        $cityObject->setPostcode($csvData[self::COLUMN_INDEX_OF_POST_CODE]);
        return $this->cityRepository->save($cityObject);
    }
}
