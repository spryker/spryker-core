<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business\Reader;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class MerchantSalesOrderReader implements MerchantSalesOrderReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    public function isMerchantOrderShipment(
        MerchantOrderTransfer $merchantOrderTransfer,
        ShipmentTransfer $shipmentTransfer
    ): bool {
        foreach ($merchantOrderTransfer->getOrder()->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment()->getIdSalesShipment() !== $shipmentTransfer->getIdSalesShipment()) {
                return false;
            }
        }

        return true;
    }
}
