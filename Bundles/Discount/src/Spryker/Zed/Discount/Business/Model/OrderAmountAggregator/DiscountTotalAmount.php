<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class DiscountTotalAmount
{
    /**
     * @var DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * ItemDiscountAmounts constructor.
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireTotals();

        $totalDiscountAmount = $this->getSumTotalGrossDiscountAmount($orderTransfer);
        
        $discountTotalsTransfer = new DiscountTotalsTransfer();
        $discountTotalsTransfer->setTotalAmount($totalDiscountAmount);
        $orderTransfer->getTotals()->setDiscount($discountTotalsTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getSumTotalGrossDiscountAmount(OrderTransfer $orderTransfer)
    {
        $totalSumGrossDiscountAmount = $this->getItemDiscountTotalAmount($orderTransfer);
        $totalSumGrossDiscountAmount += $this->getExpenseDiscountTotalAmount($orderTransfer);

        return $totalSumGrossDiscountAmount;
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
    protected function getItemDiscountTotalAmount(OrderTransfer $orderTransfer)
    {
        $totalSumGrossDiscountAmount = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTotalSumGrossDiscountAmount = 0;
            $itemTotalUnitGrossDiscountAmount = 0;

            $itemTotalSumGrossDiscountAmount += $this->getCalculatedDiscountSumGrossAmount($itemTransfer->getCalculatedDiscounts());
            $itemTotalUnitGrossDiscountAmount += $this->getCalculatedDiscountUnitGrossAmount($itemTransfer->getCalculatedDiscounts());
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $itemTotalSumGrossDiscountAmount += $this->getCalculatedDiscountSumGrossAmount($productOptionTransfer->getCalculatedDiscounts());
                $itemTotalUnitGrossDiscountAmount += $this->getCalculatedDiscountUnitGrossAmount($productOptionTransfer->getCalculatedDiscounts());
            }

            $itemTransfer->setUnitGrossPriceWithProductOptionAndDiscountAmounts(
                $itemTransfer->getUnitGrossPriceWithProductOptions() - $itemTotalUnitGrossDiscountAmount
            );
            $itemTransfer->setSumGrossPriceWithProductOptionAndDiscountAmounts(
                $itemTransfer->getSumGrossPriceWithProductOptions() - $itemTotalSumGrossDiscountAmount
            );

            $totalSumGrossDiscountAmount += $itemTotalSumGrossDiscountAmount;
        }
        return $totalSumGrossDiscountAmount;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getExpenseDiscountTotalAmount(OrderTransfer $orderTransfer)
    {
        $totalSumGrossDiscountAmount = 0 ;
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $totalSumGrossDiscountAmount += $this->getCalculatedDiscountSumGrossAmount($expenseTransfer->getCalculatedDiscounts());
        }
        return $totalSumGrossDiscountAmount;
    }
}
