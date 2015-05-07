<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;
use Generated\Shared\Transfer\CalculationDiscountTotalsTransfer;
use Generated\Shared\Transfer\SalesDiscountTotalItemTransfer;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\OptionContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableExpenseInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountItemInterface;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\AbstractCalculator;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

class DiscountTotalsCalculator extends AbstractCalculator implements DiscountTotalsCalculatorInterface
{
    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $discountableContainer
     * @param CalculableItemCollectionInterface $discountableContainers
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $discountableContainer,
        CalculableItemCollectionInterface $discountableContainers
    ) {
        if ($discountableContainer instanceof DiscountableContainerInterface &&
            $discountableContainers instanceof DiscountableItemCollectionInterface) {
            $discount = $this->createDiscountTransfer($discountableContainer, $discountableContainers);
            $totalsTransfer->setDiscount($discount);
        }
    }

    /**
     * @param DiscountableContainerInterface $discountableContainer
     * @param DiscountableItemCollectionInterface|DiscountableItemInterface[] $discountableItems
     * @return int
     */
    public function calculateDiscount(
        DiscountableContainerInterface $discountableContainer,
        DiscountableItemCollectionInterface $discountableItems
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
     * @param DiscountableItemCollectionInterface $discountableContainers
     * @return CalculationDiscountTotalsTransfer
     */
    protected function createDiscountTransfer(
        DiscountableContainerInterface $discountableContainer,
        DiscountableItemCollectionInterface $discountableContainers
    ) {
        $discountTransfer = new CalculationDiscountTotalsTransfer();
        $discountTransfer->setTotalAmount($this->calculateDiscount($discountableContainer, $discountableContainers));

        foreach ($this->sumDiscountItems($discountableContainer, $discountableContainers) as $discountTotalItem) {
            $discountTransfer->addDiscountItem($discountTotalItem);
        }

        return $discountTransfer;
    }

    /**
     * @param DiscountableContainerInterface $discountableContainer
     * @param DiscountableItemCollectionInterface|DiscountableItemInterface[] $discountableItems
     *
     * @return array
     */
    protected function sumDiscountItems(
        DiscountableContainerInterface $discountableContainer,
        DiscountableItemCollectionInterface $discountableItems
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
     * @param DiscountItemInterface $discount
     * @param $arrayOfExpenseTotalItems
     */
    protected function transformDiscountToDiscountTotalItemInArray(
        DiscountItemInterface $discount,
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
     * @return SalesDiscountTotalItemTransfer
     */
    protected function getDiscountTotalItem()
    {
        return new SalesDiscountTotalItemTransfer();
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
            if ($option instanceof DiscountableInterface)
            foreach ($option->getDiscounts() as $discount) {
                $discountAmount += $discount->getAmount();
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
