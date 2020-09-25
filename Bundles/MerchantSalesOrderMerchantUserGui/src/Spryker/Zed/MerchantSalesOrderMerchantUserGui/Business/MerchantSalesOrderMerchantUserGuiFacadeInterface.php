<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

interface MerchantSalesOrderMerchantUserGuiFacadeInterface
{
    /**
     * Specification:
     * - Return false if MerchantOrder.order.items.shipment.IdSalesShipment not equal Shipment.IdSalesShipment
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    public function isMerchantOrderShipment(
        MerchantOrderTransfer $merchantOrderTransfer,
        ShipmentTransfer $shipmentTransfer
    ): bool;
}
