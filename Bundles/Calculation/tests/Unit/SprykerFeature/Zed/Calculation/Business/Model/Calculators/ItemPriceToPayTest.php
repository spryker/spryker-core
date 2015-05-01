<?php

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Calculation\Transfer\Discount;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ItemPriceToPayCalculator;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * Class ItemPriceToPayTest
 * @group ItemPriceToPayTest
 * @group Calculation
 * @package PhpUnit\SprykerFeature\Zed\Calculation\Communication\Plugin
 */
class ItemPriceToPayTest extends \PHPUnit_Framework_TestCase
{
    const ITEM_GROSS_PRICE = 10000;
    const ITEM_SALESRULE_DISCOUNT_AMOUNT = 100;
    const ITEM_COUPON_DISCOUNT_AMOUNT = 50;

    public function testPriceToPayShouldReturnItemGrossPriceForAnOrderWithOneItem()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->addItem($item);

        $calculator = new ItemPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            $this->assertEquals(self::ITEM_GROSS_PRICE, $item->getPriceToPay());
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountForAnOrderWithOneItemWithCouponDiscountAmmount()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->addItem($item);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $order->addItem($item);
        $calculator = new ItemPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            $this->assertEquals(self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT, $item->getPriceToPay());
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountMinusSalesruleDoscountAmountForAnOrderWithOneItemAndBothDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->addItem($item);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $order->addItem($item);
        $calculator = new ItemPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            $this->assertEquals(
                self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                $item->getPriceToPay()
            );
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountMinusSalesruleDiscountAmountForAnOrderWithTwoItemsAndBothDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->addItem($item);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $order->addItem($item);
        $order->addItem(clone $item);
        $calculator = new ItemPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            $this->assertEquals(
                self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                $item->getPriceToPay()
            );
        }
    }

    /**
     * @return Discount
     */
    protected function getPriceDiscount()
    {
        return new \Generated\Shared\Transfer\CalculationDiscountTransfer();
    }

    /**
     * @return Order
     */
    protected function getOrderWithFixtureData()
    {
        $order = new \Generated\Shared\Transfer\SalesOrderTransfer();
        $order->fillWithFixtureData();

        return $order;
    }

    /**
     * @return OrderItem
     */
    protected function getItemWithFixtureData()
    {
        $item = new \Generated\Shared\Transfer\SalesOrderItemTransfer();
        $item->fillWithFixtureData();

        return $item;
    }

    /**
     * @return AbstractLocatorLocator|Locator|AutoCompletion
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }
}
