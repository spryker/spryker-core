<?php

namespace Functional\SprykerFeature\Zed\Discount\Business;

use Codeception\TestCase\Test;
use SprykerFeature\Zed\Discount\Business\Model\Calculator;
use SprykerFeature\Zed\Discount\Business\DecisionRule;
use SprykerFeature\Zed\Discount\Business\DiscountDependencyContainer;
use SprykerFeature\Zed\Discount\Business\Model\Distributor;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * Class CalculatorTest
 * @group DiscountCalculatorTest
 * @group Discount
 * @package Unit\SprykerFeature\Zed\Discount\Business
 */
class CalculatorTest extends Test
{
    const ITEM_GROSS_PRICE_500 = 500;

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
    }

    public function testTwoDiscountsShouldNotBeFilteredOut()
    {
        $this->initializeDiscount(
            'name 1',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $this->initializeDiscount(
            'name 2',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = (new DiscountDependencyContainer(new Factory('Discount'), Locator::getInstance()))
            ->getDiscountSettings();
        $calculator = new Calculator();

        $order = $this->getOrderWithTwoItems();
        $result = $calculator->calculate(
            $this->retrieveDiscounts(),
            $order,
            $settings,
            new Distributor(Locator::getInstance())
        );
        $this->assertEquals(2, count($result));
    }

    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanOne()
    {
        $this->initializeDiscount(
            'name 1',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $this->initializeDiscount(
            'name 2',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $this->initializeDiscount(
            'name 3',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = (new DiscountDependencyContainer(new Factory('Discount'), Locator::getInstance()))
            ->getDiscountSettings();
        $calculator = new Calculator();

        $order = $this->getOrderWithTwoItems();
        $result = $calculator->calculate(
            $this->retrieveDiscounts(),
            $order,
            $settings,
            new Distributor(Locator::getInstance())
        );
        $this->assertEquals(2, count($result));
    }

    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanTwo()
    {
        $this->initializeDiscount(
            'name 1',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $this->initializeDiscount(
            'name 2',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $this->initializeDiscount(
            'name 3',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $this->initializeDiscount(
            'name 4',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            70,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );

        $settings = (new DiscountDependencyContainer(new Factory('Discount'), Locator::getInstance()))
            ->getDiscountSettings();
        $calculator = new Calculator();

        $order = $this->getOrderWithTwoItems();
        $result = $calculator->calculate(
            $this->retrieveDiscounts(),
            $order,
            $settings,
            new Distributor(Locator::getInstance())
        );
        $this->assertEquals(2, count($result));
    }

    public function testFilterOutLowestUnprivilegedDiscountIfThereAreMoreThanTwoAndTwoPrivilegedOnes()
    {
        $this->initializeDiscount(
            'name 1',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $this->initializeDiscount(
            'name 2',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            50,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            true
        );
        $this->initializeDiscount(
            'name 3',
            DiscountDependencyContainer::PLUGIN_CALCULATOR_PERCENTAGE,
            60,
            true,
            DiscountDependencyContainer::PLUGIN_COLLECTOR_ITEM,
            false
        );
        $this->initializeDiscount(
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
        $result = $calculator->calculate($this->retrieveDiscounts(), $order, $settings, new Distributor(Locator::getInstance()));
        $this->assertEquals(3, count($result));
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

    /**
     * @return array
     */
    protected function retrieveDiscounts()
    {
        $result = [];
        foreach ((new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery())->find() as $discount) {
            $result[] = $discount;
        }

        return $result;
    }
}
