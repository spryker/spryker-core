<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ReservationInterface
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateReservationQuantity($sku);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $reservationQuantity
     *
     * @return void
     */
    public function saveReservation(string $sku, StoreTransfer $storeTransfer, int $reservationQuantity): void;

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku, ?StoreTransfer $storeTransfer = null);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getOmsReservedProductQuantityForSku($sku, StoreTransfer $storeTransfer);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStoreTransfer
     *
     * @return int
     */
    public function getReservationsFromOtherStores($sku, StoreTransfer $currentStoreTransfer);

    /**
     * @return string[]
     */
    public function getReservedStateNames(): array;

    /**
     * @return \Generated\Shared\Transfer\OmsStateCollectionTransfer
     */
    public function getReservedStates(): OmsStateCollectionTransfer;
}
