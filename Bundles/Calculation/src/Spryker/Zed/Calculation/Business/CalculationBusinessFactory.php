<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business;

use Spryker\Zed\Calculation\Business\Model\PriceCalculationHelper;
use Spryker\Zed\Calculation\Business\Model\Calculator\ItemTotalPriceCalculator;
use Spryker\Zed\Calculation\Business\Model\StackExecutor;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpensePriceToPayCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ItemPriceToPayCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionPriceToPayCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveAllExpensesCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalWithoutItemExpensesTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\TaxTotalsCalculator;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Calculation\CalculationConfig;
use Spryker\Zed\Calculation\Communication\Plugin\ItemTotalPriceCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\TaxTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ProductOptionPriceToPayCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ItemPriceToPayCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\GrandTotalTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\SubtotalWithoutItemExpensesTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\SubtotalTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\ExpenseTotalsCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\RemoveAllExpensesCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\RemoveTotalsCalculatorPlugin;

/**
 * @method CalculationConfig getConfig()
 */
class CalculationBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\StackExecutor
     */
    public function createStackExecutor()
    {
        $stackExecutor = new StackExecutor();

        return $stackExecutor;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ExpensePriceToPayCalculator
     */
    public function createExpensePriceToPayCalculator()
    {
        $expensePriceToPayCalculator = new ExpensePriceToPayCalculator();

        return $expensePriceToPayCalculator;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator
     */
    public function createGrandTotalsCalculator()
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
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ItemPriceToPayCalculator
     */
    public function createItemPriceToPayCalculator()
    {
        $itemPriceToPayCalculator = new ItemPriceToPayCalculator();

        return $itemPriceToPayCalculator;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionPriceToPayCalculator
     */
    public function createOptionPriceToPayCalculator()
    {
        $productOptionPriceToPayCalculator = new ProductOptionPriceToPayCalculator();

        return $productOptionPriceToPayCalculator;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\RemoveAllExpensesCalculator
     */
    public function createRemoveAllExpensesCalculator()
    {
        $removeAllExpensesCalculator = new RemoveAllExpensesCalculator();

        return $removeAllExpensesCalculator;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator
     */
    public function createRemoveTotalsCalculator()
    {
        $removeTotalsCalculator = new RemoveTotalsCalculator();

        return $removeTotalsCalculator;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator
     */
    public function createSubtotalTotalsCalculator()
    {
        $subtotalTotalsCalculator = new SubtotalTotalsCalculator();

        return $subtotalTotalsCalculator;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ItemTotalPriceCalculator
     */
    public function createItemTotalCalculator()
    {
        $itemTotalPriceCalculator = new ItemTotalPriceCalculator();

        return $itemTotalPriceCalculator;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalWithoutItemExpensesTotalsCalculator
     */
    public function createSubtotalWithoutItemExpensesTotalsCalculator()
    {
        $subtotalWithoutItemExpensesTotalsCalculator = new SubtotalWithoutItemExpensesTotalsCalculator();

        return $subtotalWithoutItemExpensesTotalsCalculator;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\TaxTotalsCalculator
     */
    public function createTaxTotalsCalculator()
    {
        return new TaxTotalsCalculator(
            $this->createPriceCalculationHelper()
        );
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator
     */
    protected function createSubTotalsCalculator()
    {
        $subtotalTotalsCalculator = new SubtotalTotalsCalculator();

        return $subtotalTotalsCalculator;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator
     */
    public function createExpenseTotalsCalculator()
    {
        $expenseTotalsCalculator = new ExpenseTotalsCalculator();

        return $expenseTotalsCalculator;
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\PriceCalculationHelper
     */
    protected function createPriceCalculationHelper()
    {
        $priceCalculationHelper = new PriceCalculationHelper();

        return $priceCalculationHelper;
    }

    /**
     * @return CalculatorPluginInterface[]|TotalsCalculatorPluginInterface[]
     */
    public function getCalculatorStack()
    {
        return [
            $this->createRemoveTotalsCalculatorPlugin(),
            $this->createRemoveAllExpensesCalculatorPlugin(),
            $this->createExpenseTotalsCalculatorPlugin(),
            $this->createSubtotalTotalsCalculatorPlugin(),
            $this->createSubtotalWithoutItemExpensesTotalsCalculatorPlugin(),
            $this->createGrandTotalTotalsCalculatorPlugin(),
            $this->createExpensePriceToPayCalculatorPlugin(),
            $this->createItemPriceToPayCalculatorPlugin(),
            $this->createProductOptionPriceToPayCalculatorPlugin(),
            $this->createTaxTotalsCalculatorPlugin(),
            $this->createItemTotalPriceCalculatorPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\RemoveTotalsCalculatorPlugin
     */
    protected function createRemoveTotalsCalculatorPlugin()
    {
        return new RemoveTotalsCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\RemoveAllExpensesCalculatorPlugin
     */
    protected function createRemoveAllExpensesCalculatorPlugin()
    {
        return new RemoveAllExpensesCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\ExpenseTotalsCalculatorPlugin
     */
    protected function createExpenseTotalsCalculatorPlugin()
    {
        return new ExpenseTotalsCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\SubtotalTotalsCalculatorPlugin
     */
    protected function createSubtotalTotalsCalculatorPlugin()
    {
        return new SubtotalTotalsCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\SubtotalWithoutItemExpensesTotalsCalculatorPlugin
     */
    protected function createSubtotalWithoutItemExpensesTotalsCalculatorPlugin()
    {
        return new SubtotalWithoutItemExpensesTotalsCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\GrandTotalTotalsCalculatorPlugin
     */
    protected function createGrandTotalTotalsCalculatorPlugin()
    {
        return new GrandTotalTotalsCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin
     */
    protected function createExpensePriceToPayCalculatorPlugin()
    {
        return new ExpensePriceToPayCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\ItemPriceToPayCalculatorPlugin
     */
    protected function createItemPriceToPayCalculatorPlugin()
    {
        return new ItemPriceToPayCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\ProductOptionPriceToPayCalculatorPlugin
     */
    protected function createProductOptionPriceToPayCalculatorPlugin()
    {
        return new ProductOptionPriceToPayCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\TaxTotalsCalculatorPlugin
     */
    protected function createTaxTotalsCalculatorPlugin()
    {
        return new TaxTotalsCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\Calculation\Communication\Plugin\ItemTotalPriceCalculatorPlugin
     */
    protected function createItemTotalPriceCalculatorPlugin()
    {
        return new ItemTotalPriceCalculatorPlugin();
    }

}
