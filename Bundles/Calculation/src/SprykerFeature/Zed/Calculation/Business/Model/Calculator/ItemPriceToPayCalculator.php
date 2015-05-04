<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemInterfaceTransfer;

/**
 * Class ItemPriceToPayCalculator
 * @package SprykerFeature\Zed\Calculation\Business\Model\Calculator
 */
class ItemPriceToPayCalculator extends AbstractCalculator implements
    CalculatorPluginInterface
{
    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculate(CalculableContainerInterface $calculableContainer)
    {
        foreach ($calculableContainer->getItems() as $item) {
            $priceToPay = $item->getGrossPrice();
            $priceToPay -= $this->sumDiscounts($item);

            $priceToPay = max(0, $priceToPay);
            if ($priceToPay == 0) {
                $priceToPay = $item->getGrossPrice();
            }

            $priceToPay += $this->sumExpenses($item);
            $priceToPay += $this->sumOptions($item);

            $item->setPriceToPay($priceToPay);
        }
    }

    /**
     * @param CalculableItemInterface $item
     * @return int
     */
    protected function sumDiscounts(CalculableItemInterface $item)
    {
        $discountAmount = 0;
        foreach ($item->getDiscounts() as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }

    /**
     * @param CalculableItemInterface $item
     * @return int
     */
    protected function sumExpenses(CalculableItemInterface $item)
    {
        $expenseAmount = 0;
        foreach ($item->getExpenses() as $expense) {
            $expenseAmount += $expense->getPriceToPay();
        }

        return $expenseAmount;
    }

    /**
     * @param CalculableItemInterface $item
     * @return int
     */
    protected function sumOptions(CalculableItemInterface $item)
    {
        $optionsAmount = 0;
        foreach ($item->getOptions() as $option) {
            $optionsAmount += $option->getPriceToPay();
        }

        return $optionsAmount;
    }
}
