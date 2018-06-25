<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business;

use Spryker\Zed\Calculation\Business\Model\Aggregator\DiscountAmountAggregator;
use Spryker\Zed\Calculation\Business\Model\Aggregator\DiscountAmountAggregator\DiscountAmountAggregatorForGrossAmount;
use Spryker\Zed\Calculation\Business\Model\Aggregator\ItemDiscountAmountFullAggregator;
use Spryker\Zed\Calculation\Business\Model\Aggregator\ItemProductOptionPriceAggregator;
use Spryker\Zed\Calculation\Business\Model\Aggregator\ItemSubtotalAggregator;
use Spryker\Zed\Calculation\Business\Model\Aggregator\ItemTaxAmountFullAggregator;
use Spryker\Zed\Calculation\Business\Model\Aggregator\PriceToPayAggregator;
use Spryker\Zed\Calculation\Business\Model\Calculator\CanceledTotalCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\DiscountTotalCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\GrossPrice\PriceGrossCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\GrossPrice\SumGrossPriceCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\InitialGrandTotalCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\NetPrice\PriceNetCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\NetPrice\SumNetPriceCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\NetTotalCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\OrderTaxTotalCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\PriceCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RefundableAmountCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RefundTotalCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\TaxTotalCalculator;
use Spryker\Zed\Calculation\Business\Model\CheckoutGrandTotalPreCondition;
use Spryker\Zed\Calculation\Business\Model\Executor\OrderCalculatorExecutor;
use Spryker\Zed\Calculation\Business\Model\Executor\QuoteCalculatorExecutor;
use Spryker\Zed\Calculation\CalculationDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Calculation\CalculationConfig getConfig()
 */
class CalculationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Executor\QuoteCalculatorExecutorInterface
     */
    public function createQuoteCalculatorExecutor()
    {
        return new QuoteCalculatorExecutor($this->getProvidedQuoteCalculatorPluginStack());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Executor\OrderCalculatorExecutorInterface
     */
    public function createOrderCalculatorExecutor()
    {
        return new OrderCalculatorExecutor($this->getProvidedOrderCalculatorPluginStack());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\PriceCalculator
     */
    public function createPriceCalculator()
    {
        return new PriceCalculator(
            $this->createNetPriceCalculators(),
            $this->createGrossPriceCalculators()
        );
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface[]
     */
    protected function createNetPriceCalculators()
    {
        return [
            $this->createSumNetPriceCalculator(),
            $this->createPriceNetCalculator(),
        ];
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface[]
     */
    protected function createGrossPriceCalculators()
    {
        return [
            $this->createSumGrossPriceCalculator(),
            $this->createPriceGrossCalculator(),
        ];
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\NetPrice\PriceNetCalculator
     */
    protected function createPriceNetCalculator()
    {
        return new PriceNetCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\NetPrice\SumNetPriceCalculator
     */
    protected function createSumNetPriceCalculator()
    {
        return new SumNetPriceCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\GrossPrice\PriceGrossCalculator
     */
    public function createPriceGrossCalculator()
    {
        return new PriceGrossCalculator();
    }

    /**
     * @deprecated Use createPriceGrossCalculator() instead.
     *
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\GrossPrice\PriceGrossCalculator
     */
    protected function createPriceCrossCalculator()
    {
        return $this->createPriceGrossCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\GrossPrice\SumGrossPriceCalculator
     */
    protected function createSumGrossPriceCalculator()
    {
        return new SumGrossPriceCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Aggregator\ItemProductOptionPriceAggregator
     */
    public function createProductOptionPriceAggregator()
    {
        return new ItemProductOptionPriceAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Aggregator\DiscountAmountAggregator\DiscountAmountAggregatorForGrossAmount
     */
    public function createDiscountAmountAggregator()
    {
        return new DiscountAmountAggregatorForGrossAmount();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Aggregator\DiscountAmountAggregator
     */
    public function createDiscountAmountAggregatorForGenericAmount()
    {
        return new DiscountAmountAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Aggregator\ItemDiscountAmountFullAggregator
     */
    public function createItemDiscountAmountFullAggregator()
    {
        return new ItemDiscountAmountFullAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Aggregator\ItemTaxAmountFullAggregator
     */
    public function createItemTaxAmountFullAggregator()
    {
        return new ItemTaxAmountFullAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Aggregator\ItemSubtotalAggregator
     */
    public function createSumAggregator()
    {
        return new ItemSubtotalAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Aggregator\PriceToPayAggregator
     */
    public function createPriceToPayAggregator()
    {
        return new PriceToPayAggregator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalCalculator
     */
    public function createSubtotalCalculator()
    {
         return new SubtotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalCalculator
     */
    public function createExpenseTotalCalculator()
    {
        return new ExpenseTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\DiscountTotalCalculator
     */
    public function createDiscountTotalCalculator()
    {
        return new DiscountTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\TaxTotalCalculator
     */
    public function createTaxTotalCalculator()
    {
        return new TaxTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\RefundTotalCalculator
     */
    public function createRefundTotalCalculator()
    {
        return new RefundTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\RefundableAmountCalculator
     */
    public function createRefundableAmountCalculator()
    {
        return new RefundableAmountCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalCalculator
     */
    public function createGrandTotalCalculator()
    {
        return new GrandTotalCalculator($this->getUtilTextService());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\InitialGrandTotalCalculator
     */
    public function createInitialGrandTotalCalculator()
    {
        return new InitialGrandTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\CanceledTotalCalculator
     */
    public function createCanceledTotalCalculator()
    {
        return new CanceledTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface|\Spryker\Zed\Calculation\Business\Model\Calculator\OrderTaxTotalCalculator
     */
    public function createOrderTaxTotalCalculator()
    {
        return new OrderTaxTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\CheckoutGrandTotalPreConditionInterface
     */
    public function createCheckoutGrandTotalPreCondition()
    {
        return new CheckoutGrandTotalPreCondition($this->createQuoteCalculatorExecutor());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator
     */
    public function createRemoveTotalsCalculator()
    {
        return new RemoveTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator|\Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    public function createRemoveAllCalculatedDiscountsCalculator()
    {
        return new RemoveAllCalculatedDiscountsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\NetTotalCalculator|\Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    public function createNetTotalCalculator()
    {
        return new NetTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getProvidedQuoteCalculatorPluginStack()
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::QUOTE_CALCULATOR_PLUGIN_STACK);
    }

    /**
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]|\Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface[]
     */
    protected function getProvidedOrderCalculatorPluginStack()
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::ORDER_CALCULATOR_PLUGIN_STACK);
    }

    /**
     * @return \Spryker\Zed\Calculation\Dependency\Service\CalculationToUtilTextInterface
     */
    public function getUtilTextService()
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
