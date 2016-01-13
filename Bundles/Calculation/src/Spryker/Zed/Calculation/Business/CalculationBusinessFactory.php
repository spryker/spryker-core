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
     * @return StackExecutor
     */
    public function createStackExecutor()
    {
        $stackExecutor = new StackExecutor();

        return $stackExecutor;
    }

    /**
     * @return ExpensePriceToPayCalculator
     */
    public function createExpensePriceToPayCalculator()
    {
        $expensePriceToPayCalculator = new ExpensePriceToPayCalculator();

        return $expensePriceToPayCalculator;
    }

    /**
     * @return GrandTotalTotalsCalculator
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
     * @return ItemPriceToPayCalculator
     */
    public function createItemPriceToPayCalculator()
    {
        $itemPriceToPayCalculator = new ItemPriceToPayCalculator();

        return $itemPriceToPayCalculator;
    }

    /**
     * @return ProductOptionPriceToPayCalculator
     */
    public function createOptionPriceToPayCalculator()
    {
        $productOptionPriceToPayCalculator = new ProductOptionPriceToPayCalculator();

        return $productOptionPriceToPayCalculator;
    }

    /**
     * @return RemoveAllExpensesCalculator
     */
    public function createRemoveAllExpensesCalculator()
    {
        $removeAllExpensesCalculator = new RemoveAllExpensesCalculator();

        return $removeAllExpensesCalculator;
    }

    /**
     * @return RemoveTotalsCalculator
     */
    public function createRemoveTotalsCalculator()
    {
        $removeTotalsCalculator = new RemoveTotalsCalculator();

        return $removeTotalsCalculator;
    }

    /**
     * @return SubtotalTotalsCalculator
     */
    public function createSubtotalTotalsCalculator()
    {
        $subtotalTotalsCalculator = new SubtotalTotalsCalculator();

        return $subtotalTotalsCalculator;
    }

    /**
     * @return ItemTotalPriceCalculator
     */
    public function createItemTotalCalculator()
    {
        $itemTotalPriceCalculator = new ItemTotalPriceCalculator();

        return $itemTotalPriceCalculator;
    }

    /**
     * @return SubtotalWithoutItemExpensesTotalsCalculator
     */
    public function createSubtotalWithoutItemExpensesTotalsCalculator()
    {
        $subtotalWithoutItemExpensesTotalsCalculator = new SubtotalWithoutItemExpensesTotalsCalculator();

        return $subtotalWithoutItemExpensesTotalsCalculator;
    }

    /**
     * @return TaxTotalsCalculator
     */
    public function createTaxTotalsCalculator()
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
    public function createExpenseTotalsCalculator()
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
     * @return CalculatorPluginInterface[]|TotalsCalculatorPluginInterface[]
     * @deprecated?
     */
    public function getSoftCalculatorStack()
    {
        return [
        ];
    }

    /**
     * @return RemoveTotalsCalculatorPlugin
     */
    protected function createRemoveTotalsCalculatorPlugin()
    {
        return new RemoveTotalsCalculatorPlugin();
    }

    /**
     * @return RemoveAllExpensesCalculatorPlugin
     */
    protected function createRemoveAllExpensesCalculatorPlugin()
    {
        return new RemoveAllExpensesCalculatorPlugin();
    }

    /**
     * @return ExpenseTotalsCalculatorPlugin
     */
    protected function createExpenseTotalsCalculatorPlugin()
    {
        return new ExpenseTotalsCalculatorPlugin();
    }

    /**
     * @return SubtotalTotalsCalculatorPlugin
     */
    protected function createSubtotalTotalsCalculatorPlugin()
    {
        return new SubtotalTotalsCalculatorPlugin();
    }

    /**
     * @return SubtotalWithoutItemExpensesTotalsCalculatorPlugin
     */
    protected function createSubtotalWithoutItemExpensesTotalsCalculatorPlugin()
    {
        return new SubtotalWithoutItemExpensesTotalsCalculatorPlugin();
    }

    /**
     * @return GrandTotalTotalsCalculatorPlugin
     */
    protected function createGrandTotalTotalsCalculatorPlugin()
    {
        return new GrandTotalTotalsCalculatorPlugin();
    }

    /**
     * @return ExpensePriceToPayCalculatorPlugin
     */
    protected function createExpensePriceToPayCalculatorPlugin()
    {
        return new ExpensePriceToPayCalculatorPlugin();
    }

    /**
     * @return ItemPriceToPayCalculatorPlugin
     */
    protected function createItemPriceToPayCalculatorPlugin()
    {
        return new ItemPriceToPayCalculatorPlugin();
    }

    /**
     * @return ProductOptionPriceToPayCalculatorPlugin
     */
    protected function createProductOptionPriceToPayCalculatorPlugin()
    {
        return new ProductOptionPriceToPayCalculatorPlugin();
    }

    /**
     * @return TaxTotalsCalculatorPlugin
     */
    protected function createTaxTotalsCalculatorPlugin()
    {
        return new TaxTotalsCalculatorPlugin();
    }

    /**
     * @return ItemTotalPriceCalculatorPlugin
     */
    protected function createItemTotalPriceCalculatorPlugin()
    {
        return new ItemTotalPriceCalculatorPlugin();
    }

}
