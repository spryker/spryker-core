<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Order;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;

/**
 * @deprecated Will be removed in next major release.
 */
class SalesOrderDataBCForMultiShipmentAdapter implements SalesOrderDataBCForMultiShipmentAdapterInterface
{
    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function adapt(OrderTransfer $orderTransfer): OrderTransfer
    {
        if ($this->assertThatItemTransfersHaveShipmentAndAddress($orderTransfer)) {
            return $orderTransfer;
        }

        if ($this->assertThatOrderHasNoAddressTransfer($orderTransfer)) {
            return $orderTransfer;
        }

        if ($this->assertThatOrderHasNoShipment($orderTransfer)) {
            return $orderTransfer;
        }

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($this->assertThatItemTransferHasShipment($itemTransfer)) {
                continue;
            }

            $this->setItemTransferShipmentAndShipmentAddressForBC($itemTransfer, $orderTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransfersHaveShipmentAndAddress(OrderTransfer $orderTransfer): bool
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null
                || $itemTransfer->getShipment()->getShippingAddress() === null
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function assertThatOrderHasNoAddressTransfer(OrderTransfer $orderTransfer): bool
    {
        return $orderTransfer->getShippingAddress() === null;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function assertThatOrderHasNoShipment(OrderTransfer $orderTransfer): bool
    {
        return $orderTransfer->getShipment() === null;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransferHasShipment(ItemTransfer $itemTransfer): bool
    {
        return ($itemTransfer->getShipment() !== null);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getShipmentTransferForBC(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): ShipmentTransfer
    {
        if ($itemTransfer->getShipment() !== null) {
            return $itemTransfer->getShipment();
        }

        return $orderTransfer->getShipment();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getShipmentAddressTransferForBC(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): AddressTransfer
    {
        if ($itemTransfer->getShipment()->getShippingAddress() !== null) {
            return $itemTransfer->getShipment()->getShippingAddress();
        }

        return $orderTransfer->getShippingAddress();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $orderExpenseTransfer
     *
     * @return void
     */
    protected function setItemTransferShipmentAndShipmentAddressForBC(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): void
    {
        $shipmentTransfer = $this->getShipmentTransferForBC($itemTransfer, $orderTransfer);
        $itemTransfer->setShipment($shipmentTransfer);

        $shippingAddressTransfer = $this->getShipmentAddressTransferForBC($itemTransfer, $orderTransfer);
        $shipmentTransfer->setShippingAddress($shippingAddressTransfer);
    }
}
