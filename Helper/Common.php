<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\AlreadyExistsException;

class Common extends AbstractHelper
{
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
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Directory\Model\Region $regionModel,
        \Magento\Directory\Model\ResourceModel\Region $resourceRegion,
        \Magento\Directory\Model\RegionFactory $regionFactory
    ) {
        $this->regionModel = $regionModel;
        $this->resourceRegion = $resourceRegion;
        $this->regionFactory = $regionFactory;
        parent::__construct($context);
    }

    /**
     * build region code based country id and region name
     *
     * @param string $countryId
     * @param string $regionName
     * @return string||false
     */
    public function buildRegionCodeFromName($countryId, $regionName)
    {
        if(empty($regionName) || empty($countryId)) return false;

        $words = preg_split("/\s+/", $regionName);
        $result = implode(
            "",
            array_map(
                function($word){
                    return strtoupper(ucwords(substr($word,0,1)));
                }
            ,$words)
        );

        return $countryId."-".$result;
    }

    /**
     * build region code based country id and region name
     *
     * @param string $countryId
     * @param string $regionCode
     * @param string $regionName
     * @return string
     * @throws LocalizedException
     * @throws AlreadyExistsException
     */
    public function saveNewRegion($countryId, $regionCode, $regionName)
    {
        if(!$countryId) throw new LocalizedException(__("Country Id is missing"));
        if(!$regionCode) throw new LocalizedException(__("Region Code is missing"));
        if(!$regionName) throw new LocalizedException(__("region Name is missing"));

        if($this->isRegionExistByCode($countryId, $regionCode)){
            throw new AlreadyExistsException(__("Region exist"));
        }

        $regionModel = $this->regionFactory->create();
        $regionModel->setCountryId($countryId);
        $regionModel->setCode($regionCode);
        $regionModel->setDefaultName($regionName);
        $this->resourceRegion->save($regionModel);
        return $regionModel->getId();
    }

    /**
     * check if region exist
     *
     * @param string $countryId
     * @param string $regionCode
     * @return boolean
     */
    public function isRegionExistByCode($countryId, $regionCode)
    {
        $region = $this->regionModel->loadByCode($regionCode, $countryId);
        if($region->getId()){
            return true;
        }
        return false;
    }
}

