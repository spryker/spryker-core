<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\DiscountItemsTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\DiscountTotalItemTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class DiscountTotalsCalculator implements DiscountTotalsCalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return void
     */
    public function recalculateTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $discountableContainer,
        $calculableItems
    ) {
        $discountTransfer = $this->createDiscountTransfer($discountableContainer, $calculableItems);
        $totalsTransfer->setDiscount($discountTransfer);
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return int
     */
    public function calculateDiscount(
        CalculableInterface $discountableContainer,
        \ArrayObject $calculableItems
    ) {
        $discountAmount = 0;

        if ($calculableItems instanceof OrderItemsTransfer) {
            $calculableItems = $calculableItems->getOrderItems();
        }

        foreach ($calculableItems as $itemTransfer) {
            $discountAmount += $this->calculateItemDiscountAmount($itemTransfer);
        }

        $discountAmount += $this->sumTotalExpenseDiscounts($discountableContainer);

        return $discountAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateItemDiscountAmount(ItemTransfer $itemTransfer)
    {
        $itemDiscountAmount = 0;

        $itemDiscountAmount += $this->sumItemDiscounts($itemTransfer->getDiscounts());
        $itemDiscountAmount += $this->sumItemExpenseDiscounts($itemTransfer->getExpenses());
        $itemDiscountAmount += $this->sumOptionDiscounts($itemTransfer->getProductOptions());

        $itemDiscountAmount = $itemDiscountAmount * $itemTransfer->getQuantity();

        return $itemDiscountAmount;
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountableContainer
     * @param \ArrayObject|ItemTransfer[] $calculableItems
     *
     * @return \Generated\Shared\Transfer\DiscountTotalsTransfer
     */
    protected function createDiscountTransfer(
        CalculableInterface $discountableContainer,
        \ArrayObject $calculableItems
    ) {
        $discountTransfer = new DiscountTotalsTransfer();
        $totalDiscountAmount = $this->calculateDiscount($discountableContainer, $calculableItems);
        $discountTransfer->setTotalAmount($totalDiscountAmount);

        $discountTotalItemCollection = $this->calculateDiscountTotals($discountableContainer, $calculableItems);
        foreach ($discountTotalItemCollection as $discountTotalItemTransfer) {
            $discountTransfer->addDiscountItem($discountTotalItemTransfer);
        }

        return $discountTransfer;
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountableContainer
     * @param \ArrayObject|ItemTransfer[] $calculableItems
     *
     * @return array|DiscountTotalItemTransfer[]
     */
    protected function calculateDiscountTotals(
        CalculableInterface $discountableContainer,
        \ArrayObject $calculableItems
    ) {
        $discountTotalItemCollection = [];

        foreach ($discountableContainer->getCalculableObject()->getExpenses() as $expensesTransfer) {
            foreach ($expensesTransfer->getDiscounts() as $discountTransfer) {
                $this->transformDiscountToDiscountTotalItemInArray($discountTransfer, $discountTotalItemCollection);
            }
        }

        $this->calculateItemTotals($calculableItems, $discountTotalItemCollection);

        return $discountTotalItemCollection;
    }

    /**
     * @param \ArrayObject|ItemTransfer[] $calculableItems
     * @param array|DiscountTotalItemTransfer[] $discountTotalItemCollection
     *
     * @return void
     */
    protected function calculateItemTotals(\ArrayObject $calculableItems, &$discountTotalItemCollection)
    {
        foreach ($calculableItems as $itemTransfer) {
            foreach ($itemTransfer->getDiscounts() as $discountTransfer) {
                $this->transformDiscountToDiscountTotalItemInArray(
                    $discountTransfer,
                    $discountTotalItemCollection,
                    $itemTransfer->getQuantity()
                );
            }

            foreach ($itemTransfer->getProductOptions() as $optionTransfer) {
                foreach ($optionTransfer->getDiscounts() as $discountTransfer) {
                    $this->transformDiscountToDiscountTotalItemInArray(
                        $discountTransfer,
                        $discountTotalItemCollection,
                        $optionTransfer->getQuantity()
                    );
                }
            }

            foreach ($itemTransfer->getExpenses() as $expensesTransfer) {
                foreach ($expensesTransfer->getDiscounts() as $discountTransfer) {
                    $this->transformDiscountToDiscountTotalItemInArray(
                        $discountTransfer,
                        $discountTotalItemCollection,
                        $expensesTransfer->getQuantity()
                    );
                }
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param array|DiscountTotalItemTransfer[] $discountTotalItemCollection
     * @param int $quantity
     *
     * @return void
     */
    protected function transformDiscountToDiscountTotalItemInArray(
        DiscountTransfer $discountTransfer,
        array &$discountTotalItemCollection,
        $quantity = 1
    ) {
        if (!isset($discountTotalItemCollection[$discountTransfer->getDisplayName()])) {
            $discountTotalItemTransfer = $this->getDiscountTotalItem();
            $discountTotalItemTransfer->setName($discountTransfer->getDisplayName());
        } else {
            $discountTotalItemTransfer = $discountTotalItemCollection[$discountTransfer->getDisplayName()];
        }

        $this->setUsedCodes($discountTotalItemTransfer, $discountTransfer);

        $discountTotalItemTransfer->setAmount(
            $discountTotalItemTransfer->getAmount() + ($discountTransfer->getAmount() * $quantity)
        );
        $discountTotalItemCollection[$discountTransfer->getDisplayName()] = $discountTotalItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTotalItemTransfer $discountTotalItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return void
     */
    protected function setUsedCodes(
        DiscountTotalItemTransfer $discountTotalItemTransfer,
        DiscountTransfer $discountTransfer
    ) {
        $storedCodes = (array) $discountTotalItemTransfer->getCodes();
        foreach ($discountTransfer->getUsedCodes() as $code) {
            if (!in_array($code, $storedCodes)) {
                $discountTotalItemTransfer->addCode($code);
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountTotalItemTransfer
     */
    protected function getDiscountTotalItem()
    {
        return new DiscountTotalItemTransfer();
    }

    /**
     * @param \ArrayObject|DiscountItemsTransfer $discounts
     *
     * @return int
     */
    protected function sumItemDiscounts(\ArrayObject $discounts)
    {
        $discountAmount = 0;

        if ($discounts instanceof DiscountItemsTransfer) {
            $discounts = $discounts->getDiscounts();
        }

        foreach ($discounts as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }

    /**
     * @param \ArrayObject|ExpenseTransfer[] $expenses
     *
     * @return int
     */
    protected function sumItemExpenseDiscounts(\ArrayObject $expenses)
    {
        $discountAmount = 0;

        foreach ($expenses as $expense) {
            if ($expense instanceof ExpenseTransfer) {
                foreach ($expense->getDiscounts() as $discount) {
                    $discountAmount += $discount->getAmount();
                }
            }
        }

        return $discountAmount;
    }

    /**
     * @param \ArrayObject|ProductOptionTransfer[] $options
     *
     * @return int
     */
    protected function sumOptionDiscounts(\ArrayObject $options)
    {
        $discountAmount = 0;

        foreach ($options as $option) {
            if ($option instanceof ProductOptionTransfer) {
                foreach ($option->getDiscounts() as $discount) {
                    $discountAmount += $discount->getAmount();
                }
            }
        }

        return $discountAmount;
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountableContainer
     *
     * @return int
     */
    protected function sumTotalExpenseDiscounts(CalculableInterface $discountableContainer)
    {
        $discountAmount = 0;

        foreach ($discountableContainer->getCalculableObject()->getExpenses() as $expense) {
            foreach ($expense->getDiscounts() as $discount) {
                $discountAmount += $discount->getAmount();
            }
        }

        return $discountAmount;
    }

}
