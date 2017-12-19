<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ReservationInterface
{
    /**
     * @param string $sku
     * @param null|string $storeName
     *
     * @return void
     */
    public function updateReservationQuantity($sku, $storeName = null);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku, StoreTransfer $storeTransfer = null);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getOmsReservedProductQuantityForSku($sku, StoreTransfer $storeTransfer);

    /**
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     *
     * @return void
     */
    public function saveReservationRequest(OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer);
}
