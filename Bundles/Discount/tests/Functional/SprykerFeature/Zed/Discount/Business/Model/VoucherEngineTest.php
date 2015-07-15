<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Discount\Business\Model;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use SprykerEngine\Shared\Config;
use SprykerFeature\Zed\Discount\Business\Model\Calculator;
use SprykerFeature\Zed\Discount\Business\Model\Distributor;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\Sales\Business\Model\CalculableContainer;

/**
 * @group VoucherEngineTest
 * @group Discount
 */
class VoucherEngineTest extends Test
{

    const ITEM_GROSS_PRICE_500 = 500;

    protected function setUp()
    {
        parent::setUp();
    }

    public function testCalculationWithoutAnyDiscountShouldNotReturnMatchingDiscounts()
    {
        $settings = new DiscountConfig(Config::getInstance(), Locator::getInstance());
        $calculator = new Calculator();

        $order = $this->getOrderWithTwoItems();

        $result = $calculator->calculate([], $order, $settings, new Distributor(Locator::getInstance()));

        $this->assertEquals(0, count($result));
    }

    public function testOneDiscountShouldNotBeFilteredOut()
    {
        $discount = $this->initializeDiscount(
            'name 1',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $settings = new DiscountConfig(Config::getInstance(), Locator::getInstance());
        $calculator = new Calculator();

        $order = $this->getOrderWithTwoItems();

        $result = $calculator->calculate([$discount], $order, $settings, new Distributor(Locator::getInstance()));

        $this->assertEquals(1, count($result));
        $discount->delete();
    }

    public function testTwoDiscountsShouldNotBeFilteredOut()
    {
        $discount1 = $this->initializeDiscount(
            'name 1',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = new DiscountConfig(Config::getInstance(), Locator::getInstance());
        $calculator = new Calculator();

        $order = $this->getOrderWithTwoItems();
        $result = $calculator->calculate([$discount1, $discount2], $order, $settings, new Distributor(Locator::getInstance()));
        $this->assertEquals(2, count($result));

        $discount1->delete();
        $discount2->delete();
    }

    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanOne()
    {
        $discount1 = $this->initializeDiscount(
            'name 1',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount3 = $this->initializeDiscount(
            'name 3',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = new DiscountConfig(Config::getInstance(), Locator::getInstance());
        $calculator = new Calculator();

        $order = $this->getOrderWithTwoItems();
        $result = $calculator->calculate(
            [$discount1, $discount2, $discount3], $order, $settings, new Distributor(Locator::getInstance())
        );
        $this->assertEquals(2, count($result));

        $discount1->delete();
        $discount2->delete();
        $discount3->delete();
    }

    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanTwo()
    {
        $discount1 = $this->initializeDiscount(
            'name 1',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount3 = $this->initializeDiscount(
            'name 3',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount4 = $this->initializeDiscount(
            'name 4',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = new DiscountConfig(Config::getInstance(), Locator::getInstance());
        $calculator = new Calculator();

        $order = $this->getOrderWithTwoItems();
        $result = $calculator->calculate([$discount1, $discount2, $discount3, $discount4], $order, $settings, new Distributor(Locator::getInstance()));
        $this->assertEquals(2, count($result));

        $discount1->delete();
        $discount2->delete();
        $discount3->delete();
        $discount4->delete();
    }

    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanTwoAndTwoPrivilegedOnes()
    {
        $discount1 = $this->initializeDiscount(
            'name 1',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount3 = $this->initializeDiscount(
            'name 3',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount4 = $this->initializeDiscount(
            'name 4',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount5 = $this->initializeDiscount(
            'name 5',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            80,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $settings = new DiscountConfig(Config::getInstance(), Locator::getInstance());
        $calculator = new Calculator();

        $order = $this->getOrderWithTwoItems();
        $result = $calculator->calculate([$discount1, $discount2, $discount3, $discount4, $discount5], $order, $settings, new Distributor(Locator::getInstance()));
        $this->assertEquals(3, count($result));

        $discount1->delete();
        $discount2->delete();
        $discount3->delete();
        $discount4->delete();
        $discount5->delete();
    }

    /**
     * @param $displayName
     * @param $calculatorPlugin
     * @param $amount
     * @param $isActive
     * @param $collectorPlugin
     * @param bool $isPrivileged
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount
     */
    protected function initializeDiscount(
        $displayName,
        $calculatorPlugin,
        $amount,
        $isActive,
        $collectorPlugin,
        $isPrivileged = true
    ) {
        $discount = new SpyDiscount();
        $discount->setDisplayName($displayName);
        $discount->setAmount($amount);
        $discount->setIsActive($isActive);
        $discount->setCalculatorPlugin($calculatorPlugin);
        $discount->setCollectorPlugin($collectorPlugin);
        $discount->setIsPrivileged($isPrivileged);
        $discount->save();

        return $discount;
    }

    /**
     * @return CalculableContainer
     */
    protected function getOrderWithTwoItems()
    {
        $order = new OrderTransfer();
        $item = new OrderItemTransfer();
        $itemCollection = new OrderItemsTransfer();

        $item->setGrossPrice(self::ITEM_GROSS_PRICE_500);
        $itemCollection->addOrderItem($item);
        $itemCollection->addOrderItem(clone $item);

        $order->setItems($itemCollection);

        return new CalculableContainer($order);
    }

}
