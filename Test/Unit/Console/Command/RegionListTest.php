<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Test\Unit\Console\Command;

use Deki\CustomerAddress\Console\Command\RegionList;
use Symfony\Component\Console\Tester\CommandTester;
use Deki\CustomerAddress\Test\Unit\Console\Command\AbstractRegionCommandTest;

class RegionListTest extends AbstractRegionCommandTest
{
    /**
     * Is called before running a test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->command = new RegionList($this->csvHelperMock);
    }

    /**
     * test php bin/magento customer-address:region-list
     * @param array $regionList
     * @dataProvider executeDataProvider
     */
    public function testExecute($regionList)
    {
        $this->csvHelperMock->expects($this->once())->method('getRegionList')->willReturn($regionList);
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $this->assertEquals($this->getExpectedExecutionOutput($regionList), $commandTester->getDisplay());
    }

    /**
     * {@inheritdoc}
     */
    public function getExpectedExecutionOutput(array $countryIds)
    {
        $output = 'Available CSV file :' . PHP_EOL;
        foreach ($countryIds as $countryId) {
            $output .= "- ".$countryId. PHP_EOL;
        }
        $output .= 'use available region list for import, example :' . PHP_EOL;
        $output .= 'php bin/magento customer-address:import-region ID' . PHP_EOL;
        
        return $output;
    }
}
