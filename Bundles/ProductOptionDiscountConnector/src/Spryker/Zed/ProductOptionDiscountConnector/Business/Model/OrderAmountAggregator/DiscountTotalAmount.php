<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class DiscountTotalAmount
{
    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->assertDisountTotalRequirements($orderTransfer);

        $totalDiscountAmountWithProductOptions = $this->getTotalDiscountAmountWithProductOptions($orderTransfer);

        $orderTransfer->getTotals()->setDiscountTotal($totalDiscountAmountWithProductOptions);
    }

    /**
     * @param \ArrayObject|CalculatedDiscountTransfer[] $calculatedDiscounts
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

    /**
     * @param \ArrayObject|CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return int
     */
    protected function getCalculatedDiscountUnitGrossAmount(\ArrayObject $calculatedDiscounts)
    {
        $totalUnitGrossDiscountAmount = 0;
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $totalUnitGrossDiscountAmount += $calculatedDiscountTransfer->getUnitGrossAmount();
        }

        return $totalUnitGrossDiscountAmount;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getSumTotalGrossDiscountAmount(OrderTransfer $orderTransfer)
    {
        $totalSumGrossDiscountAmount = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $totalItemUnitDiscountAmount = $this->getCalculatedDiscountUnitGrossAmount($itemTransfer->getCalculatedDiscounts());
            $totalItemSumDiscountAmount = $this->getCalculatedDiscountSumGrossAmount($itemTransfer->getCalculatedDiscounts());

            list($productOptionUnitAmount, $productOptionSumAmount) = $this->getProductOptionCalculatedDiscounts($itemTransfer);

            $itemTransfer->setUnitGrossPriceWithProductOptionAndDiscountAmounts(
                $itemTransfer->getUnitGrossPriceWithProductOptions() - $totalItemUnitDiscountAmount - $productOptionUnitAmount
            );
            $itemTransfer->setSumGrossPriceWithProductOptionAndDiscountAmounts(
                $itemTransfer->getSumGrossPriceWithProductOptions() - $totalItemSumDiscountAmount - $productOptionSumAmount
            );

            $totalSumGrossDiscountAmount += $productOptionSumAmount;

            $itemTransfer->setRefundableAmount(
                $itemTransfer->getRefundableAmount() - $totalItemSumDiscountAmount - $productOptionSumAmount
            );
        }

        return $totalSumGrossDiscountAmount;
    }

    /**
     * @param OrderTransfer $orderTransfer
     */
    protected function assertDisountTotalRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireTotals();
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getTotalDiscountAmountWithProductOptions(OrderTransfer $orderTransfer)
    {
        $currentTotalDiscountAmount = $orderTransfer->getTotals()->getDiscountTotal();
        $discountTotalAmountForProductOptions = $this->getSumTotalGrossDiscountAmount($orderTransfer);

        return $currentTotalDiscountAmount + $discountTotalAmountForProductOptions;
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function getProductOptionCalculatedDiscounts(ItemTransfer $itemTransfer)
    {
        $productOptionUnitAmount = 0;
        $productOptionSumAmount = 0;

        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionUnitAmount += $this->getCalculatedDiscountUnitGrossAmount(
                $productOptionTransfer->getCalculatedDiscounts()
            );

            $productOptionSumAmount += $this->getCalculatedDiscountSumGrossAmount(
                $productOptionTransfer->getCalculatedDiscounts()
            );
        }

        return array($productOptionUnitAmount, $productOptionSumAmount);
    }

}
