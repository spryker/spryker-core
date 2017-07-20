<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface;

class PriceDiscountDecisionRule implements PriceDiscountDecisionRuleInterface
{

    /**
     * @var \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface $discountFacade
     */
    public function __construct(ShipmentDiscountConnectorToDiscountInterface $discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer)
    {
        $result = false;

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $result |= $this->isSatisfiedPrice($expenseTransfer, $clauseTransfer);
        }

        return $result;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param ExpenseTransfer $expenseTransfer
     * @param ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isExpenseSatisfiedBy(QuoteTransfer $quoteTransfer, ExpenseTransfer $expenseTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->isSatisfiedPrice($expenseTransfer, $clauseTransfer);
    }

    /**
     * @param ExpenseTransfer $expenseTransfer
     * @param ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function isSatisfiedPrice(ExpenseTransfer $expenseTransfer, ClauseTransfer $clauseTransfer)
    {
        $moneyAmount = $expenseTransfer->getUnitGrossPrice();

        if ($moneyAmount > 0) {
            $moneyAmount /= 100;
        }

        return $this->discountFacade->queryStringCompare($clauseTransfer, $moneyAmount);
    }

}
