<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Discount\Business\Model;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\Model\Calculator;
use Spryker\Zed\Discount\Business\Model\CollectorResolver;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\Percentage;
use Spryker\Zed\Discount\Communication\Plugin\Collector\Item;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Locator;

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
    public function testCalculationWithoutAnyDiscountShouldNotReturnMatchingDiscounts()
    {
        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();

        $result = $calculator->calculate([], $quoteTransfer, new Distributor(Locator::getInstance()));

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
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();

        $result = $calculator->calculate(
            $discountCollection,
            $quoteTransfer,
            new Distributor(Locator::getInstance())
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
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate($discountCollection, $quoteTransfer, new Distributor(Locator::getInstance()));
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
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 3',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = new DiscountConfig(Config::getInstance(), Locator::getInstance());
        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate($discountCollection, $quoteTransfer, new Distributor(Locator::getInstance()));
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
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 3',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 4',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $calculator = $this->getCalculator();

        $order = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate(
            $discountCollection,
            $order,
            new Distributor(Locator::getInstance())
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
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 3',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 4',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate($discountCollection, $quoteTransfer, new Distributor(Locator::getInstance()));
        $this->assertEquals(3, count($result));
    }

    /**
     * @param string $displayName
     * @param string $calculatorPlugin
     * @param int $amount
     * @param bool $isActive
     * @param string $collectorPlugin
     * @param bool $isPrivileged
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function initializeDiscount(
        $displayName,
        $calculatorPlugin,
        $amount,
        $isActive,
        $collectorPlugin,
        $isPrivileged = true
    ) {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setDisplayName($displayName);
        $discountTransfer->setAmount($amount);
        $discountTransfer->setIsActive($isActive);
        $discountTransfer->setCalculatorPlugin($calculatorPlugin);

        $discountCollectorTransfer = new DiscountCollectorTransfer();
        $discountCollectorTransfer->setCollectorPlugin($collectorPlugin);

        $discountTransfer->addDiscountCollectors($discountCollectorTransfer);
        $discountTransfer->setIsPrivileged($isPrivileged);

        return $discountTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransferWithTwoItems()
    {
        $quoteTransfer = new QuoteTransfer();

        $item = new ItemTransfer();
        $item->setUnitGrossPrice(self::ITEM_GROSS_PRICE_500);
        $quoteTransfer->addItem($item);
        $quoteTransfer->addItem(clone $item);

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Model\Calculator
     */
    protected function getCalculator()
    {
        $locator = Locator::getInstance();
        $calculatorPlugins[DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE] = new Percentage();
        $collectorPlugins[DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM] = new Item();

        $collectorResolver = new CollectorResolver($collectorPlugins);
        $messengerFacade = new DiscountToMessengerBridge($locator->messenger()->facade());
        $calculator = new Calculator($collectorResolver, $messengerFacade, $calculatorPlugins);

        return $calculator;
    }

}
