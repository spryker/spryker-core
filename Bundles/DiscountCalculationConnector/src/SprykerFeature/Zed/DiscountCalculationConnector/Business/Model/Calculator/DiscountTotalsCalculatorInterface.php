<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

interface DiscountTotalsCalculatorInterface extends TotalsCalculatorPluginInterface
{

    /**
     * @param CalculableInterface $discountableContainer
     * @param $discountableItems
     */
    public function calculateDiscount(
        CalculableInterface $discountableContainer,
        $discountableItems
    );

}
