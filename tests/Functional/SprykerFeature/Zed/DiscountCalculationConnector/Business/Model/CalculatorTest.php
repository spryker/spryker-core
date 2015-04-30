<?php

namespace Functional\SprykerFeature\Zed\DiscountCalculationConnector\Business\Model;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Customer\Transfer\OrderItemCollection;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Sales\Code\ExpenseConstants;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Shared\Calculation\Transfer\DiscountCollection;
use SprykerFeature\Shared\Calculation\Transfer\Expense;
use SprykerFeature\Shared\Calculation\Transfer\ExpenseCollection;
use SprykerFeature\Zed\Calculation\Business\Model\StackExecutor;
use SprykerFeature\Zed\Calculation\Communication\Plugin\ExpensePriceToPayCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\ExpenseTotalsCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\GrandTotalTotalsCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\ItemPriceToPayCalculatorPlugin;
use SprykerFeature\Zed\Calculation\Communication\Plugin\SubtotalTotalsCalculatorPlugin;
use SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin\DiscountCalculatorPlugin;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Communication\Factory;

/**
 * Class CalculatorTest
 * @group CalculatorTest
 * @group Calculation
 * @package PhpUnit\SprykerFeature\Zed\Calculation\Business\Model
 */
class CalculatorTest extends Test
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
            new SubtotalTotalsCalculatorPlugin(new Factory('Calculation'), $this->getLocator()),
            new DiscountCalculatorPlugin(new Factory('DiscountCalculationConnector'), $this->getLocator()),
            new ExpenseTotalsCalculatorPlugin(new Factory('Calculation'), $this->getLocator()),
            new GrandTotalTotalsCalculatorPlugin(new Factory('Calculation'), $this->getLocator()),
            new ExpensePriceToPayCalculatorPlugin(new Factory('Calculation'), $this->getLocator()),
            new ItemPriceToPayCalculatorPlugin(new Factory('Calculation'), $this->getLocator()),
            $this->locator->discountCalculationConnector()->pluginGrandTotalWithDiscountsTotalsCalculatorPlugin()
        ];

        return $stack;
    }

    public function testCanRecalculateAnEmptyOrder()
    {
        $order = $this->getOrderWithFixtureData();
        $calculator = $this->getCalculator();
        $calculatorStack = $this->createCalculatorStack();
        $calculator->recalculate($calculatorStack, $order);
        $this->assertEmpty($order->getTotals()->getGrandTotalWithDiscounts());
    }

    public function testCanRecalculateAnExampleOrderWithOneItemAndExpenseOnOrder()
    {
        $order = $this->getOrderWithFixtureData();
        $items = $this->getItemCollection();
        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $discounts = $this->getPriceDiscountCollection();
        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $discounts->add($discount);
        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_DISCOUNT_AMOUNT);
        $discounts->add($discount);

        $expense = $this->getExpenseWithFixtureData();
        $expense->setName(self::EXPENSE_NAME_SHIPPING_COSTS)
            ->setType(ExpenseConstants::EXPENSE_SHIPPING)
            ->setPriceToPay(self::ORDER_SHIPPING_COSTS)
            ->setGrossPrice(self::ORDER_SHIPPING_COSTS);

        $expensesCollection = $this->getExpenseCollection();
        $expensesCollection->add($expense);
        $order->setExpenses($expensesCollection);

        $item->setDiscounts($discounts);
        $items->add($item);
        $order->setItems($items);

        $calculator = $this->getCalculator();

        $calculatorStack = $this->createCalculatorStack();
        $calculator->recalculate($calculatorStack, $order);
        $calculator->recalculateTotals($calculatorStack, $order);
        $expected = self::ORDER_SHIPPING_COSTS
            + self::ITEM_GROSS_PRICE
            - self::ITEM_COUPON_DISCOUNT_AMOUNT
            - self::ITEM_DISCOUNT_AMOUNT;

        $this->assertEquals($expected, $order->getTotals()->getGrandTotalWithDiscounts());

        foreach ($order->getItems() as $item) {
            $this->assertEquals(
                self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_DISCOUNT_AMOUNT,
                $item->getPriceToPay()
            );
        }
    }

    public function testCanRecalculateAnExampleOrderWithTwoItemsAndExpenseOnItems()
    {
        $order = $this->getOrderWithFixtureData();
        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $expense = $this->getExpenseWithFixtureData();
        $expense->setName(self::EXPENSE_NAME_SHIPPING_COSTS)
            ->setType(ExpenseConstants::EXPENSE_SHIPPING)
            ->setPriceToPay(self::ORDER_SHIPPING_COSTS/2)
            ->setGrossPrice(self::ORDER_SHIPPING_COSTS/2);

        $item->addExpense($expense);

        $order->addItem($item);
        $order->addItem(clone $item);

        $calculator = $this->getCalculator();
        $calculatorStack = $this->createCalculatorStack();
        $order = $calculator->recalculate($calculatorStack, $order);
        $calculator->recalculateTotals($calculatorStack, $order);

        $this->assertEquals(
            2 * self::ITEM_GROSS_PRICE + self::ORDER_SHIPPING_COSTS,
            $order->getTotals()->getSubtotal()
        );

        $this->assertEquals(
            self::ORDER_SHIPPING_COSTS + 2
            * (self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_DISCOUNT_AMOUNT),
            $order->getTotals()->getGrandTotalWithDiscounts()
        );

        foreach ($order->getItems() as $item) {
            $this->assertEquals(
                self::ORDER_SHIPPING_COSTS / 2 + self::ITEM_GROSS_PRICE
                - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_DISCOUNT_AMOUNT,
                $item->getPriceToPay()
            );
        }
    }

    /**
     * @return StackExecutor
     */
    protected function getCalculator()
    {
        return new StackExecutor(Locator::getInstance());
    }

    /**
     * @return OrderItemCollection
     */
    protected function getItemCollection()
    {
        return $this->getLocator()->sales()->transferOrderItemCollection();
    }

    /**
     * @return DiscountCollection
     */
    protected function getPriceDiscountCollection()
    {
        return $this->getLocator()->calculation()->transferDiscountCollection();
    }

    /**
     * @return \SprykerFeature\Shared\Calculation\Transfer\ExpenseCollection
     */
    protected function getExpenseCollection()
    {
        /* @var ExpenseCollection $expenseCollection */
        return $this->getLocator()->calculation()->transferExpenseCollection();
    }

    /**
     * @return \SprykerFeature\Shared\Calculation\Transfer\Discount
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
        /* @var Order $order */
        $order = new \Generated\Shared\Transfer\SalesOrderTransfer();
        $order->fillWithFixtureData();

        return $order;
    }

    /**
     * @return OrderItem
     */
    protected function getItemWithFixtureData()
    {
        /* @var OrderItem $item */
        $item = new \Generated\Shared\Transfer\SalesOrderItemTransfer();
        $item->fillWithFixtureData();

        return $item;
    }

    /**
     * @return \SprykerFeature\Shared\Calculation\Transfer\Expense
     */
    protected function getExpenseWithFixtureData()
    {
        /* @var Expense $expense */
        $expense = new \Generated\Shared\Transfer\CalculationExpenseTransfer();
        $expense->fillWithFixtureData();

        return $expense;
    }

    /**
     * @return AbstractLocatorLocator|AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }
}
