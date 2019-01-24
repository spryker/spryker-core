<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaver as SalesOrderSaverWithoutItemShipmentAddress;

class SalesOrderSaver extends SalesOrderSaverWithoutItemShipmentAddress
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderSales(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        /**
         * @deprecated Will be removed in next major version after multiple shipment release.
         */
        $quoteTransfer = $this->adaptQuoteDataBCForMultiShipment($quoteTransfer);

        parent::saveOrderSales($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function adaptQuoteDataBCForMultiShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() !== null) {
                return $quoteTransfer;
            }
            break;
        }

        $shippingAddress = $quoteTransfer->getShippingAddress();
        if ($shippingAddress === null) {
            return $quoteTransfer;
        }

        $shipmentExpenseTransfer = null;
        foreach ($quoteTransfer->getExpenses() as $key => $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $shipmentExpenseTransfer = $expenseTransfer;
            break;
        }

        $quoteShipment = $quoteTransfer->getShipment();
        if ($quoteShipment === null && $shipmentExpenseTransfer === null) {
            return $quoteTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() !== null
                && $itemTransfer->getShipment()->getExpense() !== null
                && $itemTransfer->getShipment()->getShippingAddress() !== null
            ) {
                continue;
            }

            $shipmentTransfer = $itemTransfer->getShipment() ?: $quoteShipment;
            if ($shipmentTransfer === null) {
                $shipmentTransfer = (new ShipmentTransfer())
                    ->setMethod(new ShipmentMethodTransfer());
            }

            if ($shipmentExpenseTransfer === null && $itemTransfer->getShipment() !== null) {
                $shipmentExpenseTransfer = $itemTransfer->getShipment()->getExpense();
            }

            $shipmentTransfer->setExpense($shipmentExpenseTransfer)
                ->setShippingAddress($shippingAddress);
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateAddresses(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $billingAddressEntity = $this->saveSalesOrderAddress($quoteTransfer->getBillingAddress());
        $salesOrderEntity->setBillingAddress($billingAddressEntity);
    }
}
