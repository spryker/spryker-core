<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;

class ShipmentDiscountCollector implements ShipmentDiscountCollectorInterface
{
    /**
     * @var \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface
     */
    protected $shipmentDiscountDecisionRule;

    /**
     * @param \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface $carrierDiscountDecisionRule
     */
    public function __construct(ShipmentDiscountDecisionRuleInterface $carrierDiscountDecisionRule)
    {
        $this->shipmentDiscountDecisionRule = $carrierDiscountDecisionRule;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $discountableItems = [];

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $isSatisfied = $this->shipmentDiscountDecisionRule->isExpenseSatisfiedBy($quoteTransfer, $expenseTransfer, $clauseTransfer);

            if ($isSatisfied) {
                $discountableItems[] = $this->createDiscountableItemTransfer($expenseTransfer, $quoteTransfer->getPriceMode());
            }
        }

        return $discountableItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createDiscountableItemTransfer(ExpenseTransfer $expenseTransfer, $priceMode)
    {
        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->fromArray($expenseTransfer->toArray(), true);
        $price = $this->getPrice($expenseTransfer, $priceMode);
        $discountableItemTransfer->setUnitGrossPrice($price);
        $discountableItemTransfer->setUnitPrice($price);
        $discountableItemTransfer->setOriginalItemCalculatedDiscounts($expenseTransfer->getCalculatedDiscounts());

        return $discountableItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param string $priceMode
     *
     * @return int
     */
    private function getPrice(ExpenseTransfer $expenseTransfer, $priceMode)
    {
        if ($priceMode === 'NET_MODE') {
            return $expenseTransfer->getUnitNetPrice();
        } else {
            return $expenseTransfer->getUnitGrossPrice();
        }
    }
}
