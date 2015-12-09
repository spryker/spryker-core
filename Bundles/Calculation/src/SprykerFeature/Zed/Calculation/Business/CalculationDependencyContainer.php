<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business;

use SprykerFeature\Zed\Calculation\Business\Model\PriceCalculationHelper;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ItemTotalPriceCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\StackExecutor;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ExpensePriceToPayCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ItemPriceToPayCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ProductOptionPriceToPayCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\RemoveAllExpensesCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalWithoutItemExpensesTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\TaxTotalsCalculator;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Calculation\CalculationConfig;

/**
 * @method CalculationConfig getConfig()
 */
class CalculationDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return StackExecutor
     */
    public function getStackExecutor()
    {
        return new StackExecutor();
    }

    /**
     * @return ExpensePriceToPayCalculator
     */
    public function getExpensePriceToPayCalculator()
    {
        return new ExpensePriceToPayCalculator();
    }

    /**
     * @return ExpenseTotalsCalculator
     */
    public function getExpenseTotalsCalculator()
    {
        return new ExpenseTotalsCalculator();
    }

    /**
     * @return GrandTotalTotalsCalculator
     */
    public function getGrandTotalsCalculator()
    {
        $subtotalTotalsCalculator = $this->createSubTotalsCalculator();
        $expenseTotalsCalculator = $this->createExpenseTotalsCalculator();

        $grandTotalsCalculator = new GrandTotalTotalsCalculator(
            $subtotalTotalsCalculator,
            $expenseTotalsCalculator
        );

        return $grandTotalsCalculator;
    }

    /**
     * @return ItemPriceToPayCalculator
     */
    public function getItemPriceToPayCalculator()
    {
        return new ItemPriceToPayCalculator();
    }

    /**
     * @return ProductOptionPriceToPayCalculator
     */
    public function getOptionPriceToPayCalculator()
    {
        return new ProductOptionPriceToPayCalculator();
    }

    /**
     * @return RemoveAllExpensesCalculator
     */
    public function getRemoveAllExpensesCalculator()
    {
        return new RemoveAllExpensesCalculator();
    }

    /**
     * @return RemoveTotalsCalculator
     */
    public function getRemoveTotalsCalculator()
    {
        return new RemoveTotalsCalculator();
    }

    /**
     * @return SubtotalTotalsCalculator
     */
    public function getSubtotalTotalsCalculator()
    {
        return new SubtotalTotalsCalculator();
    }

    /**
     * @return ItemTotalPriceCalculator
     */
    public function getItemTotalCalculator()
    {
        return new ItemTotalPriceCalculator();
    }

    /**
     * @return SubtotalWithoutItemExpensesTotalsCalculator
     */
    public function getSubtotalWithoutItemExpensesTotalsCalculator()
    {
        return new SubtotalWithoutItemExpensesTotalsCalculator();
    }

    /**
     * @return TaxTotalsCalculator
     */
    public function getTaxTotalsCalculator()
    {
        return new TaxTotalsCalculator(
            $this->createPriceCalculationHelper()
        );
    }

    /**
     * @return SubtotalTotalsCalculator
     */
    protected function createSubTotalsCalculator()
    {
        $subtotalTotalsCalculator = new SubtotalTotalsCalculator();

        return $subtotalTotalsCalculator;
    }

    /**
     * @return ExpenseTotalsCalculator
     */
    protected function createExpenseTotalsCalculator()
    {
        $expenseTotalsCalculator = new ExpenseTotalsCalculator();

        return $expenseTotalsCalculator;
    }

    /**
     * @return PriceCalculationHelper
     */
    protected function createPriceCalculationHelper()
    {
        return new PriceCalculationHelper();
    }

}
