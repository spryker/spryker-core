<?php

namespace Functional\SprykerFeature\Zed\Discount\Business\Model;

use Codeception\TestCase\Test;
use SprykerFeature\Zed\Discount\Business\Model\Calculator;
use SprykerFeature\Zed\Discount\Business\DecisionRule;
use SprykerFeature\Zed\Discount\Business\DiscountDependencyContainer;
use SprykerFeature\Zed\Discount\Business\Model\Distributor;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * Class VoucherEngineTest
 * @group VoucherEngineTest
 * @group Discount
 * @package Unit\SprykerFeature\Zed\Discount\Business\Model
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
        $settings = (new DiscountDependencyContainer(new Factory('Discount'), Locator::getInstance()))->getDiscountSettings();
        $calculator = new Calculator();

        $order = $this->getOrderWithTwoItems();

        $result = $calculator->calculate([], $order, $settings, new Distributor(Locator::getInstance()));

        $this->assertEquals(0, count($result));
    }

    public function testOneDiscountShouldNotBeFilteredOut()
    {
        $discount = $this->initializeDiscount(
            'name 1',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $settings = (new DiscountDependencyContainer(new Factory('Discount'), Locator::getInstance()))->getDiscountSettings();
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
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = (new DiscountDependencyContainer(new Factory('Discount'), Locator::getInstance()))->getDiscountSettings();
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
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount3 = $this->initializeDiscount(
            'name 3',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = (new DiscountDependencyContainer(new Factory('Discount'), Locator::getInstance()))->getDiscountSettings();
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
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount3 = $this->initializeDiscount(
            'name 3',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount4 = $this->initializeDiscount(
            'name 4',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = (new DiscountDependencyContainer(new Factory('Discount'), Locator::getInstance()))->getDiscountSettings();
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
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $discount2 = $this->initializeDiscount(
            'name 2',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount3 = $this->initializeDiscount(
            'name 3',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount4 = $this->initializeDiscount(
            'name 4',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $discount5 = $this->initializeDiscount(
            'name 5',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            80,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );

        $settings = (new DiscountDependencyContainer(new Factory('Discount'), Locator::getInstance()))->getDiscountSettings();
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
        $discount = new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount();
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
     * @return mixed
     */
    protected function getOrderWithTwoItems()
    {
        $locator = Locator::getInstance();
        $order = $locator->sales()->transferOrder();
        $item = $locator->sales()->transferOrderItem();
        $itemCollection = $locator->sales()->transferOrderItemCollection();

        $item->setGrossPrice(self::ITEM_GROSS_PRICE_500);
        $itemCollection->add($item);
        $itemCollection->add(clone $item);

        $order->setItems($itemCollection);

        return $order;
    }
}
