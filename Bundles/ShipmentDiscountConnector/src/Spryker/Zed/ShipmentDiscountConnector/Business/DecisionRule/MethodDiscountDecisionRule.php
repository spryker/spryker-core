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
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\MethodDiscountDecisionRule as MethodDiscountDecisionRuleWithMultiShipment;

class MethodDiscountDecisionRule extends MethodDiscountDecisionRuleWithMultiShipment
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
        return $this->isSatisfiedItemShipmentMethod($itemTransfer, $clauseTransfer);
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
        return $this->isSatisfiedItemShipmentMethod($itemTransfer, $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function isSatisfiedItemShipmentMethod(ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer): bool
    {
        $shipment = $itemTransfer->getShipment();

        if (!$shipment) {
            return false;
        }

        $idShipmentMethod = null;

        if ($shipment->getMethod()) {
            $idShipmentMethod = $shipment->getMethod()->getIdShipmentMethod();
        }

        if ($idShipmentMethod && $this->discountFacade->queryStringCompare($clauseTransfer, $idShipmentMethod)) {
            return true;
        }

        return false;
    }
}
