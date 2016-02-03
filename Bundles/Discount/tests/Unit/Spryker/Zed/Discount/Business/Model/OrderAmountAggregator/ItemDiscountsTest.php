<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Discount\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesDiscountQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Discount\Business\Model\OrderAmountAggregator\ItemDiscounts;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class ItemDiscountsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testItemDiscountsShouldBeHydratedFromEntities()
    {
        $itemsDiscountsAggregator = $this->createItemDiscountsAggregator();
        $orderTransfer = $this->createOrderTransfer();

        $itemsDiscountsAggregator->aggregate($orderTransfer);

        $itemCalculatedDiscounts = $orderTransfer->getItems()[0]->getCalculatedDiscounts();
        $this->assertEquals(100, $itemCalculatedDiscounts[0]->getSumGrossAmount());

    }

    /**
     * @return void
     */
    public function testExpenseDiscountShouldBeHydratedFromEntities()
    {
        $itemsDiscountsAggregator = $this->createItemDiscountsAggregator();
        $orderTransfer = $this->createOrderTransfer();

        $itemsDiscountsAggregator->aggregate($orderTransfer);

        $expenseCalculatedDiscounts = $orderTransfer->getExpenses()[0]->getCalculatedDiscounts();
        $this->assertEquals(200, $expenseCalculatedDiscounts[0]->getSumGrossAmount());
    }
    
    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $orderTransfer->setIdSalesOrder(1);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(1);
        $itemTransfer->setIdSalesOrderItem(1);
        $orderTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setIdSalesExpense(1);

        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Model\OrderAmountAggregator\ItemDiscounts
     */
    protected function createItemDiscountsAggregator()
    {
        $discountQueryContainer = $this->createDiscountQueryContainer();
        $discountQueryMock  = $this->createDiscountQueryMock();

        $objectColletion = new ObjectCollection();

        $salesDiscountEntity = new SpySalesDiscount();
        $salesDiscountEntity->setDisplayName('test');
        $salesDiscountEntity->setFkSalesOrderItem(1);
        $salesDiscountEntity->setAmount(100);
        $objectColletion->append($salesDiscountEntity);

        $salesDiscountEntity = new SpySalesDiscount();
        $salesDiscountEntity->setDisplayName('test');
        $salesDiscountEntity->setFkSalesExpense(1);
        $salesDiscountEntity->setAmount(200);
        $objectColletion->append($salesDiscountEntity);

        $discountQueryMock->expects($this->once())
            ->method('findByFkSalesOrder')
            ->with($this->isType('integer'))
            ->willReturn($objectColletion);

        $discountQueryContainer->expects($this->once())
            ->method('querySalesDisount')
            ->willReturn($discountQueryMock);

        return new ItemDiscounts($discountQueryContainer);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Sales\Persistence\SpySalesDiscountQuery
     */
    protected function createDiscountQueryMock()
    {
        return $this->getMockBuilder(SpySalesDiscountQuery::class)
            ->setMethods(['findByFkSalesOrder'])
            ->disableArgumentCloning()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function createDiscountQueryContainer()
    {
        return $this->getMockBuilder(DiscountQueryContainerInterface::class)
            ->disableArgumentCloning()
            ->getMock();
    }
}
