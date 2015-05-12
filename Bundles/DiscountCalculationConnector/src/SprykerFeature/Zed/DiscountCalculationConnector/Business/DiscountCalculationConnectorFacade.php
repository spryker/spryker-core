<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class DiscountCalculationConnectorFacade extends AbstractFacade
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param DiscountableContainerInterface $discountableContainer
     * @param \ArrayObject $discountableContainers
     */
    public function recalculateDiscountTotals(
        TotalsInterface $totalsTransfer,
        DiscountableContainerInterface $discountableContainer,
        \ArrayObject $discountableContainers
    ) {
        $calculator = $this->getDependencyContainer()->getDiscountTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $discountableContainer, $discountableContainers);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param DiscountableContainerInterface $container
     * @param \ArrayObject $items
     */
    public function recalculateGrandTotalWithDiscountsTotals(
        TotalsInterface $totalsTransfer,
        DiscountableContainerInterface $container,
        \ArrayObject $items
    ) {
        $calculator = $this->getDependencyContainer()->getGrandTotalWithDiscountsTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $container, $items);
    }

    /**
     * @param DiscountableContainerInterface $container
     */
    public function recalculateRemoveAllCalculatedDiscounts(DiscountableContainerInterface $container)
    {
        $calculator = $this->getDependencyContainer()->getRemoveAllCalculatedDiscountsCalculator();
        $calculator->recalculate($container);
    }
}
