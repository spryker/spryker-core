<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface CurrencyEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function createCurrency(CurrencyTransfer $currencyTransfer): CurrencyTransfer;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<\Generated\Shared\Transfer\CurrencyTransfer> $currencyTransfers
     *
     * @return void
     */
    public function updateCurrencyStores(StoreTransfer $storeTransfer, array $currencyTransfers): void;

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function updateStoreDefaultCurrency(CurrencyTransfer $currencyTransfer, StoreTransfer $storeTransfer): void;
}
