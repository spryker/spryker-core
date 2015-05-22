<?php

namespace Functional\SprykerFeature\Zed\DiscountCalculationConnector\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Sales\Code\ExpenseConstants;
use SprykerFeature\Zed\Calculation\Business\Model\StackExecutor;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator;

/**
 * @group SprykerFeature
 * @group Zed
 * @group DiscountCalculationConnector
 * @group Business
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
        $calculator = $this->getMock('\SprykerFeature\Zed\Calculation\Business\Model\StackExecutor', []);

        return $calculator;
    }

    protected function createCalculatorStack()
    {
        $stack = [
            new Calculator\ExpenseTotalsCalculator(),
            new Calculator\SubtotalTotalsCalculator(),
            new GrandTotalTotalsCalculator(
                new Calculator\SubtotalTotalsCalculator(),
                new Calculator\ExpenseTotalsCalculator()
            ),
            new Calculator\ExpensePriceToPayCalculator(),
            new Calculator\ItemPriceToPayCalculator(),
            new DiscountTotalsCalculator(),
            $this->locator->discountCalculationConnector()->pluginGrandTotalWithDiscountsTotalsCalculatorPlugin()
        ];

        return $stack;
    }

    public function testCanRecalculateAnEmptyOrder()
    {
        $order = new OrderTransfer();
        $order->setTotals(new TotalsTransfer());
        $order->setItems(new OrderItemsTransfer());

        $calculator = new StackExecutor();//$this->getCalculatorModel();
        $calculatorStack = $this->createCalculatorStack();
        $calculator->recalculate($calculatorStack, $order);
        $this->assertEmpty($order->getTotals()->getGrandTotalWithDiscounts());
    }

    public function testCanRecalculateAnExampleOrderWithOneItemAndExpenseOnOrder()
    {
        $order = new OrderTransfer();
        $order->setTotals(new TotalsTransfer());

        $items = new OrderItemsTransfer();
        $item =  new OrderItemTransfer();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $discounts = new \ArrayObject();
        $discount = new DiscountTransfer();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $discounts->append($discount);
        $discount = new DiscountTransfer();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $discounts->append($discount);

        $expense = new ExpenseTransfer();
        $expense->setName('Shipping Costs')
            ->setType(ExpenseConstants::EXPENSE_SHIPPING)
            ->setPriceToPay(self::ORDER_SHIPPING_COSTS)
            ->setGrossPrice(self::ORDER_SHIPPING_COSTS);

        $expensesCollection = new \ArrayObject();
        $expensesCollection->append($expense);
        $order->setExpenses($expensesCollection);

        $item->setDiscounts($discounts);
        $items->append($item);
        $order->setItems($items);

        $calculator = new StackExecutor();

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
        $order = new OrderTransfer();
        $order->setTotals(new TotalsTransfer());

        $items = new \ArrayObject();
        $item = new OrderItemTransfer();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $discount = new DiscountTransfer();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $discount = new DiscountTransfer();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $expense = new ExpenseTransfer();
        $expense->setName('Shipping Costs')
            ->setType(ExpenseConstants::EXPENSE_SHIPPING)
            ->setPriceToPay(self::ORDER_SHIPPING_COSTS/2)
            ->setGrossPrice(self::ORDER_SHIPPING_COSTS/2);

        $item->addExpense($expense);
        $items->append($item);
        $items->append(clone $item);
        $order->setItems($items);

        //$order->addItem($item);
        //$order->addItem(clone $item);

        $calculator = new StackExecutor();
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
