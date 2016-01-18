<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;

class ProductOption
{
    /**
     * @param OrderTransfer $orderTransfer
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $totalProductOptionGrossSum = 0;
            $totalProductOptionGrossUnit = 0;
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $productOptionTransfer->requireUnitGrossPrice()->requireQuantity();
                $productOptionTransfer->setSumGrossPrice(
                    $productOptionTransfer->getUnitGrossPrice() * $productOptionTransfer->getQuantity()
                );

                $totalProductOptionGrossSum += $productOptionTransfer->getSumGrossPrice();
                $totalProductOptionGrossUnit += $productOptionTransfer->getUnitGrossPrice();
            }

            $itemTransfer->setUnitGrossPriceWithProductOptions($itemTransfer->getUnitGrossPrice() + $totalProductOptionGrossUnit);
            $itemTransfer->setSumGrossPriceWithProductOptions($itemTransfer->getSumGrossPrice() + $totalProductOptionGrossSum);
        }
    }
}
