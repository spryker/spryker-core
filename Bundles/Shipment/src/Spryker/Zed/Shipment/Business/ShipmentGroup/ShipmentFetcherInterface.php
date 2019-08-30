<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentPriceTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ShipmentFetcherInterface
{
    /**
     * @param int $shipmentMethodId
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findActiveShipmentMethodWithPricesAndCarrierById(int $shipmentMethodId): ?ShipmentMethodTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentPriceTransfer|null
     */
    public function findMethodPriceByShipmentMethodAndCurrentStoreCurrency(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): ?ShipmentPriceTransfer;
}
