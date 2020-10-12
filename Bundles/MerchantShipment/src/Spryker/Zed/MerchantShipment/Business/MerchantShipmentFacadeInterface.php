<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Business;

use Generated\Shared\Transfer\ShipmentTransfer;

interface MerchantShipmentFacadeInterface
{
    /**
     * Specification:
     * - Returns true if at least one MerchantOrder.order.items.shipment.idSalesShipment is equal Shipment.idSalesShipment.
     *
     * @api
     *
     * @param string $merchantReference
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    public function isMerchantOrderShipment(
        string $merchantReference,
        ShipmentTransfer $shipmentTransfer
    ): bool;
}
