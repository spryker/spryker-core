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
        if ($this->assertThatItemTransfersHaveShipmentAndShipmentExpenseAndAddress($orderTransfer)) {
            return $orderTransfer;
        }

        if ($this->assertThatOrderHasNoAddressTransfer($orderTransfer)) {
            return $orderTransfer;
        }

        if ($this->assertThatOrderHasNoShipment($orderTransfer)) {
            return $orderTransfer;
        }

        $orderExpenseTransfer = $this->findOrderShipmentExpense($orderTransfer);
        if ($orderExpenseTransfer === null) {
            return $orderTransfer;
        }

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($this->assertThatItemTransferHasShipmentWithShipmentExpense($itemTransfer)) {
                continue;
            }

            $this->setItemTransferShipmentAndShipmentAddressAndShipmentExpenseForBC($itemTransfer, $orderTransfer, $orderExpenseTransfer);
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
    protected function assertThatItemTransfersHaveShipmentAndShipmentExpenseAndAddress(OrderTransfer $orderTransfer): bool
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null
                || $itemTransfer->getShipment()->getExpense() === null
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findOrderShipmentExpense(OrderTransfer $orderTransfer): ?ExpenseTransfer
    {
        foreach ($orderTransfer->getExpenses() as $key => $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            return $expenseTransfer;
        }

        return null;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransferHasShipmentWithShipmentExpense(ItemTransfer $itemTransfer): bool
    {
        return ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getExpense() !== null);
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
     * @param \Generated\Shared\Transfer\ExpenseTransfer $orderExpenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function getShipmentExpenseTransferForBC(ItemTransfer $itemTransfer, ExpenseTransfer $orderExpenseTransfer): ExpenseTransfer
    {
        if ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getExpense() !== null) {
            return $itemTransfer->getShipment()->getExpense();
        }

        return $orderExpenseTransfer;
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
    protected function setItemTransferShipmentAndShipmentAddressAndShipmentExpenseForBC(
        ItemTransfer $itemTransfer,
        OrderTransfer $orderTransfer,
        ExpenseTransfer $orderExpenseTransfer
    ): void {
        $shipmentTransfer = $this->getShipmentTransferForBC($itemTransfer, $orderTransfer);
        $itemTransfer->setShipment($shipmentTransfer);

        $shipmentExpenseTransfer = $this->getShipmentExpenseTransferForBC($itemTransfer, $orderExpenseTransfer);
        $shippingAddressTransfer = $this->getShipmentAddressTransferForBC($itemTransfer, $orderTransfer);
        $shipmentTransfer->setExpense($shipmentExpenseTransfer)
            ->setShippingAddress($shippingAddressTransfer);
    }
}
