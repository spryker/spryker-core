<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Discount\Business\Model;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Config;
use Spryker\Zed\Assertion\Business\AssertionFacade;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\Model\Calculator;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\Tokenizer;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\Percentage;
use Spryker\Zed\Discount\Communication\Plugin\Collector\ItemBySkuCollectorPlugin;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToAssertionBridge;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Messenger\Business\MessengerFacade;

/**
 * @group DiscountCalculatorTest
 * @group Discount
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
     * @return void
     */
    public function testTwoDiscountsShouldNotBeFilteredOut()
    {
        $discountCollection = [];
        $discountCollection[] = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            false
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate($discountCollection, $quoteTransfer);
        $this->assertEquals(2, count($result));
    }

    /**
     * @return void
     */
    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanOne()
    {
        $discountCollection = [];
        $discountCollection[] = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 3',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            false
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate($discountCollection, $quoteTransfer);
        $this->assertEquals(2, count($result));
    }

    /**
     * @return void
     */
    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanTwo()
    {
        $discountCollection = [];
        $discountCollection[] = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 3',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 4',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            false
        );

        $calculator = $this->getCalculator();

        $order = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate(
            $discountCollection,
            $order
        );
        $this->assertEquals(2, count($result));
    }

    /**
     * @return void
     */
    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanTwoAndTwoPrivilegedOnes()
    {
        $discountCollection = [];
        $discountCollection[] = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 3',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 4',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            false
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate($discountCollection, $quoteTransfer);
        $this->assertEquals(3, count($result));
    }

    /**
     * @param string $displayName
     * @param string $calculatorPlugin
     * @param int $amount
     * @param bool $isActive
     * @param bool $isPrivileged
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function initializeDiscount(
        $displayName,
        $calculatorPlugin,
        $amount,
        $isActive,
        $isPrivileged = true
    ) {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setDisplayName($displayName);
        $discountTransfer->setAmount($amount);
        $discountTransfer->setIsActive($isActive);
        $discountTransfer->setCollectorQueryString('sku = "sku1"');
        $discountTransfer->setCalculatorPlugin($calculatorPlugin);
        $discountTransfer->setIsPrivileged($isPrivileged);

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
     * @return \Spryker\Zed\Discount\Business\Model\Calculator
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
     * @return SpecificationBuilder
     */
    protected function createCollectorBuilder()
    {
        return new SpecificationBuilder(
            $this->createTokenizer(),
            $this->createDiscountToAssertionBridge(),
            $this->createCollectorSpecificationProvider()
        );
    }

    /**
     * @return CollectorProvider
     */
    protected function createCollectorSpecificationProvider()
    {
        $collectorPlugins = $this->createCollectorPlugins();

        return new CollectorProvider($collectorPlugins);
    }

    /**
     * @return CollectorPluginInterface[]
     */
    protected function createCollectorPlugins()
    {
         $collectorProviderPlugins[] = new ItemBySkuCollectorPlugin();

        return $collectorProviderPlugins;
    }

    /**
     * @return Distributor
     */
    protected function createDistributor()
    {
        return new Distributor();
    }

    /**
     * @return MessengerFacade
     */
    protected function createMessengerFacade()
    {
        return new MessengerFacade();
    }

    /**
     * @return Percentage
     */
    protected function createPercentageCalculator()
    {
        return new Percentage();
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractLocatorLocator|static
     */
    protected function createLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return DiscountToMessengerBridge
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
     * @return Tokenizer
     */
    protected function createTokenizer()
    {
        return new Tokenizer();
    }

    /**
     * @return DiscountToAssertionBridge
     */
    protected function createDiscountToAssertionBridge()
    {
        return new DiscountToAssertionBridge(new AssertionFacade());
    }

}
