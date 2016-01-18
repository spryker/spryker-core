<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class OrderDiscounts
{
    /**
     * @var DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @param DiscountQueryContainerInterface $discountQueryContainer
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
        $orderCalculatedDiscounts = new \ArrayObject();
        $orderCalculatedDiscounts = $this->getOrderItemCalculatedDIscounts($orderTransfer, $orderCalculatedDiscounts);
        $orderCalculatedDiscounts = $this->getProductOptionCalculatedDiscounts($orderTransfer, $orderCalculatedDiscounts);
        $orderCalculatedDiscounts = $this->getOrderExpenseCalculatedDiscounts($orderTransfer, $orderCalculatedDiscounts);

        $orderTransfer->setCalculatedDiscounts($orderCalculatedDiscounts);
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param \ArrayObject $orderCalculatedDiscounts
     *
     * @return \ArrayObject
     */
    protected function getOrderExpenseCalculatedDiscounts(
        OrderTransfer $orderTransfer,
        \ArrayObject $orderCalculatedDiscounts
    ) {
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $orderCalculatedDiscounts = $this->sumCalculatedDiscounts(
                $orderCalculatedDiscounts,
                $expenseTransfer->getCalculatedDiscounts()
            );
        }

        return $orderCalculatedDiscounts;
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param \ArrayObject $orderCalculatedDiscounts
     *
     * @return \ArrayObject
     */
    protected function getOrderItemCalculatedDIscounts(
        OrderTransfer $orderTransfer,
        \ArrayObject $orderCalculatedDiscounts
    ) {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $orderCalculatedDiscounts = $this->sumCalculatedDiscounts(
                $orderCalculatedDiscounts,
                $itemTransfer->getCalculatedDiscounts()
            );
        }

        return $orderCalculatedDiscounts;
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param \ArrayObject $orderCalculatedDiscounts
     *
     * @return \ArrayObject
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
     * @param \ArrayObject $orderCalculatedDiscounts
     * @param \ArrayObject|CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return \ArrayObject
     */
    public function sumCalculatedDiscounts(
        \ArrayObject $orderCalculatedDiscounts,
        \ArrayObject $calculatedDiscounts
    ) {
        foreach ($calculatedDiscounts as $calculatedDiscountDiscountTransfer) {
            $displayName = $calculatedDiscountDiscountTransfer->getDisplayName();
            if ($orderCalculatedDiscounts->offsetExists($displayName) === false) {
                $orderCalculatedDiscounts[$displayName] = $calculatedDiscountDiscountTransfer;
                continue;
            }

            $orderCalculatedDiscountTransfer = $calculatedDiscounts[$displayName];
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
