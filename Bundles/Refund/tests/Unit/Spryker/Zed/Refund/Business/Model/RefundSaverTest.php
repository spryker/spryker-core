<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use PHPUnit_Framework_TestCase;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Refund\Business\Model\RefundSaver;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group Business
 * @group Model
 * @group RefundSaverTest
 */
class RefundSaverTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var bool
     */
    protected $isCommitSuccessful = true;

    /**
     * @return void
     */
    public function testSaveRefundShouldReturnTrueIfRefundSaved()
    {
        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $refundEntity = $this->getRefundEntity(1);

        $refundSaver = $this->getRefundSaverMock($refundEntity, $salesQueryContainerMock);
        $refundTransfer = new RefundTransfer();

        $this->assertTrue($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldReturnFalseIfRefundNotSaved()
    {
        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaverMock($refundEntity, $salesQueryContainerMock);
        $refundTransfer = new RefundTransfer();

        $this->isCommitSuccessful = false;

        $this->assertFalse($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldSetCanceledAmountOnOrderItemEntities()
    {
        $salesOrderItemEntityMock = $this->getSalesOrderItemEntityMock();

        $salesOrderItemQueryMock = $this->getMockBuilder(SpySalesOrderItemQuery::class)->setMethods(['findOneByIdSalesOrderItem'])->getMock();
        $salesOrderItemQueryMock->method('findOneByIdSalesOrderItem')->willReturn($salesOrderItemEntityMock);

        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesQueryContainerMock->method('querySalesOrderItem')->willReturn($salesOrderItemQueryMock);

        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaverMock($refundEntity, $salesQueryContainerMock);

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(100);

        $itemTransfer = new ItemTransfer();
        $refundTransfer->addItem($itemTransfer);

        $this->assertTrue($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldSetCanceledAmountOnOrderExpenseEntities()
    {
        $salesExpenseEntityMock = $this->getSalesExpenseEntityMock();

        $salesExpenseQueryMock = $this->getMockBuilder(SpySalesExpenseQuery::class)->setMethods(['findOneByIdSalesExpense'])->getMock();
        $salesExpenseQueryMock->method('findOneByIdSalesExpense')->willReturn($salesExpenseEntityMock);

        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesQueryContainerMock->method('querySalesExpense')->willReturn($salesExpenseQueryMock);

        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaverMock($refundEntity, $salesQueryContainerMock);

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(100);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setRefundableAmount(100);
        $refundTransfer->addExpense($expenseTransfer);

        $this->assertTrue($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldBuildRefundEntity()
    {
        $refundSaverMock = $this->getRefundSaverMock(null, $this->getSalesQueryContainerMock());

        $refundSaverMock->saveRefund(new RefundTransfer());
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Refund\Persistence\SpyRefund $refundEntity
     * @param \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainerMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Refund\Business\Model\RefundSaverInterface
     */
    protected function getRefundSaverMock($refundEntity, $salesQueryContainerMock)
    {
        if ($refundEntity) {
            $refundSaverMock = $this->getMockBuilder(RefundSaver::class)->setMethods(['buildRefundEntity'])->setConstructorArgs([$salesQueryContainerMock])->getMock();
            $refundSaverMock->expects($this->once())->method('buildRefundEntity')->willReturn($refundEntity);
        } else {
            $refundSaverMock = $this->getMockBuilder(RefundSaver::class)
                ->setMethods(['saveRefundEntity', 'updateOrderItems', 'updateExpenses'])
                ->setConstructorArgs([$this->getSalesQueryContainerMock()])
                ->getMock();
            $refundSaverMock->expects($this->once())->method('saveRefundEntity');
        }

        return $refundSaverMock;
    }

    /**
     * @param int $affectedColumns
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Refund\Persistence\SpyRefund
     */
    protected function getRefundEntity($affectedColumns)
    {
        $refundEntityMock = $this->getMockBuilder(SpyRefund::class)->getMock();
        $refundEntityMock->method('save')->willReturn($affectedColumns);

        return $refundEntityMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainerMock()
    {
        $salesQueryContainerMock = $this->getMockBuilder(SalesQueryContainerInterface::class)->getMock();
        $salesQueryContainerMock->method('getConnection')->willReturn($this->getPropelConnectionMock());

        return $salesQueryContainerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function getSalesOrderItemEntityMock()
    {
        $salesOrderItemEntityMock = $this->getMockBuilder(SpySalesOrderItem::class)->setMethods(['save', 'setCanceledAmount'])->disableOriginalConstructor()->getMock();
        $salesOrderItemEntityMock->method('save')->willReturn(1);
        $salesOrderItemEntityMock->expects($this->once())->method('setCanceledAmount');

        return $salesOrderItemEntityMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    protected function getSalesExpenseEntityMock()
    {
        $salesExpenseEntityMock = $this->getMockBuilder(SpySalesExpense::class)->setMethods(['save', 'setCanceledAmount'])->disableOriginalConstructor()->getMock();
        $salesExpenseEntityMock->method('save')->willReturn(1);
        $salesExpenseEntityMock->expects($this->once())->method('setCanceledAmount');

        return $salesExpenseEntityMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getPropelConnectionMock()
    {
        $propelConnectionMock = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $propelConnectionMock->method('commit')->willReturnCallback([$this, 'commit']);

        return $propelConnectionMock;
    }

    /**
     * @return bool
     */
    public function commit()
    {
        return $this->isCommitSuccessful;
    }

}
