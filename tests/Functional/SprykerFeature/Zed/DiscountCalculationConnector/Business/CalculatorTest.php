<?php

namespace Functional\SprykerFeature\Zed\DiscountCalculationConnector\Business;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Sales\Code\ExpenseConstants;
use SprykerFeature\Zed\Calculation\Business\Model\StackExecutor;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator;

/**
 * @group Salesrule
 * @group Calculator
 */
class CalculatorTest extends Test
{
    const ITEM_GROSS_PRICE = 10000;
    const ITEM_COUPON_DISCOUNT_AMOUNT = 1000;
    const ITEM_SALESRULE_DISCOUNT_AMOUNT = 1000;
    const ORDER_SHIPPING_COSTS = 2000;

    /**
     * @var LocatorLocatorInterface|AutoCompletion
     */
    protected $locator;

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();
    }

    /**
     * @return StackExecutor
     */
    protected function getCalculatorModel()
    {
        $calculator = $this->getMock('\SprykerFeature\Zed\Calculation\Business\Model\StackExecutor', [], [$this->locator]);
        return $calculator;
    }

    protected function createCalculatorStack()
    {
        $stack = [
            new Calculator\ExpenseTotalsCalculator($this->locator),
            new Calculator\SubtotalTotalsCalculator($this->locator),
            new GrandTotalTotalsCalculator(
                $this->locator,
                new Calculator\SubtotalTotalsCalculator($this->locator),
                new Calculator\ExpenseTotalsCalculator($this->locator)
            ),
            new Calculator\ExpensePriceToPayCalculator($this->locator),
            new Calculator\ItemPriceToPayCalculator($this->locator),
            new DiscountTotalsCalculator($this->locator),
            $this->locator->discountCalculationConnector()->pluginGrandTotalWithDiscountsTotalsCalculatorPlugin()
        ];

        return $stack;
    }

    public function testCanRecalculateAnEmptyOrder()
    {
        $order = $this->getLocator()->sales()->transferOrder();
        $calculator = $this->getCalculatorModel();
        $calculatorStack = $this->createCalculatorStack();
        $calculator->recalculate($calculatorStack, $order);
        $this->assertEmpty($order->getTotals()->getGrandTotalWithDiscounts());
    }

    public function testCanRecalculateAnExampleOrderWithOneItemAndExpenseOnOrder()
    {
        $order = $this->getLocator()->sales()->transferOrder();
        $items = $this->getLocator()->sales()->transferOrderItemCollection();
        $item =  $this->getLocator()->sales()->transferOrderItem();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $discounts = $this->getLocator()->calculation()->transferDiscountCollection();
        $discount = $this->getLocator()->calculation()->transferDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $discounts->add($discount);
        $discount = $this->getLocator()->calculation()->transferDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $discounts->add($discount);

        $expense = $this->getLocator()->calculation()->transferExpense();
        $expense->setName('Shipping Costs')
            ->setType(ExpenseConstants::EXPENSE_SHIPPING)
            ->setPriceToPay(self::ORDER_SHIPPING_COSTS)
            ->setGrossPrice(self::ORDER_SHIPPING_COSTS);

        $expensesCollection = $this->getLocator()->calculation()->transferExpenseCollection();
        $expensesCollection->add($expense);
        $order->setExpenses($expensesCollection);

        $item->setDiscounts($discounts);
        $items->add($item);
        $order->setItems($items);

        $calculator = new StackExecutor($this->locator);

        $calculatorStack = $this->createCalculatorStack();
        $calculator->recalculate($calculatorStack, $order);
        $calculator->recalculateTotals($calculatorStack, $order, null);

        $expected = self::ORDER_SHIPPING_COSTS
            + self::ITEM_GROSS_PRICE
            - self::ITEM_COUPON_DISCOUNT_AMOUNT
            - self::ITEM_SALESRULE_DISCOUNT_AMOUNT;

        $actual = $order->getTotals()->getGrandTotalWithDiscounts();
        $this->assertEquals($expected, $actual);

        foreach ($order->getItems() as $item) {
            $this->assertEquals(
                self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                $item->getPriceToPay()
            );
        }
    }

    public function testCanRecalculateAnExampleOrderWithTwoItemsAndExpenseOnItems()
    {
        $order = $this->locator->sales()->transferOrder();
        $item = $this->locator->sales()->transferOrderItem();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $discount = $this->getLocator()->calculation()->transferDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $discount = $this->getLocator()->calculation()->transferDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $expense = $this->getLocator()->calculation()->transferExpense();
        $expense->setName('Shipping Costs')
            ->setType(ExpenseConstants::EXPENSE_SHIPPING)
            ->setPriceToPay(self::ORDER_SHIPPING_COSTS/2)
            ->setGrossPrice(self::ORDER_SHIPPING_COSTS/2);

        $item->addExpense($expense);

        $order->addItem($item);
        $order->addItem(clone $item);

        $calculator = new StackExecutor($this->locator);
        $calculatorStack = $this->createCalculatorStack();
        $order = $calculator->recalculate($calculatorStack, $order);
        $calculator->recalculateTotals($calculatorStack, $order, null);

        $this->assertEquals(2 * self::ITEM_GROSS_PRICE + self::ORDER_SHIPPING_COSTS, $order->getTotals()->getSubtotal());
        $this->assertEquals(self::ORDER_SHIPPING_COSTS + 2 * (self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT), $order->getTotals()->getGrandTotalWithDiscounts());

        foreach ($order->getItems() as $item) {
            $this->assertEquals(self::ORDER_SHIPPING_COSTS / 2 + self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT, $item->getPriceToPay());
        }
    }

    /**
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return $this->locator;
    }
}
