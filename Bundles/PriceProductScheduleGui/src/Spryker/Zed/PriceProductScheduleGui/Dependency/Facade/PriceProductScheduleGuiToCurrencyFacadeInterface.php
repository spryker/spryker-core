<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Dependency\Facade;

use Generated\Shared\Transfer\StoreWithCurrencyTransfer;

interface PriceProductScheduleGuiToCurrencyFacadeInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\StoreWithCurrencyTransfer>
     */
    public function getAllStoresWithCurrencies(): array;

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreWithCurrencyTransfer
     */
    public function getStoreWithCurrenciesByIdStore(int $idStore): StoreWithCurrencyTransfer;
}
