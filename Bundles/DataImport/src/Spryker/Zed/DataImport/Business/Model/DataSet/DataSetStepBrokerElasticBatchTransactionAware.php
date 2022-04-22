<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataSet;

use Spryker\Zed\DataImport\Business\Exception\DataSetBrokerTransactionFailedException;
use Spryker\Zed\DataImport\Business\Exception\TransactionException;
use Spryker\Zed\DataImport\Business\Model\ElasticBatch\ElasticBatchInterface;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface;
use Throwable;

class DataSetStepBrokerElasticBatchTransactionAware extends DataSetStepBroker
{
    /**
     * @var \Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface
     */
    protected $propelConnection;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\ElasticBatch\ElasticBatchInterface
     */
    protected $memoryAllocatedElasticBatch;

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface $propelConnection
     * @param \Spryker\Zed\DataImport\Business\Model\ElasticBatch\ElasticBatchInterface $memoryAllocatedElasticBatch
     */
    public function __construct(
        DataImportToPropelConnectionInterface $propelConnection,
        ElasticBatchInterface $memoryAllocatedElasticBatch
    ) {
        $this->propelConnection = $propelConnection;
        $this->memoryAllocatedElasticBatch = $memoryAllocatedElasticBatch;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataSetBrokerTransactionFailedException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $this->beforeDataSetExecution();

        try {
            parent::execute($dataSet);
        } catch (Throwable $exception) {
            $this->rollbackTransaction();

            throw new DataSetBrokerTransactionFailedException($this->count, $exception->getMessage(), $exception->getCode(), $exception);
        }

        $this->afterDataSetExecution();
    }

    /**
     * @return void
     */
    protected function rollbackTransaction(): void
    {
        $this->propelConnection->rollBack();
        $this->memoryAllocatedElasticBatch->reset();
    }

    /**
     * @return void
     */
    protected function beforeDataSetExecution()
    {
        if (!$this->propelConnection->inTransaction()) {
            $this->propelConnection->beginTransaction();
        }
    }

    /**
     * @throws \Spryker\Zed\DataImport\Business\Exception\TransactionException
     *
     * @return void
     */
    protected function afterDataSetExecution()
    {
        $this->count++;

        if (!$this->propelConnection->inTransaction()) {
            throw new TransactionException('There is no opened transaction.');
        }

        if ($this->memoryAllocatedElasticBatch->isFull()) {
            $this->endTransaction();
        }
    }

    /**
     * @return void
     */
    protected function endTransaction(): void
    {
        $this->propelConnection->endTransaction();
        $this->memoryAllocatedElasticBatch->reset();
        $this->count = 0;
    }

    /**
     * Make sure that an opened transaction is always closed.
     */
    public function __destruct()
    {
        if ($this->propelConnection->inTransaction()) {
            $this->endTransaction();
        }
    }
}
