<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountableItemCollectionInterfaceTransfer;
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
