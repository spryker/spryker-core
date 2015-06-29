<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

interface DiscountTotalsCalculatorInterface extends TotalsCalculatorPluginInterface
{
    /**
     * @param OrderInterface $discountableContainer
     * @param \ArrayObject $discountableItems
     */
    public function calculateDiscount(
        OrderInterface $discountableContainer,
        \ArrayObject $discountableItems
    );
}
