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
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\CarrierDiscountDecisionRule as CarrierDiscountDecisionRuleWithMultiShipment;

class CarrierDiscountDecisionRule extends CarrierDiscountDecisionRuleWithMultiShipment
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
}
