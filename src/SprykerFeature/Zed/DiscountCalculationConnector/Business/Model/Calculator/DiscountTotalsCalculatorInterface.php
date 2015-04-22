<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

interface DiscountTotalsCalculatorInterface extends TotalsCalculatorPluginInterface
{
    /**
     * @param DiscountableContainerInterface $discountableContainer
     * @param DiscountableItemCollectionInterface $discountableItems
     */
    public function calculateDiscount(
        DiscountableContainerInterface $discountableContainer,
        DiscountableItemCollectionInterface $discountableItems
    );
}
