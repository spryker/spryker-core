<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business;

use Spryker\Zed\Calculation\Business\Aggregator\DiscountAmountAggregator;
use Spryker\Zed\Calculation\Business\Aggregator\PriceToPayAggregator;
use Spryker\Zed\Calculation\Business\Aggregator\ItemSubtotalAggregator;
use Spryker\Zed\Calculation\Business\Aggregator\ItemTaxAmountFullAggregator;
use Spryker\Zed\Calculation\Business\Aggregator\ItemProductOptionPriceAggregator;
use Spryker\Zed\Calculation\Business\Aggregator\TaxRateAverageAggregator;
use Spryker\Zed\Calculation\Business\Calculator\CanceledTotalCalculator;
use Spryker\Zed\Calculation\Business\Calculator\DiscountTotalCalculator;
use Spryker\Zed\Calculation\Business\Calculator\ExpenseTotalCalculator;
use Spryker\Zed\Calculation\Business\Calculator\GrandTotalCalculator;
use Spryker\Zed\Calculation\Business\Calculator\GrossPrice\PriceGrossCalculator;
use Spryker\Zed\Calculation\Business\Calculator\GrossPrice\SumGrossPriceCalculator;
use Spryker\Zed\Calculation\Business\Calculator\NetPrice\PriceNetCalculator;
use Spryker\Zed\Calculation\Business\Calculator\NetPrice\SumNetPriceCalculator;
use Spryker\Zed\Calculation\Business\Calculator\OrderTaxTotalCalculator;
use Spryker\Zed\Calculation\Business\Calculator\PriceCalculator;
use Spryker\Zed\Calculation\Business\Calculator\RefundableAmountCalculator;
use Spryker\Zed\Calculation\Business\Calculator\RefundTotalCalculator;
use Spryker\Zed\Calculation\Business\Calculator\SubtotalCalculator;
use Spryker\Zed\Calculation\Business\Calculator\TaxAmountAfterCancellationCalculator;
use Spryker\Zed\Calculation\Business\Calculator\TaxAmountCalculator;
use Spryker\Zed\Calculation\Business\Calculator\TaxTotalCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseGrossSumAmountCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossAmountsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionGrossSumCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\CheckoutGrandTotalPreCondition;
use Spryker\Zed\Calculation\Business\Model\OrderCalculatorExecutor;
use Spryker\Zed\Calculation\Business\Model\QuoteCalculatorExecutor;
use Spryker\Zed\Calculation\Business\Model\StackExecutor;
use Spryker\Zed\Calculation\CalculationDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Calculation\Business\Aggregator\ItemDiscountAmountFullAggregator;

/**
 * @method \Spryker\Zed\Calculation\CalculationConfig getConfig()
 */
class CalculationBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\StackExecutorInterface|\Spryker\Zed\Calculation\Business\Model\QuoteCalculatorExecutorInterface
     */
    public function createStackExecutor()
    {
        if ($this->getConfig()->isNewCalculatorsEnabled()) {
            return $this->createQuoteCalculatorExecutor();
        }
        return new StackExecutor($this->getCalculatorStack());
    }

    // START: new calculators

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\QuoteCalculatorExecutorInterface
     */
    public function createQuoteCalculatorExecutor()
    {
        return new QuoteCalculatorExecutor($this->getProvidedQuoteCalculatorPluginStack());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\OrderCalculatorExecutorInterface
     */
    public function createOrderCalculatorExecutor()
    {
        return new OrderCalculatorExecutor($this->getProvidedOrderCalculatorPluginStack());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|PriceCalculator
     */
    public function createPriceCalculator()
    {
        return new PriceCalculator(
            $this->createNetPriceCalculators(),
            $this->createGrossPriceCalculators(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface[]
     */
    protected function createNetPriceCalculators()
    {
        return [
            $this->createSumNetPriceCalculator(),
            $this->createPriceNetCalculator(),
        ];
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface[]
     */
    protected function createGrossPriceCalculators()
    {
        return [
            $this->createSumGrossPriceCalculator(),
            $this->createPriceCrossCalculator(),
        ];
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|PriceNetCalculator
     */
    protected function createPriceNetCalculator()
    {
        return new PriceNetCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|SumNetPriceCalculator
     */
    protected function createSumNetPriceCalculator()
    {
        return new SumNetPriceCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|PriceGrossCalculator
     */
    protected function createPriceCrossCalculator()
    {
        return new PriceGrossCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|SumGrossPriceCalculator
     */
    protected function createSumGrossPriceCalculator()
    {
        return new SumGrossPriceCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|ItemProductOptionPriceAggregator
     */
    public function createProductOptionPriceAggregator()
    {
        return new ItemProductOptionPriceAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|DiscountAmountAggregator
     */
    public function createDiscountAmountAggregator()
    {
        return new DiscountAmountAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|ItemDiscountAmountFullAggregator
     */
    public function createItemDiscountAmountFullAggregator()
    {
        return new ItemDiscountAmountFullAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|ItemTaxAmountFullAggregator
     */
    public function createItemTaxAmountFullAggregator()
    {
        return new ItemTaxAmountFullAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|ItemSubtotalAggregator
     */
    public function createSumAggregator()
    {
        return new ItemSubtotalAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|PriceToPayAggregator
     */
    public function createPriceToPayAggregator()
    {
        return new PriceToPayAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|SubtotalCalculator
     */
    public function createSubtotalCalculator()
    {
         return new SubtotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|ExpenseTotalCalculator
     */
    public function createExpenseTotalCalculator()
    {
        return new ExpenseTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|DiscountTotalCalculator
     */
    public function createDiscountTotalCalculator()
    {
        return new DiscountTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|TaxTotalCalculator
     */
    public function createTaxTotalCalculator()
    {
        return new TaxTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|RefundTotalCalculator
     */
    public function createRefundTotalCalculator()
    {
        return new RefundTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|RefundableAmountCalculator
     */
    public function createRefundableAmountCalculator()
    {
        return new RefundableAmountCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|GrandTotalCalculator
     */
    public function createGrandTotalCalculator()
    {
        return new GrandTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|TaxAmountCalculator
     */
    public function createTaxAmountCalculator()
    {
        return new TaxAmountCalculator($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Calculator\CanceledTotalCalculator
     */
    public function createCanceledTotalCalculator()
    {
        return new CanceledTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Aggregator\TaxRateAverageAggregator
     */
    public function createTaxRateAverageAggregationCalculator()
    {
        return new TaxRateAverageAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Calculator\TaxAmountAfterCancellationCalculator
     */
    public function createTaxAmountAfterCancellationCalculator()
    {
        return new TaxAmountAfterCancellationCalculator($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Calculator\OrderTaxTotalCalculator
     */
    public function createOrderTaxTotalCalculator()
    {
        return new OrderTaxTotalCalculator();
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
    protected function getProvidedQuoteCalculatorPluginStack()
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::QUOTE_CALCULATOR_PLUGIN_STACK);
    }

    /**
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getProvidedOrderCalculatorPluginStack()
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::ORDER_CALCULATOR_PLUGIN_STACK);
    }

    /**
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getCalculatorStack()
    {
        return $this->getProvidedCalculatorStack();
    }

    /**
     * @return \Spryker\Zed\Calculation\Dependency\Facade\CalculationToTaxInterface
     */
    protected function getTaxFacade()
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::FACADE_TAX);
    }

}
