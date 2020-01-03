<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\DataWriter\QueueWriter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataSetItemTransfer;
use Spryker\Zed\DataImport\Business\DataWriter\QueueWriter\QueueWriter;
use Spryker\Zed\DataImport\Business\DataWriter\QueueWriter\QueueWriterInterface;
use Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceBridge;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group DataWriter
 * @group QueueWriter
 * @group QueueWriterTest
 * Add your own group annotations below this line
 */
class QueueWriterTest extends Unit
{
    protected const DUMMY_QUEUE_NAME = 'dummy_queue';
    protected const PAYLOAD_DUMMY_DATA = ['dummy payload'];

    /**
     * @var \SprykerTest\Zed\DataImport\DataImportBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider getQueueWritableData
     *
     * @param int $expectedMessageCount
     * @param \Generated\Shared\Transfer\DataSetItemTransfer[] $dataSetItemTransfers
     *
     * @return void
     */
    public function testCanWriteDataToQueue(int $expectedMessageCount, array $dataSetItemTransfers): void
    {
        $queueWriter = $this->getQueueWriter($expectedMessageCount);
        $queueWriter->write(
            static::DUMMY_QUEUE_NAME,
            $dataSetItemTransfers
        );
    }

    /**
     * @return array
     */
    public function getQueueWritableData(): array
    {
        return [
            'one item with payload' => [1, [$this->getDataSetItemTransfer(static::PAYLOAD_DUMMY_DATA)]],
            'three items with payload' => [
                3,
                [
                    $this->getDataSetItemTransfer(static::PAYLOAD_DUMMY_DATA),
                    $this->getDataSetItemTransfer(static::PAYLOAD_DUMMY_DATA),
                    $this->getDataSetItemTransfer(static::PAYLOAD_DUMMY_DATA),
                ],
            ],
            'one item with no payload' => [0, [$this->getDataSetItemTransfer()]],
            'some items with no payload' => [
                2,
                [
                    $this->getDataSetItemTransfer(static::PAYLOAD_DUMMY_DATA),
                    $this->getDataSetItemTransfer(),
                    $this->getDataSetItemTransfer(static::PAYLOAD_DUMMY_DATA),
                ],
            ],
        ];
    }

    /**
     * @param int $chunkSize
     *
     * @return \Spryker\Zed\DataImport\Business\DataWriter\QueueWriter\QueueWriterInterface
     */
    protected function getQueueWriter(int $chunkSize): QueueWriterInterface
    {
        return new QueueWriter(
            $this->getQueueClientMock($chunkSize),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @param int $messageBufferCount
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface
     */
    protected function getQueueClientMock(int $messageBufferCount = 1)
    {
        $queueClientMock = $this->createMock(DataImportToQueueClientInterface::class);
        $queueClientMock->method('sendMessages')
            ->with(
                $this->stringContains(static::DUMMY_QUEUE_NAME),
                $this->callback(function ($messageBuffer) use ($messageBufferCount) {
                    return is_array($messageBuffer) && count($messageBuffer) === $messageBufferCount;
                })
            );

        return $queueClientMock;
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): DataImportToUtilEncodingServiceInterface
    {
        return new DataImportToUtilEncodingServiceBridge(
            $this->tester->getLocator()->utilEncoding()->service()
        );
    }

    /**
     * @param array|null $payload
     *
     * @return \Generated\Shared\Transfer\DataSetItemTransfer
     */
    protected function getDataSetItemTransfer(?array $payload = null): DataSetItemTransfer
    {
        return (new DataSetItemTransfer())->setPayload($payload);
    }
}
