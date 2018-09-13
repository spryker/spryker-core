<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;

interface SalesOrderThresholdDataImportToSalesOrderThresholdFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function saveSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer;
}
