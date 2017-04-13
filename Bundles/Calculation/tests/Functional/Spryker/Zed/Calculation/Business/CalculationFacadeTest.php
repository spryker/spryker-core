<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Calculation\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Aggregator\ItemPriceToPayAggregator;
use Spryker\Zed\Calculation\Business\CalculationBusinessFactory;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Calculation\CalculationDependencyProvider;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemDiscountAmountAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemDiscountAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemPriceToPayAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemSumAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemTaxAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemPriceCalculatorPlugin;
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
                new ItemPriceCalculatorPlugin(),
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
                new ItemDiscountAmountAggregatorPlugin(),
            ]
        );

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setSumGrossAmount(20);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setSumGrossAmount(20);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setSumGrossAmount(20);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setSumGrossAmount(20);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $calculationFacade->recalculate($quoteTransfer);

        $calculatedItemTransfer = $quoteTransfer->getItems()[0];

        $this->assertSame(80, $calculatedItemTransfer->getDiscountAmountAggregation());

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
        $calculatedDiscountTransfer->setSumGrossAmount(20);
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setSumGrossAmount(20);
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
                new ItemPriceToPayAggregatorPlugin(),
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

