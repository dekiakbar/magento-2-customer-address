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
use Magento\Framework\App\Helper\Context;
use Magento\Directory\Model\Region;
use Magento\Directory\Model\ResourceModel\Region as ResourceRegion;
use Magento\Directory\Model\RegionFactory;

class Common extends AbstractHelper
{
    /**
     * @var Region
     */
    protected $regionModel;
    
    /**
     * @var ResourceRegion
     */
    protected $resourceRegion;

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Region $regionModel
     * @param ResourceRegion $resourceRegion
     * @param RegionFactory $regionFactory
     */
    public function __construct(
        Context $context,
        Region $regionModel,
        ResourceRegion $resourceRegion,
        RegionFactory $regionFactory
    ) {
        $this->regionModel = $regionModel;
        $this->resourceRegion = $resourceRegion;
        $this->regionFactory = $regionFactory;
        parent::__construct($context);
    }

    /**
     * Build region code based country id and region name
     *
     * @param string $countryId
     * @param string $regionName
     * @return string||false
     */
    public function buildRegionCodeFromName($countryId, $regionName)
    {
        if (empty($regionName) || empty($countryId)) {
            return false;
        }

        $words = preg_split("/\s+/", $regionName);
        
        $codeLength = 3;

        if (count($words) > 1) {
            $codeLength = 3;
        }

        $result = implode(
            "",
            array_map(
                function ($word) use ($codeLength) {
                    return strtoupper(
                        ucwords(
                            substr($word, 0, $codeLength)
                        )
                    );
                },
                $words
            )
        );

        return $countryId."-".$result;
    }

    /**
     * Save new region
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
        if (!$countryId) {
            throw new LocalizedException(__("Country Id is missing"));
        }
        if (!$regionCode) {
            throw new LocalizedException(__("Region Code is missing"));
        }
        if (!$regionName) {
            throw new LocalizedException(__("region Name is missing"));
        }

        if ($this->isRegionExistByCode($countryId, $regionCode)) {
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
     * Check if region exist
     *
     * @param string $countryId
     * @param string $regionCode
     * @return boolean
     */
    public function isRegionExistByCode($countryId, $regionCode)
    {
        $region = $this->regionFactory->create()->loadByCode($regionCode, $countryId);
        if ($region->getId()) {
            return true;
        }
        return false;
    }

    /**
     * Check if region exist
     *
     * @param string $regionId
     * @return boolean
     */
    public function isRegionExist($regionId)
    {
        $region = $this->regionFactory->create()->load($regionId);
        if ($region->getId()) {
            return true;
        }
        return false;
    }
}
