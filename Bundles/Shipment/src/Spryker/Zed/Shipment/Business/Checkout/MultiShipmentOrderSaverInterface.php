<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Checkout;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface MultiShipmentOrderSaverInterface extends ShipmentOrderSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function saveOrderShipmentByShipmentGroup(
        OrderTransfer $orderTransfer,
        ShipmentGroupTransfer $shipmentGroupTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): ShipmentGroupTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveSalesOrderShipment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;
}
