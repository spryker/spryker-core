<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Spryker\Zed\Calculation\Business\CalculationFacade;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class DiscountCalculationToCalculationBridge implements DiscountCalculationToCalculationInterface
{

    /**
     * @var CalculationFacade
     */
    protected $calculationFacade;

    /**
     * @param CalculationFacade $calculationFacade
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
