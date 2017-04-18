<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Calculation\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Aggregator\ItemPriceToPayAggregator;
use Spryker\Zed\Calculation\Business\CalculationBusinessFactory;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Calculation\CalculationDependencyProvider;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\DiscountTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ExpenseTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\DiscountAmountAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\GrandTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemDiscountAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\PriceToPayAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemSumAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemTaxAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\PriceCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\RefundableAmountCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\RefundTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\SubtotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\TaxTotalCalculatorPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemProductOptionPriceAggregatorPlugin;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Calculation
 * @group Business
 * @group CalculationFacadeTest
 */
class CalculationFacadeTest extends Test
{

    /**
     * @return void
     */
    public function testCalculatePriceShouldSetDefaultStorePriceValues()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new PriceCalculatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(2);
        $itemTransfer->setUnitGrossPrice(100);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setQuantity(2);
        $productOptionTransfer->setUnitGrossPrice(10);

        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setUnitGrossPrice(100);
        $expenseTransfer->setQuantity(1);

        $quoteTransfer->addExpense($expenseTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        //item
        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $this->assertNotEmpty($itemTransfer->getSumGrossPrice());
        $this->assertSame($calculatedItemTransfer->getUnitPrice(), $itemTransfer->getUnitGrossPrice());
        $this->assertNotEmpty($calculatedItemTransfer->getSumPrice(), 'Item sum price is not set.');
        $this->assertSame($calculatedItemTransfer->getSumPrice(), $itemTransfer->getSumGrossPrice());

        //item.option
        $calculatedItemProductOptionTransfer = $calculatedItemTransfer->getProductOptions()[0];
        $this->assertNotEmpty($calculatedItemProductOptionTransfer->getSumGrossPrice());
        $this->assertSame($calculatedItemProductOptionTransfer->getUnitPrice(), $productOptionTransfer->getUnitPrice());
        $this->assertNotEmpty($calculatedItemProductOptionTransfer->getSumPrice(), "Product option sum price is not set.");
        $this->assertSame($calculatedItemProductOptionTransfer->getSumPrice(), $productOptionTransfer->getSumGrossPrice());

        //order.expense
        $calculatedExpenseTransfer = $quoteTransfer->getExpenses()[0];
        $this->assertNotEmpty($calculatedExpenseTransfer->getSumGrossPrice());
        $this->assertSame($calculatedExpenseTransfer->getUnitPrice(), $expenseTransfer->getUnitGrossPrice());
        $this->assertNotEmpty($calculatedExpenseTransfer->getSumPrice(), 'Item sum price is not set.');
        $this->assertSame($calculatedExpenseTransfer->getSumPrice(), $expenseTransfer->getSumGrossPrice());

    }

    /**
     * @return void
     */
    public function testCalculateProductOptionPriceAggregationShouldSumAllOptionPrices()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new ItemProductOptionPriceAggregatorPlugin(),
            ]
        );

        $itemTransfer = new ItemTransfer();
        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setSumPrice(20);
        $itemTransfer->addProductOption($productOptionTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setSumPrice(20);
        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];

        $this->assertSame(40, $calculatedItemTransfer->getProductOptionPriceAggregation());

    }

    /**
     * @return void
     */
    public function testCalculateSumDiscountAmountShouldSumAllItemDiscounts()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new DiscountAmountAggregatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setUnitGrossAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setUnitGrossAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setUnitGrossAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setUnitGrossAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setUnitGrossAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $expenseTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $quoteTransfer->addExpense($expenseTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $calculatedExpenseTransfer = $quoteTransfer->getExpenses()[0];

        $this->assertSame(80, $calculatedItemTransfer->getDiscountAmountAggregation());
        $this->assertSame(20, $calculatedExpenseTransfer->getDiscountAmountAggregation());

    }

    /**
     * @return void
     */
    public function testCalculateFullDiscountAmountShouldSumAllItemsAndAdditions()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new ItemDiscountAmountFullAggregatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setUnitGrossAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setUnitGrossAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $productOptionTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];

        $this->assertSame(40, $calculatedItemTransfer->getDiscountAmountFullAggregation());
    }

    /**
     * @return void
     */
    public function testCalculateTaxAmountFullAggregationShouldSumAllTaxesWithAdditions()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new ItemTaxAmountFullAggregatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumTaxAmount(10);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setSumTaxAmount(10);

        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $this->assertSame(20, $calculatedItemTransfer->getTaxAmountFullAggregation());
    }

    /**
     * @return void
     */
    public function testCalculateSumAggregationShouldSumItemAndAllAdditionPrices()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new ItemSumAggregatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumPrice(10);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setSumPrice(10);
        $itemTransfer->addProductOption($productOptionTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setSumPrice(20);
        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $this->assertSame(40, $calculatedItemTransfer->getSumAggregation());
    }


    /**
     * @return void
     */
    public function testCalculatePriceToPayAggregation()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new PriceToPayAggregatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumAggregation(40);
        $itemTransfer->setDiscountAmountFullAggregation(5);

        $quoteTransfer->addItem($itemTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $this->assertSame(35, $calculatedItemTransfer->getPriceToPayAggregation());
    }

    /**
     * @return void
     */
    public function testCalculateSubtotalShouldSumAllItemsWithAdditions()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new SubtotalCalculatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $totalsTransfer = new TotalsTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedTotalsTransfer = $quoteTransfer->getTotals();
        $this->assertSame(20, $calculatedTotalsTransfer->getSubtotal());
    }

    /**
     * @return void
     */
    public function testCalculateExpenseTotalShouldSumAllOrderExpenses()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new ExpenseTotalCalculatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setSumPrice(10);
        $quoteTransfer->addExpense($expenseTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setSumPrice(10);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = new TotalsTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedOrderExpenseTotal = $quoteTransfer->getTotals()->getExpenseTotal();
        $this->assertSame(20, $calculatedOrderExpenseTotal);

    }

    /**
     * @return void
     */
    public function testCalculateDiscountTotalShouldSumAllDiscounts()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new DiscountTotalCalculatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setDiscountAmountFullAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setDiscountAmountFullAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setDiscountAmountAggregation(10);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = new TotalsTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedTotalDiscountAmount = $quoteTransfer->getTotals()->getDiscountTotal();
        $this->assertSame(30, $calculatedTotalDiscountAmount);

    }


    /**
     * @return void
     */
    public function testCalculateTaxTotalShouldSumAllTaxAmounts()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new TaxTotalCalculatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setTaxAmountFullAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setTaxAmountFullAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setSumTaxAmount(10);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = new TotalsTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedTaxAmount = $quoteTransfer->getTotals()->getTaxTotal()->getAmount();

        $this->assertSame(30, $calculatedTaxAmount);
    }

    /**
     * @return void
     */
    public function testCalculateRefundTotalShouldSumAllRefundableAmounts()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new RefundTotalCalculatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setRefundableAmount(10);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setRefundableAmount(10);

        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setRefundableAmount(10);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = new TotalsTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedRefundTotal = $quoteTransfer->getTotals()->getRefundTotal();

        $this->assertSame(30, $calculatedRefundTotal);
    }

    /**
     * @return void
     */
    public function testCalculateRefundableAmount()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new RefundableAmountCalculatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumAggregation(10);
        $itemTransfer->setCanceledAmount(5);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setCanceledAmount(2);

        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setSumPrice(10);
        $expenseTransfer->setCanceledAmount(2);
        $quoteTransfer->addExpense($expenseTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];

        //@todo add expenses
        $this->assertSame(3, $calculatedItemTransfer->getRefundableAmount());
    }

    /**
     * @return void
     */
    public function testCalculateGrandTotal()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new GrandTotalCalculatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $totalsTransfer =new TotalsTransfer();

        $totalsTransfer->setSubtotal(200);
        $totalsTransfer->setExpenseTotal(100);
        $totalsTransfer->setDiscountTotal(50);

        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedGrandTotal = $quoteTransfer->getTotals()->getGrandTotal();

        $this->assertSame(250, $calculatedGrandTotal);
    }


    /**
     * @param array $calculatorPlugins
     *
     * @return \Spryker\Zed\Calculation\Business\CalculationFacade
     */
    protected function createCalculationFacade(array $calculatorPlugins)
    {
        $calculationFacade = new CalculationFacade();

        $calculationBusinessFactory = new CalculationBusinessFactory();

        $container = new Container();
        $container[CalculationDependencyProvider::CALCULATOR_PLUGIN_STACK] = function(Container $container) use ($calculatorPlugins) {
            return $calculatorPlugins;
        };

        $calculationBusinessFactory->setContainer($container);
        $calculationFacade->setFactory($calculationBusinessFactory);

        return $calculationFacade;
    }
}

