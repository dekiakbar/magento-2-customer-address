<?php
/**
 * Copyright © Deki, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Test\Unit\Console\Command;

use Deki\CustomerAddress\Helper\Csv;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractRegionCommandTest extends TestCase
{
    /**
     * @var Csv|MockObject
     */
    protected $csvHelperMock;

    protected function setUp(): void
    {
        $this->csvHelperMock = $this->createMock(Csv::class);
    }

    /**
     * Formats expected output for testExecute data providers
     *
     * @param array $types
     * @return string
     */
    abstract public function getExpectedExecutionOutput(array $types);

    /**
     * @return array
     */
    public function executeDataProvider()
    {
        return [
            [
                [0 => 'A'],
            ]
        ];
    }
}
