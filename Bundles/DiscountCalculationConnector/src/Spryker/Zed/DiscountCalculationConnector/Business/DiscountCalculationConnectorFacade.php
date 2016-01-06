<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

/**
 * @method DiscountCalculationConnectorBusinessFactory getFactory()
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
        $calculator = $this->getFactory()->createDiscountTotalsCalculator();
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
        $calculator = $this->getFactory()->createGrandTotalWithDiscountsTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $container, $items);
    }

    /**
     * @param CalculableInterface $container
     *
     * @return void
     */
    public function recalculateRemoveAllCalculatedDiscounts(CalculableInterface $container)
    {
        $calculator = $this->getFactory()->createRemoveAllCalculatedDiscountsCalculator();
        $calculator->recalculate($container);
    }

}
