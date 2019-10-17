<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\DataReader\QueueReader;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QueueReceiveMessageBuilder;
use Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group DataReader
 * @group QueueReader
 * @group QueueReaderTest
 * Add your own group annotations below this line
 */
class QueueReaderTest extends Unit
{
    protected const EXPECTED_NUMBER_OF_DATA_SETS_IN_QUEUE = 3;

    /**
     * @var \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    protected static $queueMessages = [];

    /**
     * @var \SprykerTest\Zed\DataImport\DataImportBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\DataImport\Business\DataReader\QueueReader\QueueReader
     */
    protected $queueReader;

    /**
     * @var int
     */
    protected $queueMessagePosition = 0;

    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        for ($i = 1; $i <= static::EXPECTED_NUMBER_OF_DATA_SETS_IN_QUEUE; ++$i) {
            static::$queueMessages[] = static::buildQueueReceiveMessage();
        }
    }

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(DataImportDependencyProvider::CLIENT_QUEUE, function () {
            return $this->createQueueClientMock();
        });

        $this->queueMessagePosition = 0;
    }

    /**
     * @return void
     */
    public function testDataReaderCanBeUsedAsIteratorAndReturnsArrayObject()
    {
        $queueReader = $this->getQueueReader();
        foreach ($queueReader as $dataSet) {
            $this->assertInstanceOf(DataSet::class, $dataSet);
        }
    }

    /**
     * @return void
     */
    public function testKeyReturnsCurrentDataSetPosition()
    {
        $csvReader = $this->getQueueReader();
        $this->assertInternalType('int', $csvReader->key());
    }

    /**
     * @param string|null $queueName
     * @param int $chunkSize
     * @param array $options
     *
     * @return array
     */
    public function receiveMessages(?string $queueName, int $chunkSize = 1, array $options = []): array
    {
        if ($this->queueMessagePosition >= static::EXPECTED_NUMBER_OF_DATA_SETS_IN_QUEUE) {
            return [];
        }

        $messageChunk = array_slice(static::$queueMessages, $this->queueMessagePosition, $chunkSize);
        $this->queueMessagePosition += $chunkSize;

        return $messageChunk;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Queue\QueueClientInterface
     */
    protected function createQueueClientMock()
    {
        $queueClientMock = $this->createMock(DataImportToQueueClientInterface::class);
        $queueClientMock->method('receiveMessages')
            ->willReturnCallback([$this, 'receiveMessages']);

        return $queueClientMock;
    }

    /**
     * @param int $chunkSize
     *
     * @return \Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer
     */
    protected function getQueueReaderConfigurationTransfer(int $chunkSize): DataImporterQueueReaderConfigurationTransfer
    {
        return (new DataImporterQueueReaderConfigurationTransfer())
            ->setChunkSize($chunkSize);
    }

    /**
     * @param int $chunkSize
     *
     * @return \Spryker\Zed\DataImport\Business\DataReader\QueueReader\QueueReader|\Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    protected function getQueueReader(int $chunkSize = 1)
    {
        $queueReaderConfigurationTransfer = $this->getQueueReaderConfigurationTransfer($chunkSize);

        return $this->tester->getFactory()->createQueueReaderFromConfig($queueReaderConfigurationTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected static function buildQueueReceiveMessage()
    {
        return (new QueueReceiveMessageBuilder())->build();
    }
}
