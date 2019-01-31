<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataSet;

use Spryker\Zed\DataImport\Business\Exception\TransactionException;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface;

class DataSetStepBrokerTransactionAware extends DataSetStepBroker
{
    /**
     * @var \Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface
     */
    protected $propelConnection;

    /**
     * @var int|null
     */
    protected $bulkSize;

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface $propelConnection
     * @param int|null $bulkSize
     */
    public function __construct(DataImportToPropelConnectionInterface $propelConnection, $bulkSize = null)
    {
        $this->propelConnection = $propelConnection;
        $this->bulkSize = $bulkSize;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $this->beforeDataSetExecution();

        parent::execute($dataSet);

        $this->afterDataSetExecution();
    }

    /**
     * @return void
     */
    protected function beforeDataSetExecution()
    {
        if ($this->count === 0 && !$this->propelConnection->inTransaction()) {
            $this->propelConnection->beginTransaction();
        }

        if (!$this->bulkSize || ($this->count === $this->bulkSize)) {
            $this->count = 0;
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

        if (!$this->bulkSize || $this->bulkSize === $this->count) {
            $this->propelConnection->endTransaction();
            $this->count = 0;
        }
    }

    /**
     * Make sure that an opened transaction is always closed.
     */
    public function __destruct()
    {
        if ($this->count !== 0 && $this->propelConnection->inTransaction()) {
            $this->propelConnection->endTransaction();
        }
    }
}
