<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Calculation\CalculationDiscountInterface;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\DiscountTotalItemTransfer;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\OptionContainerInterface;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableExpenseInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemInterface;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

class DiscountTotalsCalculator implements DiscountTotalsCalculatorInterface
{
    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $discountableContainer
     * @param \ArrayObject $discountableContainers
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $discountableContainer,
        \ArrayObject $discountableContainers
    ) {
        if ($discountableContainer instanceof DiscountableContainerInterface) {
            $discount = $this->createDiscountTransfer($discountableContainer, $discountableContainers);
            $totalsTransfer->setDiscount($discount);
        }
    }

    /**
     * @param DiscountableContainerInterface $discountableContainer
     * @param \ArrayObject $discountableItems
     *
     * @return array|int
     */
    public function calculateDiscount(
        DiscountableContainerInterface $discountableContainer,
        \ArrayObject $discountableItems
    ) {
        $discountAmount = 0;

        foreach ($discountableItems as $item) {
            $discountAmount += $this->sumItemDiscounts($item);
            $discountAmount += $this->sumItemExpenseDiscounts($item);
            $discountAmount += $this->sumOptionDiscounts($item);
        }

        $discountAmount += $this->sumTotalExpenseDiscounts($discountableContainer);

        return $discountAmount;
    }

    /**
     * @param DiscountableContainerInterface $discountableContainer
     * @param \ArrayObject $discountableContainers
     *
     * @return DiscountTotalsTransfer
     */
    protected function createDiscountTransfer(
        DiscountableContainerInterface $discountableContainer,
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
     * @param DiscountableContainerInterface $discountableContainer
     * @param \ArrayObject|DiscountableItemInterface[] $discountableItems
     *
     * @return array
     */
    protected function sumDiscountItems(
        DiscountableContainerInterface $discountableContainer,
        \ArrayObject $discountableItems
    ) {
        $orderExpenseItems = [];

        foreach ($discountableContainer->getDiscounts() as $discount) {
            $this->transformDiscountToDiscountTotalItemInArray($discount, $orderExpenseItems);
        }

        foreach ($discountableContainer->getExpenses() as $expenses) {
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
     * @param CalculationDiscountInterface $discount
     * @param $arrayOfExpenseTotalItems
     */
    protected function transformDiscountToDiscountTotalItemInArray(
        CalculationDiscountInterface $discount,
        &$arrayOfExpenseTotalItems
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
     * @param DiscountableItemInterface $item
     *
     * @return int
     */
    protected function sumItemDiscounts(DiscountableItemInterface $item)
    {
        $discountAmount = 0;

        foreach ($item->getDiscounts() as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }

    /**
     * @param CalculableItemInterface $item
     *
     * @return array
     */
    protected function sumItemExpenseDiscounts(CalculableItemInterface $item)
    {
        $discountAmount = 0;

        foreach ($item->getExpenses() as $expense) {
            if ($expense instanceof DiscountableExpenseInterface) {
                foreach ($expense->getDiscounts() as $discount) {
                    $discountAmount += $discount->getAmount();
                }
            }
        }
        return $discountAmount;
    }

    /**
     * @param OptionContainerInterface $item
     *
     * @return int
     */
    protected function sumOptionDiscounts(OptionContainerInterface $item)
    {
        $discountAmount = 0;

        foreach ($item->getOptions() as $option) {
            if ($option instanceof DiscountableInterface) {
                foreach ($option->getDiscounts() as $discount) {
                    $discountAmount += $discount->getAmount();
                }
            }
        }

        return $discountAmount;
    }

    /**
     * @param DiscountableContainerInterface $discountableContainer
     *
     * @return int
     */
    protected function sumTotalExpenseDiscounts(DiscountableContainerInterface $discountableContainer)
    {
        $discountAmount = 0;

        foreach ($discountableContainer->getExpenses() as $expense) {
            foreach ($expense->getDiscounts() as $discount) {
                $discountAmount += $discount->getAmount();
            }
        }

        return $discountAmount;
    }
}
