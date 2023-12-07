<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\ElasticBatch;

use Codeception\Test\Unit;
use Spryker\Zed\DataImport\Business\Model\ElasticBatch\ElasticBatchInterface;
use Spryker\Zed\DataImport\Business\Model\ElasticBatch\MemoryAllocatedElasticBatch;
use Spryker\Zed\DataImport\Business\Model\Memory\PhpSystemMemory;
use Spryker\Zed\DataImport\DataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ElasticBatch
 * @group MemoryAllocatedElasticBatchTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\DataImportBusinessTester $tester
 */
class MemoryAllocatedElasticBatchTest extends Unit
{
    /**
     * @return void
     */
    public function testSumOfBatchesAndItemsAreCorrect(): void
    {
        //Arrange
        $bulkWriteGradualityFactor = 5;
        $memoryTresholdPercent = 80;
        $dataImportConfigMock = $this->getConfigMock($bulkWriteGradualityFactor, $memoryTresholdPercent);

        $megaByte = 1024 * 1024;
        $phpSystemMemoryMock = $this->getPhpSystemMemoryMock($megaByte * 1024);

        $phpSystemMemoryMock->method('getCurrentMemoryUsage')
            ->will($this->onConsecutiveCalls(
            // Each new line means new batch on items started
                // phpcs:ignore
                $megaByte * 10, $megaByte * 20, $megaByte * 30,
                $megaByte * 110,
                $megaByte * 120,
                // phpcs:ignore
                $megaByte * 130, $megaByte * 210,
                // phpcs:ignore
                $megaByte * 220, $megaByte * 450,
                $megaByte * 470,
            ));

        $phpSystemMemoryMock->method('getMemoryUsagePeak')
            ->will($this->onConsecutiveCalls(
            // Each new line means new batch on items started
                // phpcs:ignore
                $megaByte * 35, $megaByte * 70, $megaByte * 105,
                $megaByte * 385,
                $megaByte * 420,
                // phpcs:ignore
                $megaByte * 455, $megaByte * 735,
                // phpcs:ignore
                $megaByte * 770, $megaByte * 805,
                $megaByte * 900,
            ));

        $memoryAllocatedElasticBatch = $this->createMemoryAllocatedElasticBatch(
            $phpSystemMemoryMock,
            $dataImportConfigMock,
        );

        //Act
        $batches = 0;
        for ($i = 0; $i <= 9; $i++) {
            if ($memoryAllocatedElasticBatch->isFull()) {
                $batches++;
                $memoryAllocatedElasticBatch->reset();
            }
        }

        //Assert
        $this->assertEquals(6, $batches);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\Memory\PhpSystemMemory $phpSystemMemoryMock
     * @param \Spryker\Zed\DataImport\DataImportConfig $dataImportConfig
     *
     * @return \Spryker\Zed\DataImport\Business\Model\ElasticBatch\ElasticBatchInterface
     */
    protected function createMemoryAllocatedElasticBatch(
        PhpSystemMemory $phpSystemMemoryMock,
        DataImportConfig $dataImportConfig
    ): ElasticBatchInterface {
        return new MemoryAllocatedElasticBatch(
            $phpSystemMemoryMock,
            $dataImportConfig,
        );
    }

    /**
     * @param int $bulkWriteGradualityFactor
     * @param int $memoryThresholdPercent
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\DataImportConfig
     */
    protected function getConfigMock(int $bulkWriteGradualityFactor, int $memoryThresholdPercent): DataImportConfig
    {
        $mocked = $this->getMockBuilder(DataImportConfig::class)
            ->getMock();

        $mocked->method('getBulkWriteGradualityFactor')
            ->willReturn($bulkWriteGradualityFactor);

        $mocked->method('getBulkWriteMemoryThresholdPercent')
            ->willReturn($memoryThresholdPercent);

        return $mocked;
    }

    /**
     * @param int $systemMemoryLimit
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Business\Model\Memory\PhpSystemMemory
     */
    protected function getPhpSystemMemoryMock(int $systemMemoryLimit): PhpSystemMemory
    {
        $mocked = $this->getMockBuilder(PhpSystemMemory::class)
            ->getMock();

        $mocked->method('getMemoryLimit')
            ->willReturn($systemMemoryLimit);

        return $mocked;
    }
}
