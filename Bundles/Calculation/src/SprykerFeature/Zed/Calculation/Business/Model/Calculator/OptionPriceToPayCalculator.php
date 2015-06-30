<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\OrderItemOptionInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class OptionPriceToPayCalculator implements
    CalculatorPluginInterface
{

    /**
     * @param OrderInterface $calculableContainer
     */
    public function recalculate(OrderInterface $calculableContainer)
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
     * @param OrderItemOptionInterface $option
     *
     * @return int
     */
    protected function sumDiscounts(OrderItemOptionInterface $option)
    {
        $discountAmount = 0;
        foreach ($option->getDiscounts() as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }
}
