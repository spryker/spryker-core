<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\ExpenseTotal;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;

class ExpenseTotalTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testExpenseShouldSetToOrderTransferWithHydratedEntityData()
    {
        $expenseTotalAggregator = $this->createExpenseTotalAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $expenseTotalAggregator->aggregate($orderTransfer);

        $this->assertEquals(100, $orderTransfer->getExpenses()[0]->getSumGrossPrice());
    }

    /**
     * @return void
     */
    public function testExpenseAggregatorShouldSumTotalIntoExpensesTransfer()
    {
        $expenseTotalAggregator = $this->createExpenseTotalAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $expenseTotalAggregator->aggregate($orderTransfer);

        $this->assertEquals(100, $orderTransfer->getTotals()->getExpenseTotal());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\ExpenseTotal
     */
    public function createExpenseTotalAggregator()
    {
        $salesQueryContainerMock = $this->createSalesQueryContainerMock();
        $salesExpenseQueryMock = $this->createSalesExpenseQuery();

        $salesExpenseEntity = new SpySalesExpense();
        $salesExpenseEntity->setGrossPrice(100);

        $objectColletion = new ObjectCollection();
        $objectColletion->append($salesExpenseEntity);

        $salesExpenseQueryMock->expects($this->once())
            ->method('findByFkSalesOrder')
            ->with($this->isType('integer'))
            ->willReturn($objectColletion);

        $salesQueryContainerMock->expects($this->once())
            ->method('querySalesExpense')
            ->willReturn($salesExpenseQueryMock);

        return new ExpenseTotal($salesQueryContainerMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function createSalesQueryContainerMock()
    {
        return $this->getMockBuilder(SalesQueryContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    protected function createSalesExpenseQuery()
    {
        return $this->getMockBuilder(SpySalesExpenseQuery::class)
            ->setMethods(['findByFkSalesOrder'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder(1);

        return $orderTransfer;
    }

}
