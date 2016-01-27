<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxBridgeInterface;

class OrderTaxAmountWithDiscounts
{
    /**
     * @var ProductOptionToTaxBridgeInterface
     */
    protected $taxFacade;

    /**
     * @param ProductOptionToTaxBridgeInterface $taxFacade
     */
    public function __construct(ProductOptionToTaxBridgeInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $orderEffectiveTaxRate = $this->getOrderEffectiveTaxRate($orderTransfer);

        $totalTaxAmount = $this->getTotalTaxAmount($orderTransfer, $orderEffectiveTaxRate);

        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount($totalTaxAmount);
        $taxTotalTransfer->setTaxRate($orderEffectiveTaxRate);

        $totalsTransfer = $orderTransfer->getTotals();
        $totalsTransfer->setTaxTotal($taxTotalTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getOrderEffectiveTaxRate(OrderTransfer $orderTransfer)
    {
        $itemEffectiveRates = $this->getEfectiveTaxRatesFromTaxableItems($orderTransfer->getItems());
        $expenseEffectiveRates = $this->getEfectiveTaxRatesFromTaxableItems($orderTransfer->getExpenses());
        $productOptionEffectiveRates = $this->getProductOptionEffectiveRates($orderTransfer->getItems());

        $taxRates = array_merge($itemEffectiveRates, $expenseEffectiveRates, $productOptionEffectiveRates);

        $totalTaxRate = array_sum($taxRates);
        if (empty($totalTaxRate)) {
            return 0;
        }

        $effectiveTaxRate = $totalTaxRate / count($taxRates);

        return $effectiveTaxRate;
    }

    /**
     * @param \ArrayObject|ItemTransfer[]|ExpenseTransfer[]|ProductOptionTransfer[] $taxableItems
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
     * @param \ArrayObject|ItemTransfer[] $items
     *
     * @return array|int[]
     */
    protected function getProductOptionEffectiveRates(\ArrayObject $items)
    {
        $productOptionRates = [];
        foreach ($items as $itemTransfer) {
            $rates = $this->getEfectiveTaxRatesFromTaxableItems($itemTransfer->getProductOptions());
            $productOptionRates = array_merge($productOptionRates, $rates);
        }

        return $productOptionRates;
    }

    /**
     * @param OrderTransfer $orderTransfer
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
}
