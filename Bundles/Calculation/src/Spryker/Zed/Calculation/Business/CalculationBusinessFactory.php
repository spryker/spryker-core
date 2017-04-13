<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business;

use Spryker\Zed\Calculation\Business\Aggregator\ItemDiscountAmountAggregator;
use Spryker\Zed\Calculation\Business\Aggregator\ItemPriceToPayAggregator;
use Spryker\Zed\Calculation\Business\Aggregator\ItemSumAggregator;
use Spryker\Zed\Calculation\Business\Aggregator\ItemTaxAmountFullAggregator;
use Spryker\Zed\Calculation\Business\Aggregator\ItemProductOptionPriceAggregator;
use Spryker\Zed\Calculation\Business\Calculator\ItemNetSumPriceCalculator;
use Spryker\Zed\Calculation\Business\Calculator\ItemPriceCalculator;
use Spryker\Zed\Calculation\Business\Calculator\ProductOption\ProductOptionNetSumPriceCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseGrossSumAmountCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossAmountsCalculator;
use Spryker\Zed\Calculation\Business\Calculator\ItemGrossSumPriceCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionGrossSumCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\CheckoutGrandTotalPreCondition;
use Spryker\Zed\Calculation\Business\Model\StackExecutor;
use Spryker\Zed\Calculation\CalculationDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Calculation\Business\Calculator\ProductOption\ProductOptionGrossSumPriceCalculator;
use Spryker\Zed\Calculation\Business\Aggregator\ItemDiscountAmountFullAggregator;

/**
 * @method \Spryker\Zed\Calculation\CalculationConfig getConfig()
 */
class CalculationBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\StackExecutorInterface
     */
    public function createStackExecutor()
    {
        return new StackExecutor($this->getCalculatorStack());
    }

    // START: new calculators

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    public function createPriceCalculator()
    {
        return new ItemPriceCalculator([
            $this->createGrossSumPriceCalculator(),
            $this->createItemNetSumPriceCalculator(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    protected function createGrossSumPriceCalculator()
    {
        return new ItemGrossSumPriceCalculator($this->createProductOptionGrossSumPriceCalculator());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    protected function createItemNetSumPriceCalculator()
    {
        return new ItemNetSumPriceCalculator($this->createProductOptionGrossSumPriceCalculator());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    protected function createProductOptionGrossSumPriceCalculator()
    {
        return new ProductOptionGrossSumPriceCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    protected function createProductOptionNetSumPriceCalculator()
    {
        return new ProductOptionNetSumPriceCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    public function createProductOptionPriceAggregator()
    {
        return new ItemProductOptionPriceAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    public function createItemDiscountAmountAggregator()
    {
        return new ItemDiscountAmountAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    public function createItemDiscountAmountFullAggregator()
    {
        return new ItemDiscountAmountFullAggregator($this->createItemDiscountAmountAggregator());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    public function createItemTaxAmountFullAggregator()
    {
        return new ItemTaxAmountFullAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    public function createItemSumAggregator()
    {
        return new ItemSumAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    public function createItemPriceToPayAggregator()
    {
        return new ItemPriceToPayAggregator();
    }

    // END: new calculators

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseGrossSumAmountCalculator
     */
    public function createExpenseGrossSumAmount()
    {
        return new ExpenseGrossSumAmountCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator
     */
    public function createExpenseTotalsCalculator()
    {
        return new ExpenseTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator
     */
    public function createGrandTotalsCalculator()
    {
        return new GrandTotalTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossAmountsCalculator
     */
    public function createItemGrossSumCalculator()
    {
        return new ItemGrossAmountsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionGrossSumCalculator
     */
    public function createOptionGrossSumCalculator()
    {
        return new ProductOptionGrossSumCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator
     */
    public function createRemoveTotalsCalculator()
    {
        return new RemoveTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator
     */
    public function createSubtotalTotalsCalculator()
    {
        return new SubtotalTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\CheckoutGrandTotalPreConditionInterface
     */
    public function createCheckoutGrandTotalPreCondition()
    {
        return new CheckoutGrandTotalPreCondition($this->createStackExecutor());
    }

    /**
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getProvidedCalculatorStack()
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::CALCULATOR_STACK);
    }

    /**
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getProvidedCalculatorPluginStack()
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::CALCULATOR_PLUGIN_STACK);
    }

    /**
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getCalculatorStack()
    {
        if ($this->getConfig()->enableNewCalculators()) {
            return $this->getProvidedCalculatorPluginStack();
        }

        return $this->getProvidedCalculatorStack();
    }

}
