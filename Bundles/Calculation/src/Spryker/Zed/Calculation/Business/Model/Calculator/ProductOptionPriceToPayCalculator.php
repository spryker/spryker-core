<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class ProductOptionPriceToPayCalculator implements CalculatorPluginInterface
{

    /**
     * @ param OrderInterface $calculableContainer
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
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
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $option
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
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItems(CalculableInterface $calculableContainer)
    {
        return $calculableContainer->getCalculableObject()->getItems();
    }

}
