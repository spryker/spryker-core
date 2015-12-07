<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class ProductOptionPriceToPayCalculator implements CalculatorPluginInterface
{

    /**
     * @ param OrderInterface $calculableContainer
     *
     * @param CalculableInterface $calculableContainer
     *
     * @return void
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
     * @param ProductOptionTransfer $option
     *
     * @return int
     */
    protected function sumDiscounts(ProductOptionTransfer $option)
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
     * @return ItemTransfer[]
     */
    protected function getItems(CalculableInterface $calculableContainer)
    {
        return $calculableContainer->getCalculableObject()->getItems();
    }

}
