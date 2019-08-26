<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface ProductPackagingUnitToOmsFacadeInterface
{
    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku(string $sku, StoreTransfer $storeTransfer): int;

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $reservationQuantity
     *
     * @return void
     */
    public function saveReservation(string $sku, StoreTransfer $storeTransfer, int $reservationQuantity): void;

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface[]
     */
    public function getReservedStates(): array;
}
