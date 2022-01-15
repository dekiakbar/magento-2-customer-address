<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Test\Unit\Console\Command;

use Deki\CustomerAddress\Console\Command\RegionImport;
use Symfony\Component\Console\Tester\CommandTester;
use Deki\CustomerAddress\Test\Unit\Console\Command\AbstractRegionCommandTest;

class RegionImportTest extends AbstractRegionCommandTest
{

    /**
     * Is called before running a test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->command = new RegionImport($this->csvHelperMock);
    }

    /**
     * test php bin/magento customer-address:region-import
     * @param array $regionList
     * @dataProvider executeDataProvider
     */
    public function testExecute($regionList)
    {
        $this->csvHelperMock->expects($this->once())
            ->method('getRegionList')
            ->willReturn($regionList);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'region' => $regionList,
        ]);

        $expectedOutputs = explode(
            PHP_EOL,
            $this->getExpectedExecutionOutput($regionList)
        );
        
        foreach ($expectedOutputs as $output) {
            $this->assertStringContainsString(
                $output,
                $commandTester->getDisplay()
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExpectedExecutionOutput(array $regionList)
    {
        $output = "";
        foreach ($regionList as $region) {
            $output .= 'Region and city with country code '.$region.' has been successfully imported in' . PHP_EOL;
        }
        $output .= "DONE!" . PHP_EOL;
        return $output;
    }
}
