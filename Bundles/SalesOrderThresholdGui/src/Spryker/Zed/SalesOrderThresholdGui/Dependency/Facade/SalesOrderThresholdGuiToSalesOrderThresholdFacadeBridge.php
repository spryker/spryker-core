<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class SalesOrderThresholdGuiToSalesOrderThresholdFacadeBridge implements SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface
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

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return bool
     */
    public function deleteSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): bool {
        return $this->salesOrderThresholdFacade->deleteSalesOrderThreshold($salesOrderThresholdTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer[]
     */
    public function getSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        return $this->salesOrderThresholdFacade->getSalesOrderThresholds($storeTransfer, $currencyTransfer);
    }

    /**
     * @return int|null
     */
    public function findSalesOrderThresholdTaxSetId(): ?int
    {
        return $this->salesOrderThresholdFacade->findSalesOrderThresholdTaxSetId();
    }

    /**
     * @param int $idTaxSet
     *
     * @return void
     */
    public function saveSalesOrderThresholdTaxSet(int $idTaxSet): void
    {
        $this->salesOrderThresholdFacade->saveSalesOrderThresholdTaxSet($idTaxSet);
    }
}
