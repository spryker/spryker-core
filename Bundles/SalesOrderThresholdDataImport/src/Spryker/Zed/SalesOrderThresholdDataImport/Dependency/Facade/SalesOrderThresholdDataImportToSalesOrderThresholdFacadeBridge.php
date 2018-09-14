<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;

class SalesOrderThresholdDataImportToSalesOrderThresholdFacadeBridge implements SalesOrderThresholdDataImportToSalesOrderThresholdFacadeInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface
     */
    protected $salesOrderThresholdFacade;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface $salesOrderThresholdFacade
     */
    public function __construct($salesOrderThresholdFacade)
    {
        $this->salesOrderThresholdFacade = $salesOrderThresholdFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function saveSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer {
        return $this->salesOrderThresholdFacade->saveSalesOrderThreshold($salesOrderThresholdTransfer);
    }
}
