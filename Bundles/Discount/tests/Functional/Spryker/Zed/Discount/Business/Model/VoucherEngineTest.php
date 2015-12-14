<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Discount\Business\Model;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Config;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\Model\Calculator;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Discount\Business\Model\CollectorResolver;
use Spryker\Zed\Discount\DiscountConfig;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountCollector;
use Spryker\Zed\Discount\DiscountDependencyProvider;

/**
 * @group VoucherEngineTest
 * @group Discount
 */
class VoucherEngineTest extends Test
{

    const ITEM_GROSS_PRICE_500 = 500;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
    }

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
        $discount = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();

        $result = $calculator->calculate([$discount], $quoteTransfer, new Distributor(Locator::getInstance()));

        $this->assertEquals(1, count($result));
    }

    /**
     * @return void
     */
    public function testTwoDiscountsShouldNotBeFilteredOut()
    {
        $discount1 = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate([$discount1, $discount2], $quoteTransfer, new Distributor(Locator::getInstance()));
        $this->assertEquals(2, count($result));
    }

    /**
     * @return void
     */
    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanOne()
    {
        $discount1 = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount3 = $this->initializeDiscount(
            'name 3',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate(
            [$discount1, $discount2, $discount3], $quoteTransfer, new Distributor(Locator::getInstance())
        );
        $this->assertEquals(2, count($result));
    }

    /**
     * @return void
     */
    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanTwo()
    {
        $discount1 = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount3 = $this->initializeDiscount(
            'name 3',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount4 = $this->initializeDiscount(
            'name 4',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate([$discount1, $discount2, $discount3, $discount4], $quoteTransfer, new Distributor(Locator::getInstance()));
        $this->assertEquals(2, count($result));
    }

    /**
     * @return void
     */
    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanTwoAndTwoPrivilegedOnes()
    {
        $discount1 = $this->initializeDiscount(
            'name 1',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount3 = $this->initializeDiscount(
            'name 3',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount4 = $this->initializeDiscount(
            'name 4',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount5 = $this->initializeDiscount(
            'name 5',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            80,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $calculator = $this->getCalculator();

        $quoteTransfer = $this->getQuoteTransferWithTwoItems();
        $result = $calculator->calculate([$discount1, $discount2, $discount3, $discount4, $discount5], $quoteTransfer, new Distributor(Locator::getInstance()));
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
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    protected function initializeDiscount(
        $displayName,
        $calculatorPlugin,
        $amount,
        $isActive,
        $collectorPlugin,
        $isPrivileged = true
    ) {
        $discountEntity = new SpyDiscount();
        $discountEntity->setDisplayName($displayName);
        $discountEntity->setAmount($amount);
        $discountEntity->setIsActive($isActive);
        $discountEntity->setCalculatorPlugin($calculatorPlugin);
        $discountEntity->setIsPrivileged($isPrivileged);
        $discountEntity->save();

        $discountCollectorEntity = new SpyDiscountCollector();
        $discountCollectorEntity->setCollectorPlugin($collectorPlugin);
        $discountCollectorEntity->setFkDiscount($discountEntity->getIdDiscount());
        $discountCollectorEntity->save();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->fromArray($discountEntity->toArray(), true);

        $discountCollectorTransfer = new DiscountCollectorTransfer();
        $discountCollectorTransfer->fromArray($discountCollectorEntity->toArray(), true);
        $discountTransfer->addDiscountCollectors($discountCollectorTransfer);

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
    protected function getCalculator(array $collectorPlugins = [], array $calculatorPlugins = [])
    {
        $locator = Locator::getInstance();
        $calculatorPlugins[DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE] = $locator->discount()->pluginCalculatorPercentage();
        $collectorPlugins[DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM] = $locator->discount()->pluginCollectorItem();

        $collectorResolver = new CollectorResolver($collectorPlugins);
        $messengerFacade = $locator->Messenger()->facade();
        $calculator = new Calculator($collectorResolver, $messengerFacade, $calculatorPlugins);

        return $calculator;
    }

}
