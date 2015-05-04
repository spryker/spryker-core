<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyOptionItemInterfaceTransfer;

/**
 * Class OptionPriceToPayCalculator
 * @package SprykerFeature\Zed\Calculation\Business\Model\Calculator
 */
class OptionPriceToPayCalculator extends AbstractCalculator implements
    CalculatorPluginInterface
{

    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculate(CalculableContainerInterface $calculableContainer)
    {
        foreach ($calculableContainer->getItems() as $item) {
            foreach ($item->getOptions() as $option) {
                $priceToPay = $option->getGrossPrice();
                $priceToPay -= $this->sumDiscounts($option);

                $priceToPay = max(0, $priceToPay);
                $option->setPriceToPay($priceToPay);
            }
        }
    }

    /**
     * @param OptionItemInterface $option
     * @return int
     */
    protected function sumDiscounts(OptionItemInterface $option)
    {
        $discountAmount = 0;
        foreach ($option->getDiscounts() as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }
}
