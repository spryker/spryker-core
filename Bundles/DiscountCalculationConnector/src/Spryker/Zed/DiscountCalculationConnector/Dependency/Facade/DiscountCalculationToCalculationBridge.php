<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class DiscountCalculationToCalculationBridge implements DiscountCalculationToCalculationInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Business\CalculationFacade
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\Calculation\Business\CalculationFacade $calculationFacade
     */
    public function __construct($calculationFacade)
    {
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     *
     * @return void
     */
    public function recalculateGrandTotalTotals(TotalsTransfer $totalsTransfer, CalculableInterface $calculableContainer, $calculableItems)
    {
        $this->calculationFacade->recalculateGrandTotalTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }
}
