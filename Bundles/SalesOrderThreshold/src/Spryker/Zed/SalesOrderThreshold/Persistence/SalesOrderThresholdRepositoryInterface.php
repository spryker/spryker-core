<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface SalesOrderThresholdRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function getSalesOrderThresholdTypeByKey(
        SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
    ): SalesOrderThresholdTypeTransfer;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer[]
     */
    public function getSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $orderThresholdTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer|null
     */
    public function findSalesOrderThreshold(
        SalesOrderThresholdTransfer $orderThresholdTypeTransfer
    ): ?SalesOrderThresholdTransfer;

    /**
     * @return int|null
     */
    public function findSalesOrderThresholdTaxSetId(): ?int;

    /**
     * @param string $countryIso2Code
     *
     * @return float|null
     */
    public function findMaxTaxRateByCountryIso2Code(string $countryIso2Code): ?float;
}
