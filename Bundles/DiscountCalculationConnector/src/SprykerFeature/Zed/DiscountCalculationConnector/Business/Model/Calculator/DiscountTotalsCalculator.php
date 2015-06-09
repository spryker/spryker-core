<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Calculation\DiscountItemsInterface;
use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;
use Generated\Shared\DiscountCalculationConnector\DiscountInterface;
use Generated\Shared\DiscountCalculationConnector\ExpenseInterface;
use Generated\Shared\DiscountCalculationConnector\OrderItemOptionInterface;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\DiscountTotalItemTransfer;
use Generated\Shared\Sales\OrderItemsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class DiscountTotalsCalculator implements DiscountTotalsCalculatorInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @ param OrderInterface $discountableContainer
     * @param CalculableInterface $discountableContainer
     * @param \ArrayObject $discountableContainers
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        //OrderInterface $discountableContainer,
        CalculableInterface $discountableContainer,
        \ArrayObject $discountableContainers
    ) {
        $discount = $this->createDiscountTransfer($discountableContainer, $discountableContainers);
        $totalsTransfer->setDiscount($discount);
    }

    /**
     * @ param OrderInterface $discountableContainer
     * @param CalculableInterface $discountableContainer
     * @param \ArrayObject $discountableItems
     *
     * @return array|int
     */
    public function calculateDiscount(
        CalculableInterface $discountableContainer,
        //OrderInterface $discountableContainer,
        \ArrayObject $discountableItems
    ) {
        $discountAmount = 0;

        if ($discountableItems instanceof OrderItemsInterface) {
            $discountableItems = $discountableItems->getOrderItems();
        }

        foreach ($discountableItems as $item) {
            $discountAmount += $this->sumItemDiscounts($item->getDiscounts());
            $discountAmount += $this->sumItemExpenseDiscounts($item->getExpenses());
            $discountAmount += $this->sumOptionDiscounts($item->getOptions());
        }

        $discountAmount += $this->sumTotalExpenseDiscounts($discountableContainer);

        return $discountAmount;
    }

    /**
     * @ param OrderInterface $discountableContainer
     * @param CalculableInterface $discountableContainer
     * @param \ArrayObject $discountableContainers
     *
     * @return DiscountTotalsTransfer
     */
    protected function createDiscountTransfer(
        //OrderInterface $discountableContainer,
        CalculableInterface $discountableContainer,
        \ArrayObject $discountableContainers
    ) {
        $discountTransfer = new DiscountTotalsTransfer();
        $discountTransfer->setTotalAmount($this->calculateDiscount($discountableContainer, $discountableContainers));

        foreach ($this->sumDiscountItems($discountableContainer, $discountableContainers) as $discountTotalItem) {
            $discountTransfer->addDiscountItem($discountTotalItem);
        }

        return $discountTransfer;
    }

    /**
     * @ param OrderInterface $discountableContainer
     * @param CalculableInterface $discountableContainer
     * @param \ArrayObject $discountableItems
     *
     * @return array
     */
    protected function sumDiscountItems(
        //OrderInterface $discountableContainer,
        CalculableInterface $discountableContainer,
        \ArrayObject $discountableItems
    ) {
        $orderExpenseItems = [];

        foreach ($discountableContainer->getCalculableObject()->getDiscounts() as $discount) {
            $this->transformDiscountToDiscountTotalItemInArray($discount, $orderExpenseItems);
        }

        foreach ($discountableContainer->getCalculableObject()->getExpenses() as $expenses) {
            foreach ($expenses->getDiscounts() as $discount) {
                $this->transformDiscountToDiscountTotalItemInArray($discount, $orderExpenseItems);
            }
        }

        foreach ($discountableItems as $container) {
            foreach ($container->getDiscounts() as $discount) {
                $this->transformDiscountToDiscountTotalItemInArray($discount, $orderExpenseItems);
            }

            foreach ($container->getOptions() as $option) {
                foreach ($option->getDiscounts() as $discount) {
                    $this->transformDiscountToDiscountTotalItemInArray($discount, $orderExpenseItems);
                }
            }

            foreach ($container->getExpenses() as $expenses) {
                foreach ($expenses->getDiscounts() as $discount) {
                    $this->transformDiscountToDiscountTotalItemInArray($discount, $orderExpenseItems);
                }
            }
        }

        return $orderExpenseItems;
    }

    /**
     * @param DiscountInterface $discount
     * @param array $arrayOfExpenseTotalItems
     */
    protected function transformDiscountToDiscountTotalItemInArray(
        DiscountInterface $discount,
        array &$arrayOfExpenseTotalItems
    ) {
        if (!isset($arrayOfExpenseTotalItems[$discount->getDisplayName()])) {
            $item = $this->getDiscountTotalItem();
            $item->setName($discount->getDisplayName());
        } else {
            $item = $arrayOfExpenseTotalItems[$discount->getDisplayName()];
        }
        $item->setAmount($item->getAmount() + $discount->getAmount());
        $arrayOfExpenseTotalItems[$discount->getDisplayName()] = $item;
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
     * @param \ArrayObject|OrderItemOptionInterface[] $options
     *
     * @return int
     */
    protected function sumOptionDiscounts(\ArrayObject $options)
    {
        $discountAmount = 0;

        foreach ($options as $option) {
            if ($option instanceof OrderItemOptionInterface) {
                foreach ($option->getDiscounts() as $discount) {
                    $discountAmount += $discount->getAmount();
                }
            }
        }

        return $discountAmount;
    }

    /**
     * @ param OrderInterface $discountableContainer
     * @param CalculableInterface $discountableContainer
     *
     * @return int
     */
    protected function sumTotalExpenseDiscounts(CalculableInterface $discountableContainer)
    //protected function sumTotalExpenseDiscounts(OrderInterface $discountableContainer)
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
