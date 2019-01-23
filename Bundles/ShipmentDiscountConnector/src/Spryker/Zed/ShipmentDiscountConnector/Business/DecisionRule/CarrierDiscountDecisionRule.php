<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\CarrierDiscountDecisionRule as CarrierDiscountDecisionRuleWithMultiShipment;

class CarrierDiscountDecisionRule extends CarrierDiscountDecisionRuleWithMultiShipment
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer)
    {
        /**
         * @deprecated Remove after multiple shipment will be released.
         */
        $quoteTransfer = $this->adaptQuoteDataBCForMultiShipment($quoteTransfer);

        return $this->isSatisfiedItemShipmentCarrierBy($itemTransfer, $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isItemShipmentExpenseSatisfiedBy(ItemTransfer $itemTransfer, ExpenseTransfer $expenseTransfer, ClauseTransfer $clauseTransfer): bool
    {
        return $this->isSatisfiedItemShipmentCarrierBy($itemTransfer, $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function isSatisfiedItemShipmentCarrierBy(ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer): bool
    {
        $idShipmentCarrier = $this->getIdShipmentCarrierByItem($itemTransfer);

        if ($idShipmentCarrier && $this->discountFacade->queryStringCompare($clauseTransfer, $idShipmentCarrier)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int|null
     */
    protected function getIdShipmentCarrierByItem(ItemTransfer $itemTransfer): ?int
    {
        $shipment = $itemTransfer->getShipment();

        if (!$shipment) {
            return null;
        }

        if ($shipment->getCarrier()) {
            return $shipment->getCarrier()->getIdShipmentCarrier();
        }

        if ($shipment->getMethod()) {
            $shipmentMethodTransfer = $this->shipmentFacade->findMethodById($shipment->getMethod()->getIdShipmentMethod());
            return $shipmentMethodTransfer->getFkShipmentCarrier();
        }

        return null;
    }

    /**
     * @deprecated Remove after multiple shipment will be released.
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
}
