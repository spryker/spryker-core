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
        return $this->isSatisfiedItemShipmentMethod($itemTransfer->getShipment(), $clauseTransfer);
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
        return $this->isSatisfiedItemShipmentMethod($expenseTransfer->getShipment(), $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function isSatisfiedItemShipmentMethod(ShipmentTransfer $shipmentTransfer, ClauseTransfer $clauseTransfer): bool
    {
        if ($shipmentTransfer->getMethod() === null) {
            return false;
        }

        $idShipmentMethod = $shipmentTransfer->getMethod()->getIdShipmentMethod();

        if ($idShipmentMethod && $this->discountFacade->queryStringCompare($clauseTransfer, $idShipmentMethod)) {
            return true;
        }

        return false;
    }
}
