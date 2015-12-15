<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

interface DiscountTotalsCalculatorInterface extends TotalsCalculatorPluginInterface
{

    /**
     * @param CalculableInterface $discountableContainer
     * @param \ArrayObject $calculableItems
     */
    public function calculateDiscount(CalculableInterface $discountableContainer, \ArrayObject $calculableItems);

}
