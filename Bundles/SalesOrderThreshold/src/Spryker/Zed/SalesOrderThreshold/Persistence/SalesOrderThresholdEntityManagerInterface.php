<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Persistence;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;

interface SalesOrderThresholdEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function saveSalesOrderThresholdType(SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer): SalesOrderThresholdTypeTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function saveSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return bool
     */
    public function deleteSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): bool;

    /**
     * @param int $idTaxSet
     *
     * @return void
     */
    public function saveSalesOrderThresholdTaxSet(int $idTaxSet): void;
}
