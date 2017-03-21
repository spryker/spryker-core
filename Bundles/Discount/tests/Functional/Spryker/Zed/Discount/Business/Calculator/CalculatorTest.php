<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Discount\Business\Calculator;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Calculator\Calculator;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\QueryString\ClauseValidator;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Business\QueryString\OperatorProvider;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider;
use Spryker\Zed\Discount\Business\QueryString\Tokenizer;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\PercentagePlugin;
use Spryker\Zed\Discount\Communication\Plugin\Collector\ItemBySkuCollectorPlugin;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\SkuDecisionRulePlugin;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Messenger\Business\MessengerFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group Calculator
 * @group CalculatorTest
 */
class CalculatorTest extends Test
{

    const ITEM_GROSS_PRICE_500 = 500;

    /**
     * @return void
     */
    public function testCalculationWithoutAnyDiscountShouldReturnEmptyData()
    {
        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();

        $result = $calculator->calculate([], $quoteTransfer);

        $this->assertEquals(0, count($result));
    }

    /**
     * @return void
     */
    public function testOneDiscountShouldNotBeFilteredOut()
    {
        $discountCollection = [];
        $discountCollection[] = $discount = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            true
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();

        $result = $calculator->calculate(
            $discountCollection,
            $quoteTransfer
        );

        $this->assertEquals(1, count($result));
    }

    /**
     * @param string $displayName
     * @param string $calculatorPlugin
     * @param int $amount
     * @param bool $isActive
     * @param bool $isExclusive
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function initializeDiscount(
        $displayName,
        $calculatorPlugin,
        $amount,
        $isActive,
        $isExclusive = true
    ) {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setDisplayName($displayName);
        $discountTransfer->setAmount($amount);
        $discountTransfer->setIsActive($isActive);
        $discountTransfer->setCollectorQueryString('sku = "sku1"');
        $discountTransfer->setCalculatorPlugin($calculatorPlugin);
        $discountTransfer->setIsExclusive($isExclusive);

        return $discountTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransferWithTwoItems()
    {
        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(self::ITEM_GROSS_PRICE_500);
        $itemTransfer->setSku('sku1');
        $quoteTransfer->addItem($itemTransfer);
        $quoteTransfer->addItem(clone $itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\Calculator
     */
    protected function getCalculator()
    {
        $calculatorPlugins = $this->createCalculatorPlugins();

        $messengerFacade = $this->createDiscountToMessengerBridge();
        $distributor = $this->createDistributor();
        $collectorBuilder = $this->createCollectorBuilder();

        return new Calculator(
            $collectorBuilder,
            $messengerFacade,
            $distributor,
            $calculatorPlugins
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder
     */
    protected function createCollectorBuilder()
    {
        return new SpecificationBuilder(
            $this->createTokenizer(),
            $this->createCollectorSpecificationProvider(),
            $this->createComparatorOperators(),
            $this->createClauseValidator(),
            $this->createMetaDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected function createComparatorOperators()
    {
        $operators = (new OperatorProvider())->createComparators();
        return new ComparatorOperators($operators);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\ClauseValidator
     */
    protected function createClauseValidator()
    {
        return new ClauseValidator(
            $this->createComparatorOperators(),
            $this->createMetaDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider
     */
    protected function createMetaDataProvider()
    {
        return new MetaDataProvider(
            $this->createDecisionRulePlugins(),
            $this->createComparatorOperators(),
            $this->createLogicalOperators()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[]
     */
    protected function createDecisionRulePlugins()
    {
        return [
            new SkuDecisionRulePlugin()
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider
     */
    protected function createCollectorSpecificationProvider()
    {
        $collectorPlugins = $this->createCollectorPlugins();

        return new CollectorProvider($collectorPlugins);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface[]
     */
    protected function createCollectorPlugins()
    {
         $collectorProviderPlugins[] = new ItemBySkuCollectorPlugin();

        return $collectorProviderPlugins;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Distributor\Distributor
     */
    protected function createDistributor()
    {
        return new Distributor();
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\MessengerFacade
     */
    protected function createMessengerFacade()
    {
        return new MessengerFacade();
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Plugin\Calculator\PercentagePlugin
     */
    protected function createPercentageCalculator()
    {
        return new PercentagePlugin();
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractLocatorLocator|static
     */
    protected function createLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge
     */
    protected function createDiscountToMessengerBridge()
    {
        return new DiscountToMessengerBridge($this->createMessengerFacade());
    }

    /**
     * @return array
     */
    protected function createCalculatorPlugins()
    {
        $calculatorPlugins[DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE] = $this->createPercentageCalculator();

        return $calculatorPlugins;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Tokenizer
     */
    protected function createTokenizer()
    {
        return new Tokenizer();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\LogicalComparators
     */
    protected function createLogicalOperators()
    {
        return new LogicalComparators();
    }

}
