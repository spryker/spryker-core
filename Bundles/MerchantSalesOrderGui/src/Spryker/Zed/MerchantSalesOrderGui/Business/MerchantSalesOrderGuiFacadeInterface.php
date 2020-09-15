<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Business;

use Generated\Shared\Transfer\MerchantOrderTransfer;

interface MerchantSalesOrderGuiFacadeInterface
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
