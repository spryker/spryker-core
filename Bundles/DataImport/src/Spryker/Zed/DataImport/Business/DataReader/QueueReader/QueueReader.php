<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataReader\QueueReader;

use Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface;

class QueueReader implements DataReaderInterface
{
    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    protected $messages = [];

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface
     */
    protected $queueClient;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected $dataSet;

    /**
     * @var \Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer
     */
    private $queueReaderConfigurationTransfer;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface $queueClient
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer $queueReaderConfigurationTransfer
     */
    public function __construct(
        DataImportToQueueClientInterface $queueClient,
        DataSetInterface $dataSet,
        DataImporterQueueReaderConfigurationTransfer $queueReaderConfigurationTransfer
    ) {
        $this->queueClient = $queueClient;
        $this->dataSet = $dataSet;
        $this->queueReaderConfigurationTransfer = $queueReaderConfigurationTransfer;
    }

    /**
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        if (!isset($this->messages[$this->position])) {
            $this->readFromQueue();
        }

        return isset($this->messages[$this->position]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->readFromQueue();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    public function current()
    {
        $currentMessage = $this->messages[$this->position];
        $this->dataSet->exchangeArray($currentMessage->toArray());

        return $this->dataSet;
    }

    /**
     * @return void
     */
    protected function readFromQueue(): void
    {
        $this->messages = [];
        $this->position = 0;
        $newChunk = $this->queueClient->receiveMessages($this->getQueueName(), $this->getChunkSize(), $this->getQueueConsumerOptions());

        if (!count($newChunk)) {
            return;
        }

        $this->messages = $newChunk;
    }

    /**
     * @return int|null
     */
    protected function getChunkSize(): ?int
    {
        return $this->queueReaderConfigurationTransfer->getChunkSize();
    }

    /**
     * @return string|null
     */
    protected function getQueueName(): ?string
    {
        return $this->queueReaderConfigurationTransfer->getQueueName();
    }

    /**
     * @return array
     */
    protected function getQueueConsumerOptions(): array
    {
        return $this->queueReaderConfigurationTransfer->getQueueConsumerOptions();
    }
}
