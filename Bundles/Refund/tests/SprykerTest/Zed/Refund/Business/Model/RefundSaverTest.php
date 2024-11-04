<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Refund\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\FlashMessagesTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Refund\Business\Model\RefundSaver;
use Spryker\Zed\Refund\Business\Model\RefundSaverInterface;
use Spryker\Zed\Refund\Dependency\Facade\RefundToCalculationInterface;
use Spryker\Zed\Refund\Dependency\Facade\RefundToMessengerFacadeInterface;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface;
use Spryker\Zed\Refund\RefundConfig;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Refund
 * @group Business
 * @group Model
 * @group RefundSaverTest
 * Add your own group annotations below this line
 */
class RefundSaverTest extends Unit
{
    /**
     * @var bool
     */
    protected $isCommitSuccessful = true;

    /**
     * @return void
     */
    public function testSaveRefundShouldReturnTrueIfRefundSaved(): void
    {
        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesFacadeMock = $this->getSalesFacadeMock();
        $calculationFacadeMock = $this->getCalculationFacadeMock();
        $refundConfigMock = $this->getRefundConfigMock();
        $messengerFacadeMock = $this->getMessengerFacadeMock();
        $refundEntity = $this->getRefundEntity(1);

        $refundSaver = $this->getRefundSaverMock(
            $refundEntity,
            $salesQueryContainerMock,
            $salesFacadeMock,
            $calculationFacadeMock,
            $refundConfigMock,
            $messengerFacadeMock,
        );
        $refundTransfer = new RefundTransfer();

        $this->assertTrue($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldReturnFalseIfRefundNotSaved(): void
    {
        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesFacadeMock = $this->getSalesFacadeMock();
        $calculationFacadeMock = $this->getCalculationFacadeMock();
        $refundConfigMock = $this->getRefundConfigMock();
        $messengerFacadeMock = $this->getMessengerFacadeMock();
        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaverMock(
            $refundEntity,
            $salesQueryContainerMock,
            $salesFacadeMock,
            $calculationFacadeMock,
            $refundConfigMock,
            $messengerFacadeMock,
        );
        $refundTransfer = new RefundTransfer();

        $this->isCommitSuccessful = false;

        $this->assertFalse($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldSetCanceledAmountOnOrderItemEntities(): void
    {
        $salesOrderItemEntityMock = $this->getSalesOrderItemEntityMock();

        $salesFacadeMock = $this->getSalesFacadeMock();
        $calculationFacadeMock = $this->getCalculationFacadeMock();

        $salesOrderItemQueryMock = $this->getMockBuilder(SpySalesOrderItemQuery::class)->addMethods(['findOneByIdSalesOrderItem'])->getMock();
        $salesOrderItemQueryMock->method('findOneByIdSalesOrderItem')->willReturn($salesOrderItemEntityMock);

        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesQueryContainerMock->method('querySalesOrderItem')->willReturn($salesOrderItemQueryMock);

        $refundConfigMock = $this->getRefundConfigMock();
        $messengerFacadeMock = $this->getMessengerFacadeMock();
        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaverMock(
            $refundEntity,
            $salesQueryContainerMock,
            $salesFacadeMock,
            $calculationFacadeMock,
            $refundConfigMock,
            $messengerFacadeMock,
        );

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(100);

        $itemTransfer = new ItemTransfer();
        $refundTransfer->addItem($itemTransfer);

        $this->assertTrue($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldSetCanceledAmountOnOrderExpenseEntities(): void
    {
        $salesExpenseEntityMock = $this->getSalesExpenseEntityMock();

        $salesFacadeMock = $this->getSalesFacadeMock();
        $calculationFacadeMock = $this->getCalculationFacadeMock();

        $salesExpenseQueryMock = $this->getMockBuilder(SpySalesExpenseQuery::class)->addMethods(['findOneByIdSalesExpense'])->getMock();
        $salesExpenseQueryMock->method('findOneByIdSalesExpense')->willReturn($salesExpenseEntityMock);

        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesQueryContainerMock->method('querySalesExpense')->willReturn($salesExpenseQueryMock);

        $refundConfigMock = $this->getRefundConfigMock();
        $messengerFacadeMock = $this->getMessengerFacadeMock();
        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaverMock(
            $refundEntity,
            $salesQueryContainerMock,
            $salesFacadeMock,
            $calculationFacadeMock,
            $refundConfigMock,
            $messengerFacadeMock,
        );

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
    public function testSaveRefundShouldBuildRefundEntity(): void
    {
        $salesFacadeMock = $this->getSalesFacadeMock();
        $calculationFacadeMock = $this->getCalculationFacadeMock();
        $refundConfigMock = $this->getRefundConfigMock();
        $messengerFacadeMock = $this->getMessengerFacadeMock();

        $refundSaverMock = $this->getRefundSaverMock(
            null,
            $this->getSalesQueryContainerMock(),
            $salesFacadeMock,
            $calculationFacadeMock,
            $refundConfigMock,
            $messengerFacadeMock,
        );

        $refundSaverMock->saveRefund(new RefundTransfer());
    }

    /**
     * @param \Orm\Zed\Refund\Persistence\SpyRefund|null $refundEntity
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainerMock
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface $salesFacadeMock
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToCalculationInterface $calculationFacadeMock
     * @param \Pyz\Zed\Refund\RefundConfig $refundConfigMock
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToMessengerFacadeInterface $messengerFacadeMock
     *
     * @return \Spryker\Zed\Refund\Business\Model\RefundSaverInterface
     */
    protected function getRefundSaverMock(
        ?SpyRefund $refundEntity,
        SalesQueryContainerInterface $salesQueryContainerMock,
        RefundToSalesInterface $salesFacadeMock,
        RefundToCalculationInterface $calculationFacadeMock,
        RefundConfig $refundConfigMock,
        RefundToMessengerFacadeInterface $messengerFacadeMock
    ): RefundSaverInterface {
        if ($refundEntity) {
            $refundSaverMock = $this->getMockBuilder(RefundSaver::class)
                ->onlyMethods(['buildRefundEntity'])
                ->setConstructorArgs([
                    $salesQueryContainerMock,
                    $salesFacadeMock,
                    $calculationFacadeMock,
                    $refundConfigMock,
                    $messengerFacadeMock,
                    [],
                ])->getMock();

            $refundSaverMock->expects($this->once())->method('buildRefundEntity')->willReturn($refundEntity);
        } else {
            $refundSaverMock = $this->getMockBuilder(RefundSaver::class)
                ->onlyMethods(['saveRefundEntity', 'updateOrderItems', 'updateExpenses'])
                ->setConstructorArgs([
                    $this->getSalesQueryContainerMock(),
                    $salesFacadeMock,
                    $calculationFacadeMock,
                    $refundConfigMock,
                    $messengerFacadeMock,
                    [],
                ])->getMock();

            $refundSaverMock->expects($this->once())->method('saveRefundEntity');
        }

        return $refundSaverMock;
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldCleanupRecalculationMessages(): void
    {
        // Assert
        $messengerFacadeMock = $this->getMessengerFacadeMock();
        $messengerFacadeMock->expects($this->once())->method('getStoredMessages');

        // Arrange
        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesFacadeMock = $this->getSalesFacadeMock();
        $calculationFacadeMock = $this->getCalculationFacadeMock();
        $refundConfigMock = $this->getRefundConfigMock(true);
        $refundEntity = $this->getRefundEntity(1);

        $refundSaver = $this->getRefundSaverMock(
            $refundEntity,
            $salesQueryContainerMock,
            $salesFacadeMock,
            $calculationFacadeMock,
            $refundConfigMock,
            $messengerFacadeMock,
        );

        // Act
        $refundSaver->saveRefund(new RefundTransfer());
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldNotCleanupRecalculationMessages(): void
    {
        // Assert
        $messengerFacadeMock = $this->getMessengerFacadeMock();
        $messengerFacadeMock->expects($this->never())->method('getStoredMessages');

        // Arrange
        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesFacadeMock = $this->getSalesFacadeMock();
        $calculationFacadeMock = $this->getCalculationFacadeMock();
        $refundConfigMock = $this->getRefundConfigMock();
        $refundEntity = $this->getRefundEntity(1);

        $refundSaver = $this->getRefundSaverMock(
            $refundEntity,
            $salesQueryContainerMock,
            $salesFacadeMock,
            $calculationFacadeMock,
            $refundConfigMock,
            $messengerFacadeMock,
        );

        // Act
        $refundSaver->saveRefund(new RefundTransfer());
    }

    /**
     * @param int $affectedColumns
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Refund\Persistence\SpyRefund
     */
    protected function getRefundEntity(int $affectedColumns): SpyRefund
    {
        $refundEntityMock = $this->getMockBuilder(SpyRefund::class)->getMock();
        $refundEntityMock->method('save')->willReturn($affectedColumns);

        return $refundEntityMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainerMock(): SalesQueryContainerInterface
    {
        $salesQueryContainerMock = $this->getMockBuilder(SalesQueryContainerInterface::class)->getMock();
        $salesQueryContainerMock->method('getConnection')->willReturn($this->getPropelConnectionMock());

        return $salesQueryContainerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface
     */
    protected function getSalesFacadeMock(): RefundToSalesInterface
    {
        $salesFacadeMock = $this->getMockBuilder(RefundToSalesInterface::class)->getMock();
        $salesFacadeMock->method('getOrderByIdSalesOrder')->willReturn(new OrderTransfer());

        return $salesFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Refund\Dependency\Facade\RefundToCalculationInterface
     */
    protected function getCalculationFacadeMock(): RefundToCalculationInterface
    {
        $calculationFacadeMock = $this->getMockBuilder(RefundToCalculationInterface::class)->getMock();
        $calculationFacadeMock->method('recalculateOrder')->willReturn(new OrderTransfer());

        return $calculationFacadeMock;
    }

    /**
     * @param bool $shouldCleanupRecalculationMessagesAfterRefund
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Refund\RefundConfig
     */
    protected function getRefundConfigMock(bool $shouldCleanupRecalculationMessagesAfterRefund = false): RefundConfig
    {
        $refundConfigMock = $this->getMockBuilder(RefundConfig::class)->getMock();
        $refundConfigMock
            ->method('shouldCleanupRecalculationMessagesAfterRefund')
            ->willReturn($shouldCleanupRecalculationMessagesAfterRefund);

        return $refundConfigMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Refund\Dependency\Facade\RefundToMessengerFacadeInterface
     */
    protected function getMessengerFacadeMock(): RefundToMessengerFacadeInterface
    {
        $messengerFacadeMock = $this->getMockBuilder(RefundToMessengerFacadeInterface::class)->getMock();
        $messengerFacadeMock
            ->method('getStoredMessages')
            ->willReturn(new FlashMessagesTransfer());

        return $messengerFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function getSalesOrderItemEntityMock(): SpySalesOrderItem
    {
        $salesOrderItemEntityMock = $this->getMockBuilder(SpySalesOrderItem::class)->onlyMethods(['save', 'setCanceledAmount'])->disableOriginalConstructor()->getMock();
        $salesOrderItemEntityMock->method('save')->willReturn(1);
        $salesOrderItemEntityMock->expects($this->once())->method('setCanceledAmount');

        return $salesOrderItemEntityMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    protected function getSalesExpenseEntityMock(): SpySalesExpense
    {
        $salesExpenseEntityMock = $this->getMockBuilder(SpySalesExpense::class)->onlyMethods(['save', 'setCanceledAmount'])->disableOriginalConstructor()->getMock();
        $salesExpenseEntityMock->method('save')->willReturn(1);
        $salesExpenseEntityMock->expects($this->once())->method('setCanceledAmount');

        return $salesExpenseEntityMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getPropelConnectionMock(): ConnectionInterface
    {
        $propelConnectionMock = $this->getMockBuilder(ConnectionInterface::class)->getMock();
        $propelConnectionMock->method('commit')->willReturnCallback([$this, 'commit']);

        return $propelConnectionMock;
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->isCommitSuccessful;
    }
}
