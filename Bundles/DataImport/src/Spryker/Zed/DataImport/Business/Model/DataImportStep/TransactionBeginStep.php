<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface;

class TransactionBeginStep implements DataImportStepInterface
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
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        if ($this->count === 0 && !$this->propelConnection->inTransaction()) {
            $this->propelConnection->beginTransaction();
        }
        $this->count++;

        if (!$this->bulkSize || ($this->count === $this->bulkSize)) {
            $this->count = 0;
        }
    }

}
