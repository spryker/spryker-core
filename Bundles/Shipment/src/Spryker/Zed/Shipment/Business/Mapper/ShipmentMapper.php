<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Mapper;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ShipmentFormTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class ShipmentMapper implements ShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentFormTransfer $shipmentFormTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function mapFormDataToShipmentTransfer(ShipmentFormTransfer $shipmentFormTransfer, ShipmentTransfer $shipmentTransfer): ShipmentTransfer
    {
        return $shipmentTransfer->fromArray($shipmentFormTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function mapShipmentMethodTransferToExpenseTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        ExpenseTransfer $expenseTransfer
    ): ExpenseTransfer {
        return $expenseTransfer->fromArray($shipmentMethodTransfer->modifiedToArray(), true);
    }
}
