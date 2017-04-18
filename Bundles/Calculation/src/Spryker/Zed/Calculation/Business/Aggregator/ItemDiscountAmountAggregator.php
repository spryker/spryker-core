<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Aggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class ItemDiscountAmountAggregator implements CalculatorInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    protected $expenseDiscountAmountAggregator;

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface $expenseDiscountAmountAggregator
     */
    public function __construct(CalculatorInterface $expenseDiscountAmountAggregator)
    {
        $this->expenseDiscountAmountAggregator = $expenseDiscountAmountAggregator;
    }


    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->expenseDiscountAmountAggregator->recalculate($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setDiscountAmountAggregation(
                $this->calculateItemDiscountAmountAggregation($itemTransfer)
            );
        }
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateItemDiscountAmountAggregation(ItemTransfer $itemTransfer)
    {
        $itemDiscountAmountAggregation = 0;
        foreach ($itemTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
            $this->setCalculatedDiscountsSumGrossAmount($calculatedDiscountTransfer);
            $itemDiscountAmountAggregation += $calculatedDiscountTransfer->getSumGrossAmount();
        }

        return $itemDiscountAmountAggregation;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     */
    protected function setCalculatedDiscountsSumGrossAmount(CalculatedDiscountTransfer $calculatedDiscountTransfer)
    {
        $calculatedDiscountTransfer->setSumGrossAmount($calculatedDiscountTransfer->getUnitGrossAmount() * $calculatedDiscountTransfer->getQuantity());
    }
}
