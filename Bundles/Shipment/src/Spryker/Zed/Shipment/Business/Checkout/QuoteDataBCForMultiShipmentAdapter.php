<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Checkout;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;

/**
 * @deprecated Will be removed in next major release.
 */
class QuoteDataBCForMultiShipmentAdapter implements QuoteDataBCForMultiShipmentAdapterInterface
{
    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function adapt(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($this->assertThatItemTransfersHaveShipmentAndShipmentMethodAndShipmentExpenseAndAddress($quoteTransfer)) {
            return $quoteTransfer;
        }

        if ($this->assertThatQuoteHasNoAddressTransfer($quoteTransfer)) {
            return $quoteTransfer;
        }

        if ($this->assertThatQuoteHasNoShipment($quoteTransfer)) {
            return $quoteTransfer;
        }

        if ($this->assertThatQuoteHasNoShipmentMethod($quoteTransfer)) {
            return $quoteTransfer;
        }

        $quoteExpenseTransfer = $this->findQuoteShipmentExpense($quoteTransfer);
        if ($quoteExpenseTransfer === null) {
            return $quoteTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->assertThatItemTransferHasShipmentWithShipmentMethodAndExpense($itemTransfer)) {
                continue;
            }

            $this->setItemTransferShipmentAndShipmentMethodAndShipmentExpenseForBC($itemTransfer, $quoteTransfer, $quoteExpenseTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransfersHaveShipmentAndShipmentMethodAndShipmentExpenseAndAddress(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null
                || $itemTransfer->getShipment()->getMethod() === null
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasNoAddressTransfer(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShippingAddress() === null;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasNoShipment(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShipment() === null;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasNoShipmentMethod(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShipment()->getMethod() === null;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findQuoteShipmentExpense(QuoteTransfer $quoteTransfer): ?ExpenseTransfer
    {
        foreach ($quoteTransfer->getExpenses() as $key => $expenseTransfer) {
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
    protected function assertThatItemTransferHasShipmentWithShipmentMethodAndExpense(ItemTransfer $itemTransfer): bool
    {
        return ($itemTransfer->getShipment() !== null
            && $itemTransfer->getShipment()->getMethod() !== null
            && $itemTransfer->getShipment()->getExpense() !== null
        );
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getShipmentTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ShipmentTransfer
    {
        if ($itemTransfer->getShipment() !== null) {
            return $itemTransfer->getShipment();
        }

        return $quoteTransfer->getShipment();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function getShipmentMethodTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ShipmentMethodTransfer
    {
        if ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getMethod() !== null) {
            return $itemTransfer->getShipment()->getMethod();
        }

        return $quoteTransfer->getShipment()->getMethod();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $quoteExpenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function getShipmentExpenseTransferForBC(ItemTransfer $itemTransfer, ExpenseTransfer $quoteExpenseTransfer): ExpenseTransfer
    {
        if ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getExpense() !== null) {
            return $itemTransfer->getShipment()->getExpense();
        }

        return $quoteExpenseTransfer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getShipmentAddressTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): AddressTransfer
    {
        if ($itemTransfer->getShipment()->getShippingAddress() !== null) {
            return $itemTransfer->getShipment()->getShippingAddress();
        }

        return $quoteTransfer->getShippingAddress();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $quoteExpenseTransfer
     *
     * @return void
     */
    protected function setItemTransferShipmentAndShipmentMethodAndShipmentExpenseForBC(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer,
        ExpenseTransfer $quoteExpenseTransfer
    ): void {
        $shipmentTransfer = $this->getShipmentTransferForBC($itemTransfer, $quoteTransfer);
        $itemTransfer->setShipment($shipmentTransfer);

        $shipmentMethodTransfer = $this->getShipmentMethodTransferForBC($itemTransfer, $quoteTransfer);
        $shipmentExpenseTransfer = $this->getShipmentExpenseTransferForBC($itemTransfer, $quoteExpenseTransfer);
        $shippingAddressTransfer = $this->getShipmentAddressTransferForBC($itemTransfer, $quoteTransfer);
        $shipmentTransfer->setMethod($shipmentMethodTransfer)
            ->setExpense($shipmentExpenseTransfer)
            ->setShippingAddress($shippingAddressTransfer);
    }
}
