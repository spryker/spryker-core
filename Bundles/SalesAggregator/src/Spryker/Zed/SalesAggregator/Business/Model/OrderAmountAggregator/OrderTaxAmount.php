<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface;

class OrderTaxAmount implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface $taxFacade
     */
    public function __construct(SalesAggregatorToTaxInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->assertOrderTaxAmountRequirements($orderTransfer);

        $orderEffectiveTaxRate = $this->getOrderEffectiveTaxRate($orderTransfer);

        $totalTaxAmount = $this->getTotalTaxAmount($orderTransfer, $orderEffectiveTaxRate);

        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount($totalTaxAmount);
        $taxTotalTransfer->setTaxRate($orderEffectiveTaxRate);

        $totalsTransfer = $orderTransfer->getTotals();
        $totalsTransfer->setTaxTotal($taxTotalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getOrderEffectiveTaxRate(OrderTransfer $orderTransfer)
    {
        $itemEffectiveRates = $this->getEfectiveTaxRatesFromTaxableItems($orderTransfer->getItems());
        $expenseEffectiveRates = $this->getEfectiveTaxRatesFromTaxableItems($orderTransfer->getExpenses());
        $taxRates = array_merge($itemEffectiveRates, $expenseEffectiveRates);

        $totalTaxRate = array_sum($taxRates);
        if (empty($totalTaxRate)) {
            return 0;
        }

        $effectiveTaxRate = $totalTaxRate / count($taxRates);

        return $effectiveTaxRate;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]|\Generated\Shared\Transfer\ExpenseTransfer[] $taxableItems
     *
     * @return array|int[]
     */
    protected function getEfectiveTaxRatesFromTaxableItems(\ArrayObject $taxableItems)
    {
        $taxRates = [];
        foreach ($taxableItems as $item) {
            if (!empty($item->getTaxRate())) {
                $taxRates[] = $item->getTaxRate();
            }
        }

        return $taxRates;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $orderEffectiveTaxRate
     *
     * @return int
     */
    protected function getTotalTaxAmount(OrderTransfer $orderTransfer, $orderEffectiveTaxRate)
    {
        return $this->taxFacade->getTaxAmountFromGrossPrice(
            $orderTransfer->getTotals()->getGrandTotal(),
            $orderEffectiveTaxRate
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function assertOrderTaxAmountRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireTotals();
        $orderTransfer->getTotals()->requireGrandTotal();
    }

}
