<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Business\Reader;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Business\Reader\MerchantSalesOrderReaderInterface;

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
