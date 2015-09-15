<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business;

use Generated\Shared\Calculation\CalculableContainerInterface;
use Generated\Shared\DiscountCalculationConnector\OrderInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class DiscountCalculationConnectorFacade extends AbstractFacade
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableInterface $discountableContainer
     * @param \ArrayObject $discountableContainers
     */
    public function recalculateDiscountTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $discountableContainer,
        \ArrayObject $discountableContainers
    ) {
        $calculator = $this->getDependencyContainer()->getDiscountTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $discountableContainer, $discountableContainers);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableInterface $container
     * @param \ArrayObject $items
     */
    public function recalculateGrandTotalWithDiscountsTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $container,
        \ArrayObject $items
    ) {
        $calculator = $this->getDependencyContainer()->getGrandTotalWithDiscountsTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $container, $items);
    }

    /**
     * @param CalculableInterface $container
     */
    public function recalculateRemoveAllCalculatedDiscounts(CalculableInterface $container)
    {
        $calculator = $this->getDependencyContainer()->getRemoveAllCalculatedDiscountsCalculator();
        $calculator->recalculate($container);
    }
}
