<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Test\Unit;

use Deki\CustomerAddress\Helper\Common;
use Magento\Framework\App\Helper\Context;
use Magento\Directory\Model\Region;
use Magento\Directory\Model\ResourceModel\Region as ResourceRegion;
use Magento\Directory\Model\RegionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class CommonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Common
     */
    protected $helper;

    /**
     * @var RegionFactory|MockObject
     */
    protected $regionFactoryMock;

    /**
     * Is called before running a test
     */
    protected function setUp(): void
    {
        $this->regionFactoryMock = $this->createMock(RegionFactory::class);
        $objectManager = new ObjectManager($this);
        $country = $objectManager->getObject(Region::class, []);
        $this->regionFactoryMock->method('create')->willReturn($country);

        $this->helper = new Common(
            $this->createMock(Context::class),
            $this->createMock(Region::class),
            $this->createMock(ResourceRegion::class),
            $this->createMock(RegionFactory::class),
        );
    }

    /**
     * build region code based country id and region name test
     * @param string $countryId
     * @param string $regionName
     * @param string $result
     * @dataProvider buildRegionCodeFromNameDataProvider
     */
    public function testBuildRegionCodeFromName($countryId, $regionName, $result)
    {
        $regionCode = $this->helper->buildRegionCodeFromName($countryId, $regionName);
        $this->assertEquals($result, $regionCode);
    }

    /**
     * @return array
     */
    public function buildRegionCodeFromNameDataProvider()
    {
        return [
            ["ID","Sukabumi Barat","ID-SB"],
            ["ID","Bandung Barat","ID-BB"],
            ["ID","Kalimantan Selatan","ID-KS"],
        ];
    }
}
