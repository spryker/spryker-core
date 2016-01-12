<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Discount\Business\Model;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\Model\Calculator;
use Spryker\Zed\Discount\Business\Model\CollectorResolver;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Messenger\Business\MessengerFacade;
use Spryker\Zed\Sales\Business\Model\CalculableContainer;

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
        $settings = new DiscountConfig();
        $calculator = $this->getCalculator();

        $order = $this->getCalculableContainerWithTwoItems();

        $result = $calculator->calculate([], $order, $settings, new Distributor());

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
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $settings = new DiscountConfig();
        $calculator = $this->getCalculator();

        $calculableContainer = $this->getCalculableContainerWithTwoItems();

        $result = $calculator->calculate(
            $discountCollection,
            $calculableContainer,
            $settings,
            new Distributor()
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
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = new DiscountConfig();

        $calculator = $this->getCalculator();

        $order = $this->getCalculableContainerWithTwoItems();
        $result = $calculator->calculate(
            $discountCollection,
            $order,
            $settings,
            new Distributor()
        );
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
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 3',
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = new DiscountConfig();
        $calculator = $this->getCalculator();

        $order = $this->getCalculableContainerWithTwoItems();
        $result = $calculator->calculate(
            $discountCollection,
            $order,
            $settings,
            new Distributor()
        );
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
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 3',
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 4',
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = new DiscountConfig();
        $calculator = $this->getCalculator();

        $order = $this->getCalculableContainerWithTwoItems();
        $result = $calculator->calculate(
            $discountCollection,
            $order,
            $settings,
            new Distributor()
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
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 2',
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 3',
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $discountCollection[] = $this->initializeDiscount(
            'name 4',
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountConstants::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = new DiscountConfig();
        $calculator = $this->getCalculator();

        $order = $this->getCalculableContainerWithTwoItems();
        $result = $calculator->calculate($discountCollection, $order, $settings, new Distributor());
        $this->assertEquals(3, count($result));
    }

    /**
     * @param $displayName
     * @param $calculatorPlugin
     * @param $amount
     * @param $isActive
     * @param $collectorPlugin
     * @param bool $isPrivileged
     *
     * @return DiscountTransfer
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
     * @return CalculableContainer
     */
    protected function getCalculableContainerWithTwoItems()
    {
        $order = new OrderTransfer();
        $item = new ItemTransfer();

        $item->setGrossPrice(self::ITEM_GROSS_PRICE_500);
        $order->addItem($item);
        $order->addItem(clone $item);

        return new CalculableContainer($order);
    }

    /**
     * @return Calculator
     */
    protected function getCalculator()
    {
        $settings = new DiscountConfig();
        $collectorResolver = new CollectorResolver($settings);

        $messengerFacade = new MessengerFacade();
        $calculator = new Calculator($collectorResolver, new DiscountToMessengerBridge($messengerFacade));

        return $calculator;
    }

}
