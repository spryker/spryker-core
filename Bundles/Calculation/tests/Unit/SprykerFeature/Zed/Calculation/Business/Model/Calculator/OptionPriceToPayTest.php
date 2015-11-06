<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ProductOptionPriceToPayCalculator;
use SprykerFeature\Zed\Sales\Business\Model\CalculableContainer;

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
        $order->getCalculableObject()->addItem($item);

        $option = $this->getItemOption();
        $option->setGrossPrice(self::ITEM_OPTION_1000);
        $item->addProductOption($option);

        $calculator = new ProductOptionPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        $items = $this->getItems($order);
        foreach ($items as $item) {
            foreach ($item->getProductOptions() as $option) {
                $this->assertEquals(self::ITEM_OPTION_1000, $option->getPriceToPay());
            }
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountForAnOrderWithOneItemWithCouponDiscountAmount(
    ) {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $option = $this->getItemOption();
        $option->setGrossPrice(self::ITEM_OPTION_1000);
        $item->addProductOption($option);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $option->addDiscount($discount);

        $calculator = new ProductOptionPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        $items = $this->getItems($order);
        foreach ($items as $item) {
            foreach ($item->getProductOptions() as $option) {
                $this->assertEquals(
                    self::ITEM_OPTION_1000 - self::ITEM_COUPON_DISCOUNT_AMOUNT,
                    $option->getPriceToPay()
                );
            }
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountMinusDiscountAmountForAnOrderWithOneItemAndBothDiscounts(
    ) {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $option = $this->getItemOption();
        $option->setGrossPrice(self::ITEM_OPTION_1000);
        $item->addProductOption($option);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $option->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $option->addDiscount($discount);

        $calculator = new ProductOptionPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        $items = $this->getItems($order);
        foreach ($items as $item) {
            foreach ($item->getProductOptions() as $option) {
                $this->assertEquals(
                    self::ITEM_OPTION_1000 - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                    $option->getPriceToPay()
                );
            }
        }
    }

    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountMinusDiscountAmountForAnOrderWithTwoItemsAndBothDiscounts(
    ) {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $option = $this->getItemOption();
        $option->setGrossPrice(self::ITEM_OPTION_1000);
        $item->addProductOption($option);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $option->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $option->addDiscount($discount);

        $order->getCalculableObject()->addItem($item);
        $order->getCalculableObject()->addItem(clone $item);

        $calculator = new ProductOptionPriceToPayCalculator(Locator::getInstance());
        $calculator->recalculate($order);

        $items = $this->getItems($order);
        foreach ($items as $item) {
            foreach ($item->getProductOptions() as $option) {
                $this->assertEquals(
                    self::ITEM_OPTION_1000 - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                    $option->getPriceToPay()
                );
            }
        }
    }

    /**
     * @return ProductOptionTransfer
     */
    protected function getItemOption()
    {
        return new ProductOptionTransfer();
    }

    /**
     * @return DiscountTransfer
     */
    protected function getPriceDiscount()
    {
        $discountTransfer = new DiscountTransfer();

        return $discountTransfer;
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

    /**
     * @return AbstractLocatorLocator|Locator|AutoCompletion
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @param CalculableInterface $order
     *
     * @return ItemInterface[]
     */
    protected function getItems(CalculableInterface $calculableContainer)
    {
        return $calculableContainer->getCalculableObject()->getItems();
    }

}
