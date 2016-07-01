<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Refund\Business\Model\RefundSaver;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

/**
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group Business
 * @group RefundSaver
 */
class RefundSaverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testSaveRefundShouldReturnTrueIfRefundSaved()
    {
        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $refundEntity = $this->getRefundEntity(1);

        $refundSaver = $this->getRefundSaver($refundEntity, $salesQueryContainerMock);
        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(100);

        $this->assertTrue($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldReturnFalseIfRefundNotSaved()
    {
        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaver($refundEntity, $salesQueryContainerMock);
        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(100);

        $this->assertFalse($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldSetCancelledAmountOnEntity()
    {
        $salesOrderItemEntityMock = $this->getSalesOrderItemEntityMock();

        $salesOrderItemQueryMock = $this->getMock(SpySalesOrderItemQuery::class, ['findOneByIdSalesOrderItem']);
        $salesOrderItemQueryMock->method('findOneByIdSalesOrderItem')->willReturn($salesOrderItemEntityMock);

        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesQueryContainerMock->method('querySalesOrderItem')->willReturn($salesOrderItemQueryMock);

        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaver($refundEntity, $salesQueryContainerMock);

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(100);

        $itemTransfer = new ItemTransfer();
        $refundTransfer->addItem($itemTransfer);

        $this->assertFalse($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Refund\Persistence\SpyRefund $refundEntity
     * @param \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainerMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Refund\Business\Model\RefundSaverInterface
     */
    protected function getRefundSaver($refundEntity, $salesQueryContainerMock)
    {
        $refundSaverMock = $this->getMock(RefundSaver::class, ['buildRefundEntity', 'findOneByIdSalesOrderItem'], [$salesQueryContainerMock]);
        $refundSaverMock->method('buildRefundEntity')->willReturn($refundEntity);

        return $refundSaverMock;
    }

    /**
     * @param int $affectedColumns
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Refund\Persistence\SpyRefund
     */
    protected function getRefundEntity($affectedColumns)
    {
        $refundEntityMock = $this->getMock(SpyRefund::class);
        $refundEntityMock->method('save')->willReturn($affectedColumns);

        return $refundEntityMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainerMock()
    {
        $salesQueryContainerMock = $this->getMock(SalesQueryContainerInterface::class);

        return $salesQueryContainerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function getSalesOrderItemEntityMock()
    {
        $salesOrderItemEntityMock = $this->getMock(SpySalesOrderItem::class, ['save', 'setCanceledAmount'], [], '', false);
        $salesOrderItemEntityMock->method('save')->willReturn(1);
        $salesOrderItemEntityMock->expects($this->once())->method('setCanceledAmount');

        return $salesOrderItemEntityMock;
    }

}
