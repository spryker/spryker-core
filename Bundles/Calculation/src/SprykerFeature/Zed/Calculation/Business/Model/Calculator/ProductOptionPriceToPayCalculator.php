<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\CartItemInterface;
use Generated\Shared\Calculation\OrderItemInterface;
use Generated\Shared\Calculation\ProductOptionInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class ProductOptionPriceToPayCalculator implements
    CalculatorPluginInterface
{

    /**
     * @ param OrderInterface $calculableContainer
     *
     * @param CalculableInterface $calculableContainer
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        $items = $this->getItems($calculableContainer);
        foreach ($items as $item) {
            foreach ($item->getProductOptions() as $option) {
                $priceToPay = $option->getGrossPrice();
                $priceToPay -= $this->sumDiscounts($option);
                $priceToPay = max(0, $priceToPay);
                $option->setPriceToPay($priceToPay);
            }
        }
    }

    /**
     * @param ProductOptionInterface $option
     *
     * @return int
     */
    protected function sumDiscounts(ProductOptionInterface $option)
    {
        $discountAmount = 0;
        foreach ($option->getDiscounts() as $discount) {
            $discountAmount += $discount->getAmount();
        }

        return $discountAmount;
    }

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return CartItemInterface[]|OrderItemInterface[]
     */
    protected function getItems(CalculableInterface $calculableContainer)
    {
        return $calculableContainer->getCalculableObject()->getItems();
    }
}
