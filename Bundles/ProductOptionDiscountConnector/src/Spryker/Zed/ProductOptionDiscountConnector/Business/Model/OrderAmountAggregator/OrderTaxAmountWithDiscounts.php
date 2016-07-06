<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface;

class OrderTaxAmountWithDiscounts implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface $taxFacade
     */
    public function __construct(ProductOptionToTaxInterface $taxFacade)
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
        $orderEffectiveTaxRate = $this->getOrderEffectiveTaxRate($orderTransfer);
        $totalTaxAmount = $this->getTotalTaxAmount($orderTransfer, $orderEffectiveTaxRate);

        $this->setTaxTotals($orderTransfer, $orderEffectiveTaxRate, $totalTaxAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getOrderEffectiveTaxRate(OrderTransfer $orderTransfer)
    {
        $itemEffectiveRates = $this->getEffectiveTaxRatesFromTaxableItems($orderTransfer->getItems());
        $expenseEffectiveRates = $this->getEffectiveTaxRatesFromTaxableItems($orderTransfer->getExpenses());
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
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]|\Generated\Shared\Transfer\ExpenseTransfer[]|\Generated\Shared\Transfer\ProductOptionTransfer[] $taxableItems
     *
     * @return int[]
     */
    protected function getEffectiveTaxRatesFromTaxableItems(\ArrayObject $taxableItems)
    {
        $taxRates = [];
        foreach ($taxableItems as $item) {
            if ($item->getTaxRate()) {
                $taxRates[] = $item->getTaxRate();
            }
        }

        return $taxRates;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return int[]
     */
    protected function getProductOptionEffectiveRates(\ArrayObject $items)
    {
        $productOptionRates = [];
        foreach ($items as $itemTransfer) {
            $rates = $this->getEffectiveTaxRatesFromTaxableItems($itemTransfer->getProductOptions());
            $productOptionRates = array_merge($productOptionRates, $rates);
        }

        return $productOptionRates;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $orderEffectiveTaxRate
     *
     * @return int
     */
    protected function getTotalTaxAmount(OrderTransfer $orderTransfer, $orderEffectiveTaxRate)
    {
        $orderTransfer->requireTotals();
        $orderTransfer->getTotals()->requireGrandTotal();

        return $this->taxFacade->getTaxAmountFromGrossPrice(
            $orderTransfer->getTotals()->getGrandTotal(),
            $orderEffectiveTaxRate
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $orderEffectiveTaxRate
     * @param int $totalTaxAmount
     *
     * @return void
     */
    protected function setTaxTotals(OrderTransfer $orderTransfer, $orderEffectiveTaxRate, $totalTaxAmount)
    {
        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount($totalTaxAmount);
        $taxTotalTransfer->setTaxRate($orderEffectiveTaxRate);

        $orderTransfer->getTotals()->setTaxTotal($taxTotalTransfer);
    }

}
