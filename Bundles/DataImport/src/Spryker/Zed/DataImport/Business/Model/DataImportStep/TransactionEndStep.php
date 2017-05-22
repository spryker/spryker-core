<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

use Spryker\Zed\DataImport\Business\Exception\TransactionException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface;

class TransactionEndStep implements DataImportStepInterface
{

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface
     */
    protected $propelConnection;

    /**
     * @var null|int
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
     * @throws \Spryker\Zed\DataImport\Business\Exception\TransactionException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $this->count++;

        if (!$this->propelConnection->inTransaction()) {
            throw new TransactionException(sprintf(
                'There is no opened transaction. Make sure that your bulk size in "%s" and "%s" equals and both classes are added to your "%s".',
                TransactionBeginStep::class,
                TransactionEndStep::class,
                DataSetInterface::class
            ));
        }

        if (!$this->bulkSize || $this->bulkSize === $this->count) {
            $this->propelConnection->endTransaction();
            $this->count = 0;
        }
    }

    /**
     * Make sure that a opened transaction is always closed.
     */
    public function __destruct()
    {
        if ($this->count !== 0 && $this->propelConnection->inTransaction()) {
            $this->propelConnection->endTransaction();
        }
    }

}
