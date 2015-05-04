<?php
namespace SprykerFeature\Zed\Sales\Business\Model\Bundle;

use Generated\Shared\Transfer\SalesOrderTransfer;
use Generated\Shared\Transfer\SalesOrderItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemOptionTransfer;
use Generated\Shared\Transfer\CalculationDiscountTransfer;
use Generated\Shared\Transfer\CalculationExpenseTransfer;

class SplitBundleDistributor
{

    /**
     * @var float
     */
    protected $roundingErrorItemDiscounts = 0.00;
    /**
     * @var float
     */
    protected $roundingErrorOptionDiscounts = 0.00;
    /**
     * @var float
     */
    protected $roundingErrorExpenseDiscounts = 0.00;
    /**
     * @var float
     */
    protected $roundingErrorItemGrossPrice = 0.00;
    /**
     * @var float
     */
    protected $roundingErrorExpensesGrossPrice = 0.00;
    /**
     * @var float
     */
    protected $roundingErrorOptionsGrossPrice = 0.00;

    /**
     * @param OrderItem $bundleItem
     * @param Order $transferOrder
     */
    public function distribute(OrderItem $bundleItem, Order $transferOrder)
    {
        $items = $transferOrder->getItems();
        $this->distributeItemGrossPrice($bundleItem, $items);
    }

    /**
     * @param OrderItem $bundleItem
     * @param OrderItemCollection $items
     */
    protected function distributeItemGrossPrice(OrderItem $bundleItem, OrderItemCollection $items)
    {
        $bundleGrossPrice = $bundleItem->getGrossPrice();
        $itemsGrossPriceSum = $this->getItemGrossPriceSum($items);
        /* @var $item OrderItem */
        foreach($items as $item) {
            $itemGrossPrice = $item->getGrossPrice();
            $factor = $itemGrossPrice / $itemsGrossPriceSum;
            $proportionalGrossPrice = $bundleGrossPrice * $factor + $this->roundingErrorItemGrossPrice;
            $proportionalGrossPriceRounded = $this->roundAmountToInt($proportionalGrossPrice);
            $this->roundingErrorItemGrossPrice = $proportionalGrossPrice - $proportionalGrossPriceRounded;
            $item->setGrossPrice($proportionalGrossPriceRounded);

            $this->distributeItemOptions($bundleItem, $item, $factor);
            $this->distributeItemExpenses($bundleItem, $item, $factor);
            $this->distributeItemDiscounts($bundleItem, $item, $factor);
        }
    }

    /**
     * @param OrderItem $bundleItem
     * @param OrderItem $item
     * @param int $factor
     */
    protected function distributeItemOptions(OrderItem $bundleItem, OrderItem $item, $factor)
    {
        $bundleOptionsGrossPriceSum = $this->getItemOptionsSum($bundleItem);
        foreach($item->getOptions() as $option) {
            $proportionalGrossPrice = $bundleOptionsGrossPriceSum * $factor + $this->roundingErrorOptionsGrossPrice;
            $proportionalGrossPriceRounded = $this->roundAmountToInt($proportionalGrossPrice);
            $this->roundingErrorOptionsGrossPrice = $proportionalGrossPrice - $proportionalGrossPriceRounded;
            $option->setGrossPrice($proportionalGrossPriceRounded);
            $option->setPriceToPay(0);

            $this->distributeOptionDiscounts($bundleItem, $option, $factor);
        }
    }

    /**
     * @param OrderItem $bundleItem
     * @param OrderItem $item
     * @param int $factor
     */
    protected function distributeItemExpenses(OrderItem $bundleItem, OrderItem $item, $factor)
    {
        $bundleExpensesGrossPriceSum = $this->getItemExpensesSum($bundleItem);
        foreach($item->getExpenses() as $expense) {
            $proportionalGrossPrice = $bundleExpensesGrossPriceSum * $factor + $this->roundingErrorExpensesGrossPrice;
            $proportionalGrossPriceRounded = $this->roundAmountToInt($proportionalGrossPrice);
            $this->roundingErrorExpensesGrossPrice = $proportionalGrossPrice - $proportionalGrossPriceRounded;
            $expense->setGrossPrice($proportionalGrossPriceRounded);
            $expense->setPriceToPay(0);

            $this->distributeExpenseDiscounts($bundleItem, $expense, $factor);
        }
    }

    /**
     * @param OrderItem $bundleItem
     * @param Expense $expense
     * @param int $factor
     */
    protected function distributeExpenseDiscounts(OrderItem $bundleItem, Expense $expense, $factor)
    {
        $bundleExpenseDiscountSum = $this->getElementDiscountSum($bundleItem->getExpenses());
        foreach($expense->getDiscounts() as $discount) {
            $proportionalAmount = $bundleExpenseDiscountSum * $factor + $this->roundingErrorExpenseDiscounts;
            $proportionalAmountRounded = $this->roundAmountToInt($proportionalAmount);
            $this->roundingErrorExpenseDiscounts = $proportionalAmount - $proportionalAmountRounded;
            $discount->setAmount($proportionalAmountRounded);
        }
    }

    /**
     * @param OrderItem $bundleItem
     * @param OrderItemOption $option
     * @param int $factor
     */
    protected function distributeOptionDiscounts(OrderItem $bundleItem, OrderItemOption $option, $factor)
    {
        $bundleOptionsDiscountSum = $this->getElementDiscountSum($bundleItem->getOptions());
        foreach($option->getDiscounts() as $discount) {
            $proportionalAmount = $bundleOptionsDiscountSum * $factor + $this->roundingErrorOptionDiscounts;
            $proportionalAmountRounded = $this->roundAmountToInt($proportionalAmount);
            $this->roundingErrorOptionDiscounts = $proportionalAmount - $proportionalAmountRounded;
            $discount->setAmount($proportionalAmountRounded);
        }
    }

    /**
     * @param OrderItem $bundleItem
     * @param OrderItem $item
     * @param int $factor
     */
    protected function distributeItemDiscounts(OrderItem $bundleItem, OrderItem $item, $factor)
    {
        $bundleDiscountSum = $this->getItemDiscountSum($bundleItem);
        foreach($item->getDiscounts() as $discount) {
            $proportionalAmount = ($bundleDiscountSum * $factor) + $this->roundingErrorItemDiscounts;
            $proportionalAmountRounded = $this->roundAmountToInt($proportionalAmount);
            $this->roundingErrorItemDiscounts = $proportionalAmount - $proportionalAmountRounded;
            $discount->setAmount($proportionalAmountRounded);
        }
    }

    /**
     * @param OrderItemCollection $items
     *
     * @return int
     */
    protected function getItemGrossPriceSum(OrderItemCollection $items)
    {
        $grossPriceSum = 0;

        foreach($items as $item) {
            $grossPriceSum += $item->getGrossPrice();
        }

        return $grossPriceSum;
    }

    /**
     * @param OrderItem $bundleItem
     *
     * @return int
     */
    protected function getItemOptionsSum(OrderItem $bundleItem)
    {
        $grossPriceSum = 0;

        foreach($bundleItem->getOptions() as $option) {
            $grossPriceSum += $option->getGrossPrice();
        }

        return $grossPriceSum;
    }

    /**
     * @param OrderItem $bundleItem
     *
     * @return int
     */
    protected function getItemExpensesSum(OrderItem $bundleItem)
    {
        $grossPriceSum = 0;

        foreach($bundleItem->getExpenses() as $expense) {
            $grossPriceSum += $expense->getGrossPrice();
        }

        return $grossPriceSum;
    }

    /**
     * @param OrderItem $bundleItem
     *
     * @return int
     */
    protected function getItemDiscountSum(OrderItem $bundleItem)
    {
        $discountSum = 0;

        foreach($bundleItem->getDiscounts() as $discount) {
            $discountSum += $discount->getAmount();
        }

        return $discountSum;
    }

    /**
     * @param DiscountableInterface $element
     *
     * @return int
     */
    protected function getElementDiscountSum(DiscountableInterface $element)
    {
        $discountSum = 0;

        foreach($element->getDiscounts() as $discount) {
            $discountSum += $discount->getAmount();
        }

        return $discountSum;
    }

    /**
     * @param float $amount
     *
     * @return int
     */
    protected function roundAmountToInt($amount)
    {
        return ((int) round($amount, 2));
    }
}
