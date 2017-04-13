<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Aggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class ItemProductOptionPriceAggregator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setProductOptionPriceAggregation(
                $this->aggregateProductOptionPrice($itemTransfer)
            );
        }
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function aggregateProductOptionPrice(ItemTransfer $itemTransfer)
    {
        $productOptionPriceAggregation = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionPriceAggregation += $productOptionTransfer->getSumPrice();
        }

        return $productOptionPriceAggregation;
    }
}

