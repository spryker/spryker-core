<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataSetItemTransfer;
use ReflectionProperty;
use Spryker\Zed\DataImport\Business\DataImportFacade;
use Spryker\Zed\DataImport\Communication\Plugin\AbstractQueueWriterPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group AbstractQueueWriterPluginTest
 * Add your own group annotations below this line
 */
class AbstractQueueWriterPluginTest extends Unit
{
    protected const DUMMY_QUEUE_NAME = 'dummy_queue';

    /**
     * @dataProvider getBufferTestData
     *
     * @param int $expectedNumberOfFacadeCalls
     * @param int $configChunkSize
     * @param int $collectionSize
     *
     * @return void
     */
    public function testPluginCanBufferDataItems(int $expectedNumberOfFacadeCalls, int $configChunkSize, int $collectionSize): void
    {
        $this->resetAbstractQueueDataWriterPluginBuffer();
        $dataSetItemTransferCollection = $this->getDataSetItemTransferCollection($collectionSize);
        $abstractQueueDataWriterPluginMock = $this->getAbstractQueueDataWriterPluginMock($configChunkSize);
        $queueDataWriterFacadeMock = $this->getDataImportFacadeMock($configChunkSize, $expectedNumberOfFacadeCalls);
        $abstractQueueDataWriterPluginMock->setFacade($queueDataWriterFacadeMock);

        foreach ($dataSetItemTransferCollection as $dataSetItemTransfer) {
            $abstractQueueDataWriterPluginMock->write($dataSetItemTransfer);
        }

        $this->resetAbstractQueueDataWriterPluginBuffer();
    }

    /**
     * @return void
     */
    public function testBufferIsFlushed(): void
    {
        $configChunkSize = 3;
        $expectedChunkSizeToWrite = 1;
        $expectedNumberOfFacadeCalls = 1;

        $this->resetAbstractQueueDataWriterPluginBuffer();
        $abstractQueueDataWriterPluginMock = $this->getAbstractQueueDataWriterPluginMock($configChunkSize);
        $queueDataWriterFacadeMock = $this->getDataImportFacadeMock($expectedChunkSizeToWrite, $expectedNumberOfFacadeCalls);
        $abstractQueueDataWriterPluginMock->setFacade($queueDataWriterFacadeMock);

        $abstractQueueDataWriterPluginMock->write(new DataSetItemTransfer());
        $abstractQueueDataWriterPluginMock->flush();
    }

    /**
     * @return array
     */
    public function getBufferTestData(): array
    {
        return [
            [1, 1, 1],
            [3, 3, 10],
            [0, 3, 2],
        ];
    }

    /**
     * @param int $chunkSize
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Communication\Plugin\AbstractQueueWriterPlugin
     */
    protected function getAbstractQueueDataWriterPluginMock(int $chunkSize)
    {
        $abstractQueueDataWriterPluginMock = $this->getMockForAbstractClass(AbstractQueueWriterPlugin::class);
        $abstractQueueDataWriterPluginMock->method('getQueueName')->willReturn(static::DUMMY_QUEUE_NAME);
        $abstractQueueDataWriterPluginMock->method('getChunkSize')->willReturn($chunkSize);

        return $abstractQueueDataWriterPluginMock;
    }

    /**
     * @param int $expectedChunkSizeToWrite
     * @param int $expectedNumberOfFacadeCalls
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Business\DataImportFacade
     */
    protected function getDataImportFacadeMock(int $expectedChunkSizeToWrite, int $expectedNumberOfFacadeCalls)
    {
        $queueDataWriterFacadeMock = $this->createMock(DataImportFacade::class);
        $queueDataWriterFacadeMock->expects($this->exactly($expectedNumberOfFacadeCalls))
            ->method('writeDataSetItemsToQueue')
            ->with(
                $this->equalTo(static::DUMMY_QUEUE_NAME),
                $this->callback(function (array $dataItemTransfers) use ($expectedChunkSizeToWrite) {
                    return is_array($dataItemTransfers) && count($dataItemTransfers) === $expectedChunkSizeToWrite;
                })
            );

        return $queueDataWriterFacadeMock;
    }

    /**
     * @param int $collectionSize
     *
     * @return \Generated\Shared\Transfer\DataSetItemTransfer[]
     */
    protected function getDataSetItemTransferCollection(int $collectionSize): array
    {
        $dataSetItemTransferCollection = [];

        for ($i = 1; $i <= $collectionSize; ++$i) {
            $dataSetItemTransferCollection[] = new DataSetItemTransfer();
        }

        return $dataSetItemTransferCollection;
    }

    /**
     * @return void
     */
    protected function resetAbstractQueueDataWriterPluginBuffer(): void
    {
        $reflection = new ReflectionProperty(AbstractQueueWriterPlugin::class, 'dataSetItemBuffer');
        $reflection->setAccessible(true);
        $reflection->setValue(null, []);
    }
}
