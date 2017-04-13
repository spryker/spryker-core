<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Aggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class ItemDiscountAmountFullAggregator implements CalculatorInterface
{
    /**
     * @var \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    protected $itemDiscountAmountAggregator;

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface $itemDiscountAmountAggregator
     */
    public function __construct(CalculatorInterface $itemDiscountAmountAggregator)
    {
        $this->itemDiscountAmountAggregator = $itemDiscountAmountAggregator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->itemDiscountAmountAggregator->recalculate($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productOptionDiscountAmountAggregation = $this->calculateProductOptionDiscountAmountAggregation($itemTransfer);

            $itemTransfer->setDiscountAmountFullAggregation(
                $itemTransfer->getDiscountAmountAggregation() + $productOptionDiscountAmountAggregation
            );
        }
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateProductOptionDiscountAmountAggregation(ItemTransfer $itemTransfer)
    {
        $productOptionDiscountAmountAggregation = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            foreach ($productOptionTransfer->getCalculatedDiscounts() as $discountTransfer) {
                $productOptionDiscountAmountAggregation += $discountTransfer->getSumGrossAmount();
            }
        }

        return $productOptionDiscountAmountAggregation;
    }
}
