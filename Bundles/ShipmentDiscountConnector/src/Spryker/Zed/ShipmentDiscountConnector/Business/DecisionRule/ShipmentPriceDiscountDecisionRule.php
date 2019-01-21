<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\ShipmentPriceDiscountDecisionRule as ShipmentPriceDiscountDecisionRuleWithMultiShipment;

class ShipmentPriceDiscountDecisionRule extends ShipmentPriceDiscountDecisionRuleWithMultiShipment
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isItemShipmentSatisfiedBy(ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer): bool
    {
        return $this->isSatisfiedPrice($itemTransfer->getShipment()->getExpense(), $clauseTransfer);
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
        return $this->isSatisfiedPrice($expenseTransfer, $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function isSatisfiedPrice(ExpenseTransfer $expenseTransfer, ClauseTransfer $clauseTransfer): bool
    {
        $moneyAmount = $this->moneyFacade->convertIntegerToDecimal($expenseTransfer->getUnitGrossPrice());

        return $this->discountFacade->queryStringCompare($clauseTransfer, $moneyAmount);
    }
}
