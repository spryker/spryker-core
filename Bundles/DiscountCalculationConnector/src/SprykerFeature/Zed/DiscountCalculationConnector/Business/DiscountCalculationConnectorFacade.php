<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business;

use Generated\Shared\DiscountCalculationConnector\OrderInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class DiscountCalculationConnectorFacade extends AbstractFacade
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $discountableContainer
     * @param \ArrayObject $discountableContainers
     */
    public function recalculateDiscountTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $discountableContainer,
        \ArrayObject $discountableContainers
    ) {
        $calculator = $this->getDependencyContainer()->getDiscountTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $discountableContainer, $discountableContainers);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $container
     * @param \ArrayObject $items
     */
    public function recalculateGrandTotalWithDiscountsTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $container,
        \ArrayObject $items
    ) {
        $calculator = $this->getDependencyContainer()->getGrandTotalWithDiscountsTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $container, $items);
    }

    /**
     * @param OrderInterface $container
     */
    public function recalculateRemoveAllCalculatedDiscounts(OrderInterface $container)
    {
        $calculator = $this->getDependencyContainer()->getRemoveAllCalculatedDiscountsCalculator();
        $calculator->recalculate($container);
    }
}
