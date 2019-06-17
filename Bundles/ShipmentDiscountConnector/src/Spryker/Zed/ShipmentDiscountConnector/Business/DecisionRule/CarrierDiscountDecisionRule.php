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
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\CarrierDiscountDecisionRule as CarrierDiscountDecisionRuleWithoutMultiShipment;

class CarrierDiscountDecisionRule extends CarrierDiscountDecisionRuleWithoutMultiShipment
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->isSatisfiedItemShipmentCarrier($itemTransfer->getShipment(), $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isExpenseSatisfiedBy(QuoteTransfer $quoteTransfer, ExpenseTransfer $expenseTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->isSatisfiedItemShipmentCarrier($expenseTransfer->getShipment(), $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function isSatisfiedItemShipmentCarrier(ShipmentTransfer $shipmentTransfer, ClauseTransfer $clauseTransfer): bool
    {
        $idShipmentCarrier = $this->getIdShipmentCarrier($shipmentTransfer);

        if ($idShipmentCarrier && $this->discountFacade->queryStringCompare($clauseTransfer, $idShipmentCarrier)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return int|null
     */
    protected function getIdShipmentCarrier(ShipmentTransfer $shipmentTransfer): ?int
    {
        if ($shipmentTransfer->getCarrier()) {
            return $shipmentTransfer->getCarrier()->getIdShipmentCarrier();
        }

        if ($shipmentTransfer->getMethod()) {
            $shipmentMethodTransfer = $this->shipmentFacade->findMethodById($shipmentTransfer->getMethod()->getIdShipmentMethod());

            return $shipmentMethodTransfer->getFkShipmentCarrier();
        }

        return null;
    }
}
