<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class OrderDiscounts implements OrderAmountAggregatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $orderCalculatedDiscounts = $orderTransfer->getCalculatedDiscounts();
        $orderCalculatedDiscounts = $this->getProductOptionCalculatedDiscounts(
            $orderTransfer,
            $orderCalculatedDiscounts
        );

        $orderTransfer->setCalculatedDiscounts($orderCalculatedDiscounts);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $orderCalculatedDiscounts
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[]
     */
    protected function getProductOptionCalculatedDiscounts(
        OrderTransfer $orderTransfer,
        \ArrayObject $orderCalculatedDiscounts
    ) {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $orderCalculatedDiscounts = $this->sumCalculatedDiscounts(
                    $orderCalculatedDiscounts,
                    $productOptionTransfer->getCalculatedDiscounts()
                );
            }
        }

        return $orderCalculatedDiscounts;
    }


    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $orderCalculatedDiscounts
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[]
     */
    public function sumCalculatedDiscounts(\ArrayObject $orderCalculatedDiscounts, \ArrayObject $calculatedDiscounts)
    {
        foreach ($calculatedDiscounts as $calculatedDiscountDiscountTransfer) {
            $displayName = $calculatedDiscountDiscountTransfer->getDisplayName();
            if ($orderCalculatedDiscounts->offsetExists($displayName) === false) {
                $orderCalculatedDiscounts[$displayName] = clone $calculatedDiscountDiscountTransfer;
                continue;
            }

            $orderCalculatedDiscountTransfer = $orderCalculatedDiscounts[$displayName];
            $orderCalculatedDiscountTransfer->setUnitGrossAmount(
                $orderCalculatedDiscountTransfer->getUnitGrossAmount() + $calculatedDiscountDiscountTransfer->getUnitGrossAmount()
            );
            $orderCalculatedDiscountTransfer->setSumGrossAmount(
                $orderCalculatedDiscountTransfer->getSumGrossAmount() + $calculatedDiscountDiscountTransfer->getSumGrossAmount()
            );

            $orderCalculatedDiscounts[$displayName] = $orderCalculatedDiscountTransfer;
        }

        return $orderCalculatedDiscounts;

    }
}
