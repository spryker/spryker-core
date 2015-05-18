<?php

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Shared\Transfer\CalculationDiscountTransfer;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderItemOptionTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\OptionPriceToPayCalculator;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @group OptionPriceToPayTest
 * @group Calculation
 */
class OptionPriceToPayTest extends \PHPUnit_Framework_TestCase
{
    const ITEM_GROSS_PRICE = 10000;
    const ITEM_SALESRULE_DISCOUNT_AMOUNT = 100;
    const ITEM_COUPON_DISCOUNT_AMOUNT = 50;
    const ITEM_OPTION_1000 = 1000;

    public function testPriceToPayShouldReturnItemGrossPriceForAnOrderWithOneItem()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->addItem($item);

        $option = $this->getItemOption();
        $option->setGrossPrice(self::ITEM_OPTION_1000);
        $item->addOption($option);

        $calculator = new OptionPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            foreach ($item->getOptions() as $option) {
                $this->assertEquals(self::ITEM_OPTION_1000, $option->getPriceToPay());
            }
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountForAnOrderWithOneItemWithCouponDiscountAmmount()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->addItem($item);

        $option = $this->getItemOption();
        $option->setGrossPrice(self::ITEM_OPTION_1000);
        $item->addOption($option);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $option->addDiscount($discount);

        $calculator = new OptionPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            foreach ($item->getOptions() as $option) {
                $this->assertEquals(
                    self::ITEM_OPTION_1000 - self::ITEM_COUPON_DISCOUNT_AMOUNT,
                    $option->getPriceToPay()
                );
            }
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountMinusDiscountAmountForAnOrderWithOneItemAndBothDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->addItem($item);

        $option = $this->getItemOption();
        $option->setGrossPrice(self::ITEM_OPTION_1000);
        $item->addOption($option);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $option->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $option->addDiscount($discount);

        $calculator = new OptionPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            foreach ($item->getOptions() as $option) {
                $this->assertEquals(
                    self::ITEM_OPTION_1000 - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                    $option->getPriceToPay()
                );
            }
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountMinusDiscountAmountForAnOrderWithTwoItemsAndBothDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $option = $this->getItemOption();
        $option->setGrossPrice(self::ITEM_OPTION_1000);
        $item->addOption($option);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $option->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $option->addDiscount($discount);

        $order->addItem($item);
        $order->addItem(clone $item);

        $calculator = new OptionPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        foreach ($order->getItems() as $item) {
            foreach ($item->getOptions() as $option) {
                $this->assertEquals(
                    self::ITEM_OPTION_1000 - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                    $option->getPriceToPay()
                );
            }
        }
    }

    /**
     * @return OrderItemOptionTransfer
     */
    protected function getItemOption()
    {
        return new OrderItemOptionTransfer();
    }

    /**
     * @return CalculationDiscountTransfer
     */
    protected function getPriceDiscount()
    {
        return new CalculationDiscountTransfer();
    }

    /**
     * @return OrderTransfer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new OrderTransfer();

        return $order;
    }

    /**
     * @return OrderItemTransfer
     */
    protected function getItemWithFixtureData()
    {
        $item = new OrderItemTransfer();

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
