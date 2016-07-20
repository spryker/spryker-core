<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\ProductOptionDiscountCalculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\Calculator\CalculatorInterface;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface;

class DiscountTotalAmount implements OrderAmountAggregatorInterface, CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->assertDiscountTotalRequirements($orderTransfer);

        $orderTransfer->getTotals()->setDiscountTotal(
            $this->getTotalDiscountAmountWithProductOptions(
                $orderTransfer->getTotals(),
                $orderTransfer->getItems()
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->getTotals()->setDiscountTotal(
            $this->getTotalDiscountAmountWithProductOptions(
                $quoteTransfer->getTotals(),
                $quoteTransfer->getItems()
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return int
     */
    protected function getTotalDiscountAmountWithProductOptions(TotalsTransfer $totalsTransfer, \ArrayObject $items)
    {
        $currentTotalDiscountAmount = $totalsTransfer->getDiscountTotal();
        $discountTotalAmountForProductOptions = $this->getSumTotalGrossDiscountAmount($items);

        return (int)round($currentTotalDiscountAmount + $discountTotalAmountForProductOptions);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return int
     */
    protected function getSumTotalGrossDiscountAmount(\ArrayObject $items)
    {
        $totalSumGrossDiscountAmount = 0;
        foreach ($items as $itemTransfer) {
            $totalSumGrossDiscountAmount += $this->getProductOptionCalculatedDiscounts($itemTransfer);
        }

        return $totalSumGrossDiscountAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function assertDiscountTotalRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireTotals();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getProductOptionCalculatedDiscounts(ItemTransfer $itemTransfer)
    {
        $productOptionSumTotalAmount = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {

            $productOptionSum = $this->getCalculatedDiscountSumGrossAmount(
                $productOptionTransfer->getCalculatedDiscounts()
            );

            if ($productOptionSum > $productOptionTransfer->getSumGrossPrice()) {
                $productOptionSum = $productOptionTransfer->getSumGrossPrice();
            }

            $productOptionSumTotalAmount += $productOptionSum;
        }

        return $productOptionSumTotalAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return int
     */
    protected function getCalculatedDiscountSumGrossAmount(\ArrayObject $calculatedDiscounts)
    {
        $totalSumGrossDiscountAmount = 0;
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $totalSumGrossDiscountAmount += $calculatedDiscountTransfer->getSumGrossAmount();
        }

        return $totalSumGrossDiscountAmount;
    }

}
