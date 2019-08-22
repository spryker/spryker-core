<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Calculation\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Calculation\CalculationPriceMode;
use Spryker\Zed\Calculation\Business\CalculationBusinessFactory;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Calculation\CalculationDependencyProvider;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\DiscountAmountAggregatorForGenericAmountPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\DiscountTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ExpenseTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\GrandTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\InitialGrandTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemDiscountAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemProductOptionPriceAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemSubtotalAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemTaxAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\PriceCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\PriceToPayAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\RefundableAmountCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\RefundTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\SubtotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\TaxTotalCalculatorPlugin;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Facade
 * @group CalculationFacadeTest
 * Add your own group annotations below this line
 */
class CalculationFacadeTest extends Unit
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
        $quoteTransfer->setPriceMode(CalculationPriceMode::PRICE_MODE_GROSS);

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

        $calculationFacade->recalculateQuote($quoteTransfer);

        //item
        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $this->assertNotEmpty($calculatedItemTransfer->getSumGrossPrice());
        $this->assertSame($calculatedItemTransfer->getUnitPrice(), $calculatedItemTransfer->getUnitGrossPrice());
        $this->assertNotEmpty($calculatedItemTransfer->getSumPrice(), 'Item sum price is not set.');
        $this->assertSame($calculatedItemTransfer->getSumPrice(), $calculatedItemTransfer->getSumGrossPrice());

        //item.option
        $calculatedItemProductOptionTransfer = $calculatedItemTransfer->getProductOptions()[0];
        $this->assertNotEmpty($calculatedItemProductOptionTransfer->getSumGrossPrice());
        $this->assertSame($calculatedItemProductOptionTransfer->getUnitPrice(), $calculatedItemProductOptionTransfer->getUnitPrice());
        $this->assertNotEmpty($calculatedItemProductOptionTransfer->getSumPrice(), "Product option sum price is not set.");
        $this->assertSame($calculatedItemProductOptionTransfer->getSumPrice(), $calculatedItemProductOptionTransfer->getSumGrossPrice());

        //order.expense
        $calculatedExpenseTransfer = $quoteTransfer->getExpenses()[0];
        $this->assertNotEmpty($calculatedExpenseTransfer->getSumGrossPrice());
        $this->assertSame($calculatedExpenseTransfer->getUnitPrice(), $calculatedExpenseTransfer->getUnitGrossPrice());
        $this->assertNotEmpty($calculatedExpenseTransfer->getSumPrice(), 'Item sum price is not set.');
        $this->assertSame($calculatedExpenseTransfer->getSumPrice(), $calculatedExpenseTransfer->getSumGrossPrice());
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

        $calculationFacade->recalculateQuote($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];

        $this->assertSame(40, $calculatedItemTransfer->getSumProductOptionPriceAggregation());
    }

    /**
     * @return void
     */
    public function testCalculateSumDiscountAmountShouldSumAllItemDiscounts()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new DiscountAmountAggregatorForGenericAmountPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitPrice(200);
        $itemTransfer->setSumPrice(200);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setIdDiscount(1);
        $calculatedDiscountTransfer->setUnitAmount(20);
        $calculatedDiscountTransfer->setSumAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setIdDiscount(1);
        $calculatedDiscountTransfer->setUnitAmount(20);
        $calculatedDiscountTransfer->setSumAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setIdDiscount(1);
        $calculatedDiscountTransfer->setUnitAmount(20);
        $calculatedDiscountTransfer->setSumAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setIdDiscount(1);
        $calculatedDiscountTransfer->setUnitAmount(20);
        $calculatedDiscountTransfer->setSumAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setUnitPrice(200);
        $expenseTransfer->setSumPrice(200);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setIdDiscount(1);
        $calculatedDiscountTransfer->setUnitAmount(20);
        $calculatedDiscountTransfer->setSumAmount(20);
        $calculatedDiscountTransfer->setQuantity(1);
        $expenseTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $quoteTransfer->addExpense($expenseTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $calculatedExpenseTransfer = $quoteTransfer->getExpenses()[0];

        $this->assertSame(80, $calculatedItemTransfer->getSumDiscountAmountAggregation());
        $this->assertSame(20, $calculatedExpenseTransfer->getSumDiscountAmountAggregation());
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
        $itemTransfer->setSumDiscountAmountAggregation(20);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setSumDiscountAmountAggregation(20);

        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];

        $this->assertSame(40, $calculatedItemTransfer->getSumDiscountAmountFullAggregation());
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

        $calculationFacade->recalculateQuote($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $this->assertSame(20, $calculatedItemTransfer->getSumTaxAmountFullAggregation());
    }

    /**
     * @return void
     */
    public function testCalculateSumAggregationShouldSumItemAndAllAdditionPrices()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new ItemSubtotalAggregatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitPrice(5);
        $itemTransfer->setSumPrice(10);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setUnitPrice(5);
        $productOptionTransfer->setSumPrice(10);
        $itemTransfer->addProductOption($productOptionTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setUnitPrice(5);
        $productOptionTransfer->setSumPrice(20);
        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $this->assertSame(40, $calculatedItemTransfer->getSumSubtotalAggregation());
        $this->assertSame(15, $calculatedItemTransfer->getUnitSubtotalAggregation());
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
        $itemTransfer->setUnitSubtotalAggregation(20);
        $itemTransfer->setSumSubtotalAggregation(40);
        $itemTransfer->setSumDiscountAmountFullAggregation(5);

        $quoteTransfer->addItem($itemTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $this->assertSame(35, $calculatedItemTransfer->getSumPriceToPayAggregation());
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
        $itemTransfer->setSumSubtotalAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumSubtotalAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $totalsTransfer = new TotalsTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

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

        $calculationFacade->recalculateQuote($quoteTransfer);

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
        $itemTransfer->setSumDiscountAmountFullAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumDiscountAmountFullAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setSumDiscountAmountAggregation(10);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = new TotalsTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

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
        $itemTransfer->setSumTaxAmountFullAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumTaxAmountFullAggregation(10);
        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setSumTaxAmount(10);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = new TotalsTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

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
        $itemTransfer->setQuantity(1);
        $itemTransfer->setRefundableAmount(10);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setQuantity(1);
        $productOptionTransfer->setRefundableAmount(10);

        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setQuantity(1);
        $expenseTransfer->setRefundableAmount(10);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = new TotalsTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

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
        $itemTransfer->setSumPriceToPayAggregation(10);
        $itemTransfer->setCanceledAmount(5);

        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setSumPriceToPayAggregation(10);
        $expenseTransfer->setCanceledAmount(2);
        $quoteTransfer->addExpense($expenseTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];
        $calculatedExpenseTransfer = $quoteTransfer->getExpenses()[0];

        $this->assertSame(5, $calculatedItemTransfer->getRefundableAmount());
        $this->assertSame(8, $calculatedExpenseTransfer->getRefundableAmount());
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

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumPriceToPayAggregation(100);
        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setSumPriceToPayAggregation(150);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = new TotalsTransfer();

        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

        $calculatedGrandTotal = $quoteTransfer->getTotals()->getGrandTotal();

        $this->assertSame(250, $calculatedGrandTotal);
    }

    /**
     * @return void
     */
    public function testCalculateInitialGrandTotal()
    {
        $calculationFacade = $this->createCalculationFacade(
            [
                new InitialGrandTotalCalculatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumSubtotalAggregation(200);
        $quoteTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setSumPrice(350);
        $quoteTransfer->addExpense($expenseTransfer);

        $totalsTransfer = new TotalsTransfer();

        $quoteTransfer->setTotals($totalsTransfer);

        $calculationFacade->recalculateQuote($quoteTransfer);

        $calculatedGrandTotal = $quoteTransfer->getTotals()->getGrandTotal();

        $this->assertSame(550, $calculatedGrandTotal);
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
        $container[CalculationDependencyProvider::QUOTE_CALCULATOR_PLUGIN_STACK] = function (Container $container) use ($calculatorPlugins) {
            return $calculatorPlugins;
        };

        $container[CalculationDependencyProvider::PLUGINS_QUOTE_POST_RECALCULATE] = function (Container $container) {
            return [];
        };

        $calculationBusinessFactory->setContainer($container);
        $calculationFacade->setFactory($calculationBusinessFactory);

        return $calculationFacade;
    }

    /**
     * @return void
     */
    public function testRemoveCanceledAmountResetsCancelledAmount()
    {
        // Assign
        $calculationFacade = new CalculationFacade();
        $items = (new ItemTransfer())->setCanceledAmount(100);
        $calculableObjectTransfer = new CalculableObjectTransfer();
        $calculableObjectTransfer->setItems(new ArrayObject([$items]));
        $expectedCancelledAmount = 0;

        // Act
        $calculationFacade->removeCanceledAmount($calculableObjectTransfer);
        $actualCancelledAmount = $calculableObjectTransfer->getItems()[0]->getCanceledAmount();

        // Assert
        $this->assertSame($expectedCancelledAmount, $actualCancelledAmount);
    }
}
