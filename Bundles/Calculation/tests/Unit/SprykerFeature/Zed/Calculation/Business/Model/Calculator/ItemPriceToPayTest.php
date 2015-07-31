<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ItemPriceToPayCalculator;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Sales\Business\Model\CalculableContainer;

/**
 * Class ItemPriceToPayTest
 *
 * @group ItemPriceToPayTest
 * @group Calculation
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
        $order->getCalculableObject()->addItem($item);

        $calculator = new ItemPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            $this->assertEquals(self::ITEM_GROSS_PRICE, $item->getPriceToPay());
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountForAnOrderWithOneItemWithCouponDiscountAmmount()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $order->getCalculableObject()->addItem($item);
        $calculator = new ItemPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            $this->assertEquals(self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT, $item->getPriceToPay());
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountMinusDiscountAmountForAnOrderWithOneItemAndBothDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $order->getCalculableObject()->addItem($item);
        $calculator = new ItemPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            $this->assertEquals(
                self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                $item->getPriceToPay()
            );
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountMinusDiscountAmountForAnOrderWithTwoItemsAndBothDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $order->getCalculableObject()->addItem($item);
        $order->getCalculableObject()->addItem(clone $item);
        $calculator = new ItemPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            $this->assertEquals(
                self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                $item->getPriceToPay()
            );
        }
    }

    /**
     * @return DiscountTransfer
     */
    protected function getPriceDiscount()
    {
        return new DiscountTransfer();
    }

    /**
     * @return CalculableContainer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new OrderTransfer();

        return new CalculableContainer($order);
    }

    /**
     * @return ItemTransfer
     */
    protected function getItemWithFixtureData()
    {
        $item = new ItemTransfer();

        return $item;
    }

}
