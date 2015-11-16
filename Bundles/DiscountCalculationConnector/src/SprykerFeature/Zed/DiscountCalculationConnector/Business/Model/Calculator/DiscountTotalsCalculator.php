<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Calculation\DiscountItemsInterface;
use Generated\Shared\Calculation\TotalsInterface;
use Generated\Shared\DiscountCalculationConnector\DiscountInterface;
use Generated\Shared\DiscountCalculationConnector\ExpenseInterface;
use Generated\Shared\DiscountCalculationConnector\ItemInterface;
use Generated\Shared\DiscountCalculationConnector\ProductOptionInterface;
use Generated\Shared\Sales\DiscountTotalItemInterface;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\DiscountTotalItemTransfer;
use Generated\Shared\Sales\OrderItemsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class DiscountTotalsCalculator implements DiscountTotalsCalculatorInterface
{

    /**
     * @param TotalsInterface     $totalsTransfer
     * @param CalculableInterface $discountableContainer
     * @param $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $discountableContainer,
        $calculableItems
    ) {
        $discountTransfer = $this->createDiscountTransfer($discountableContainer, $calculableItems);
        $totalsTransfer->setDiscount($discountTransfer);
    }

    /**
     * @param CalculableInterface $discountableContainer
     * @param $calculableItems
     *
     * @return array|int
     */
    public function calculateDiscount(
        CalculableInterface $discountableContainer,
        $calculableItems
    ) {
        $discountAmount = 0;

        if ($calculableItems instanceof OrderItemsInterface) {
            $calculableItems = $calculableItems->getOrderItems();
        }

        foreach ($calculableItems as $itemTransfer) {
            $discountAmount += $this->calculateItemDiscountAmount($itemTransfer);
        }

        $discountAmount += $this->sumTotalExpenseDiscounts($discountableContainer);

        return $discountAmount;
    }

    /**
     * @param ItemInterface $itemTransfer
     *
     * @return int
     */
    protected function calculateItemDiscountAmount(ItemInterface $itemTransfer)
    {
        $itemDiscountAmount = 0;

        $itemDiscountAmount += $this->sumItemDiscounts($itemTransfer->getDiscounts());
        $itemDiscountAmount += $this->sumItemExpenseDiscounts($itemTransfer->getExpenses());
        $itemDiscountAmount += $this->sumOptionDiscounts($itemTransfer->getProductOptions());

        $itemDiscountAmount = $itemDiscountAmount * $itemTransfer->getQuantity();

        return $itemDiscountAmount;
    }

    /**
     * @param CalculableInterface $discountableContainer
     * @param \ArrayObject|ItemInterface[] $calculableItems
     *
     * @return DiscountTotalsTransfer
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
     * @param CalculableInterface $discountableContainer
     * @param \ArrayObject|ItemInterface[] $calculableItems
     *
     * @return array|DiscountTotalItemInterface[]
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
     * @param \ArrayObject|ItemInterface[] $calculableItems
     * @param array|DiscountTotalItemInterface[]$discountTotalItemCollection
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
     * @param DiscountInterface                  $discountTransfer
     * @param array|DiscountTotalItemInterface[] $discountTotalItemCollection
     * @param int                                $quantity
     */
    protected function transformDiscountToDiscountTotalItemInArray(
        DiscountInterface $discountTransfer,
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
     * @param DiscountTotalItemTransfer $discountTotalItemTransfer
     * @param DiscountInterface $discountTransfer
     */
    protected function setUsedCodes(
        DiscountTotalItemTransfer $discountTotalItemTransfer,
        DiscountInterface $discountTransfer
    ) {
        $storedCodes = (array) $discountTotalItemTransfer->getCodes();
        foreach ($discountTransfer->getUsedCodes() as $code) {
            if (!in_array($code, $storedCodes)) {
                $discountTotalItemTransfer->addCode($code);
            }
        }
    }

    /**
     * @return DiscountTotalItemTransfer
     */
    protected function getDiscountTotalItem()
    {
        return new DiscountTotalItemTransfer();
    }

    /**
     * @param \ArrayObject|DiscountItemsInterface $discounts
     *
     * @return int
     */
    protected function sumItemDiscounts(\ArrayObject $discounts)
    {
        $discountAmount = 0;

        if ($discounts instanceof DiscountItemsInterface) {
            $discounts = $discounts->getDiscounts();
        }

        foreach ($discounts as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }

    /**
     * @param \ArrayObject|ExpenseInterface[] $expenses
     *
     * @return int
     */
    protected function sumItemExpenseDiscounts(\ArrayObject $expenses)
    {
        $discountAmount = 0;

        foreach ($expenses as $expense) {
            if ($expense instanceof ExpenseInterface) {
                foreach ($expense->getDiscounts() as $discount) {
                    $discountAmount += $discount->getAmount();
                }
            }
        }

        return $discountAmount;
    }

    /**
     * @param \ArrayObject|ProductOptionInterface[] $options
     *
     * @return int
     */
    protected function sumOptionDiscounts(\ArrayObject $options)
    {
        $discountAmount = 0;

        foreach ($options as $option) {
            if ($option instanceof ProductOptionInterface) {
                foreach ($option->getDiscounts() as $discount) {
                    $discountAmount += $discount->getAmount();
                }
            }
        }

        return $discountAmount;
    }

    /**
     * @param CalculableInterface $discountableContainer
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
