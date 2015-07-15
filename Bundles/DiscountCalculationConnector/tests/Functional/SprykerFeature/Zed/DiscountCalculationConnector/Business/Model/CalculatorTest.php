<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\DiscountCalculationConnector\Business\Model;

use Generated\Shared\Transfer\DiscountItemsTransfer;
use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ExpensesTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerFeature\Shared\Sales\Code\ExpenseConstants;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\StackExecutor;
use SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin\DiscountCalculatorPlugin;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerFeature\Zed\Sales\Business\Model\CalculableContainer;

/**
 * @group CalculatorTest
 * @group Calculation
 */
class CalculatorTest extends AbstractFunctionalTest
{

    const ITEM_GROSS_PRICE = 10000;
    const ITEM_COUPON_DISCOUNT_AMOUNT = 1000;
    const ITEM_DISCOUNT_AMOUNT = 1000;
    const ORDER_SHIPPING_COSTS = 2000;
    const EXPENSE_NAME_SHIPPING_COSTS = 'Shipping Costs';
    /**
     * @var LocatorLocatorInterface|AutoCompletion
     */
    protected $locator;

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();
    }

    protected function createCalculatorStack()
    {
        $stack = [
            $this->getPluginByClassName('SprykerFeature\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin'),
            new DiscountCalculatorPlugin(new Factory('DiscountCalculationConnector'), $this->getLocator()),
            $this->getPluginByClassName('SprykerFeature\Zed\Calculation\Communication\Plugin\ExpenseTotalsCalculatorPlugin'),
            $this->getPluginByClassName('SprykerFeature\Zed\Calculation\Communication\Plugin\GrandTotalTotalsCalculatorPlugin'),
            $this->getPluginByClassName('SprykerFeature\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin'),
            $this->getPluginByClassName('SprykerFeature\Zed\Calculation\Communication\Plugin\ItemPriceToPayCalculatorPlugin'),
            $this->locator->discountCalculationConnector()->pluginGrandTotalWithDiscountsTotalsCalculatorPlugin(),
        ];

        return $stack;
    }

    public function testCanRecalculateAnEmptyOrder()
    {
        $order = $this->getOrderWithFixtureData();
        $calculator = $this->getCalculator();
        $calculatorStack = $this->createCalculatorStack();
        $calculator->recalculate($calculatorStack, $order);
        $this->assertEmpty($order->getCalculableObject()->getTotals()->getGrandTotalWithDiscounts());
    }

    public function testCanRecalculateAnExampleOrderWithOneItemAndExpenseOnOrder()
    {
        $order = $this->getOrderWithFixtureData();
        $items = $this->getItemCollection();
        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setQuantity(1);

        $discounts = $this->getPriceDiscountCollection();

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $discounts->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_DISCOUNT_AMOUNT);
        $discounts->addDiscount($discount);

        $expense = $this->getExpenseWithFixtureData();
        $expense->setName(self::EXPENSE_NAME_SHIPPING_COSTS)
            ->setType(ExpenseConstants::EXPENSE_SHIPPING)
            ->setPriceToPay(self::ORDER_SHIPPING_COSTS)
            ->setGrossPrice(self::ORDER_SHIPPING_COSTS)
        ;

        $expensesCollection = $this->getExpenseCollection();
        $expensesCollection->addCalculationExpense($expense);
        $order->getCalculableObject()->setExpenses($expensesCollection);

        $item->setDiscounts($discounts);
        $items->addOrderItem($item);
        $order->getCalculableObject()->setItems($items);

        $calculator = $this->getCalculator();

        $expected = self::ORDER_SHIPPING_COSTS
            + self::ITEM_GROSS_PRICE
            - self::ITEM_COUPON_DISCOUNT_AMOUNT
            - self::ITEM_DISCOUNT_AMOUNT;

        $calculatorStack = $this->createCalculatorStack();

        $calculator->recalculate($calculatorStack, $order);
        $totals = $order->getCalculableObject()->getTotals();
        $this->assertEquals($expected, $totals->getGrandTotalWithDiscounts());

        $calculator->recalculateTotals($calculatorStack, $order);
        $totals = $order->getCalculableObject()->getTotals();
        $this->assertEquals($expected, $totals->getGrandTotalWithDiscounts());

        $items = $order->getCalculableObject()->getItems();
        $expectedItemPriceToPay = self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_DISCOUNT_AMOUNT;

        foreach ($items as $item) {
            $this->assertEquals($expectedItemPriceToPay, $item->getPriceToPay());
        }
    }

    public function testCanRecalculateAnExampleOrderWithTwoItemsAndExpenseOnItems()
    {

        $order = $this->getOrderWithFixtureData();
//        $item = $this->getItemWithFixtureData();
//        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
//
//        $discount = $this->getPriceDiscount();
//        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
//        $item->addDiscount($discount);
//
//        $discount = $this->getPriceDiscount();
//        $discount->setAmount(self::ITEM_DISCOUNT_AMOUNT);
//        $item->addDiscount($discount);
//
//        $expense = $this->getExpenseWithFixtureData();
//        $expense->setName(self::EXPENSE_NAME_SHIPPING_COSTS)
//            ->setType(ExpenseConstants::EXPENSE_SHIPPING)
//            ->setPriceToPay(self::ORDER_SHIPPING_COSTS/2)
//            ->setGrossPrice(self::ORDER_SHIPPING_COSTS/2);
//
//        $item->addExpense($expense);
//
//        $order->addItem($item);
//        $order->addItem(clone $item);
//
//        $calculator = $this->getCalculator();
//        $calculatorStack = $this->createCalculatorStack();
//        $order = $calculator->recalculate($calculatorStack, $order);
//        $calculator->recalculateTotals($calculatorStack, $order);
//
//        $totals = $order->getTotals();
//        $expectedSubTotal = 2 * self::ITEM_GROSS_PRICE + self::ORDER_SHIPPING_COSTS;
//        $this->assertEquals($expectedSubTotal, $totals->getSubtotal());
//
//        $expectedGrandTotalWithDiscounts = self::ORDER_SHIPPING_COSTS + 2
//            * (self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_DISCOUNT_AMOUNT);
//        $this->assertEquals($expectedGrandTotalWithDiscounts, $totals->getGrandTotalWithDiscounts());
//
//        $items = $order->getItems();
//        $expectedItemPriceToPay = self::ORDER_SHIPPING_COSTS / 2 + self::ITEM_GROSS_PRICE
//            - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_DISCOUNT_AMOUNT;
//
//        foreach ($items as $item) {
//            $this->assertEquals($expectedItemPriceToPay, $item->getPriceToPay());
//        }

//        $order->getCalculableObject()->addItem($item);
//        $order->getCalculableObject()->addItem(clone $item);
//
//        $calculator = $this->getCalculator();
//        $calculatorStack = $this->createCalculatorStack();
//        $order = $calculator->recalculate($calculatorStack, $order);
//        $calculator->recalculateTotals($calculatorStack, $order);
//
//        $totals = $order->getCalculableObject()->getTotals();
//        $expectedSubTotal = 2 * self::ITEM_GROSS_PRICE + self::ORDER_SHIPPING_COSTS;
//        $this->assertEquals($expectedSubTotal, $totals->getSubtotal());
//
//        $expectedGrandTotalWithDiscounts = self::ORDER_SHIPPING_COSTS + 2
//            * (self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_DISCOUNT_AMOUNT);
//
//        $this->assertEquals($expectedGrandTotalWithDiscounts, $totals->getGrandTotalWithDiscounts());
//
//        $items = $order->getCalculableObject()->getItems();
//        $expectedItemPriceToPay = self::ORDER_SHIPPING_COSTS / 2 + self::ITEM_GROSS_PRICE
//            - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_DISCOUNT_AMOUNT;
//
//        foreach ($items as $item) {
//            $this->assertEquals($expectedItemPriceToPay, $item->getPriceToPay());
//        }
    }

    /**
     * @return StackExecutor
     */
    protected function getCalculator()
    {
        return new StackExecutor(Locator::getInstance());
    }

    /**
     * @return OrderItemsTransfer
     */
    protected function getItemCollection()
    {
        return new OrderItemsTransfer();
    }

    /**
     * @return DiscountItemsTransfer
     */
    protected function getPriceDiscountCollection()
    {
        return new DiscountItemsTransfer();
    }

    /**
     * @return ExpensesTransfer
     */
    protected function getExpenseCollection()
    {
        return new ExpensesTransfer();
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
        $totals = new TotalsTransfer();
        $totals->setDiscount(new DiscountTotalsTransfer());
        $order->setTotals($totals);

        $order->setDiscounts(new DiscountTransfer());

        return new CalculableContainer($order);
    }

    /**
     * @return OrderItemTransfer
     */
    protected function getItemWithFixtureData()
    {
        return new OrderItemTransfer();
    }

    /**
     * @return ExpenseTransfer
     */
    protected function getExpenseWithFixtureData()
    {
        return new ExpenseTransfer();
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}
