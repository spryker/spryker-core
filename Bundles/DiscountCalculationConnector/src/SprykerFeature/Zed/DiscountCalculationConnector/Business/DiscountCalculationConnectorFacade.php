<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class DiscountCalculationConnectorFacade extends AbstractFacade
{

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $discountableContainer
     * @param \ArrayObject $discountableContainers
     *
     * @return void
     */
    public function recalculateDiscountTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $discountableContainer,
        \ArrayObject $discountableContainers
    ) {
        $calculator = $this->getDependencyContainer()->getDiscountTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $discountableContainer, $discountableContainers);
    }

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $container
     * @param \ArrayObject $items
     *
     * @return void
     */
    public function recalculateGrandTotalWithDiscountsTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $container,
        \ArrayObject $items
    ) {
        $calculator = $this->getDependencyContainer()->getGrandTotalWithDiscountsTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $container, $items);
    }

    /**
     * @param CalculableInterface $container
     *
     * @return void
     */
    public function recalculateRemoveAllCalculatedDiscounts(CalculableInterface $container)
    {
        $calculator = $this->getDependencyContainer()->getRemoveAllCalculatedDiscountsCalculator();
        $calculator->recalculate($container);
    }

}
