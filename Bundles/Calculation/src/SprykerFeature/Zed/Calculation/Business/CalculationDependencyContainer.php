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
        $stackExecutor = new StackExecutor();

        return $stackExecutor;
    }

    /**
     * @return ExpensePriceToPayCalculator
     */
    public function getExpensePriceToPayCalculator()
    {
        $expensePriceToPayCalculator = new ExpensePriceToPayCalculator();

        return $expensePriceToPayCalculator;
    }

    /**
     * @return ExpenseTotalsCalculator
     */
    public function getExpenseTotalsCalculator()
    {
        $expenseTotalsCalculator = new ExpenseTotalsCalculator();

        return $expenseTotalsCalculator;
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
        $itemPriceToPayCalculator = new ItemPriceToPayCalculator();

        return $itemPriceToPayCalculator;
    }

    /**
     * @return ProductOptionPriceToPayCalculator
     */
    public function getOptionPriceToPayCalculator()
    {
        $productOptionPriceToPayCalculator = new ProductOptionPriceToPayCalculator();

        return $productOptionPriceToPayCalculator;
    }

    /**
     * @return RemoveAllExpensesCalculator
     */
    public function getRemoveAllExpensesCalculator()
    {
        $removeAllExpensesCalculator = new RemoveAllExpensesCalculator();

        return $removeAllExpensesCalculator;
    }

    /**
     * @return RemoveTotalsCalculator
     */
    public function getRemoveTotalsCalculator()
    {
        $removeTotalsCalculator = new RemoveTotalsCalculator();

        return $removeTotalsCalculator;
    }

    /**
     * @return SubtotalTotalsCalculator
     */
    public function getSubtotalTotalsCalculator()
    {
        $subtotalTotalsCalculator = new SubtotalTotalsCalculator();

        return $subtotalTotalsCalculator;
    }

    /**
     * @return ItemTotalPriceCalculator
     */
    public function getItemTotalCalculator()
    {
        $itemTotalPriceCalculator = new ItemTotalPriceCalculator();

        return $itemTotalPriceCalculator;
    }

    /**
     * @return SubtotalWithoutItemExpensesTotalsCalculator
     */
    public function getSubtotalWithoutItemExpensesTotalsCalculator()
    {
        $subtotalWithoutItemExpensesTotalsCalculator = new SubtotalWithoutItemExpensesTotalsCalculator();

        return $subtotalWithoutItemExpensesTotalsCalculator;
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
        $priceCalculationHelper = new PriceCalculationHelper();

        return $priceCalculationHelper;
    }

}
