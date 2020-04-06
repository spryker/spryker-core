<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Business
 * @group SalesReturnFacade
 * @group SetOrderItemRemunerationAmountTest
 * Add your own group annotations below this line
 */
class SetOrderItemRemunerationAmountTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_SALES_ORDER_ITEM_ID = 6666;

    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testSetOrderItemRemunerationAmountCopyRefundableAmountToRemunerationAmount(): void
    {
        // Arrange
        $orderTransfer = $this->tester->haveOrder([ItemTransfer::REFUNDABLE_AMOUNT => 228], static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getOrderItems()->getIterator()->current();

        // Act
        $this->tester->getFacade()->setOrderItemRemunerationAmount($itemTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $this->getSalesFacade()
            ->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->getItems()
            ->getIterator()
            ->current();

        $this->assertNotEmpty($itemTransfer->getRemunerationAmount());
        $this->assertSame($itemTransfer->getRefundableAmount(), $itemTransfer->getRemunerationAmount());
    }

    /**
     * @return void
     */
    public function testSetOrderItemRemunerationAmountThrowsExceptionWithEmptySalesOrderItemId(): void
    {
        // Arrange
        $itemTransfer = new ItemTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->setOrderItemRemunerationAmount($itemTransfer);
    }

    /**
     * @return void
     */
    public function testSetOrderItemRemunerationAmountWithNotWrongSalesOrderItemId(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setIdSalesOrderItem(static::FAKE_SALES_ORDER_ITEM_ID);

        // Act
        $this->tester->getFacade()->setOrderItemRemunerationAmount($itemTransfer);

        // Assert
    }

    /**
     * @return void
     */
    public function testSetOrderItemRemunerationAmountWithNullableRefundableAmount(): void
    {
        // Arrange
        $orderTransfer = $this->tester->haveOrder([ItemTransfer::REFUNDABLE_AMOUNT => null], static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getOrderItems()->getIterator()->current();

        // Act
        $this->tester->getFacade()->setOrderItemRemunerationAmount($itemTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $this->getSalesFacade()
            ->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->getItems()
            ->getIterator()
            ->current();

        $this->assertNull($itemTransfer->getRemunerationAmount());
        $this->assertSame($itemTransfer->getRefundableAmount(), $itemTransfer->getRemunerationAmount());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacade(): SalesFacadeInterface
    {
        return $this->tester->getLocator()->sales()->facade();
    }
}
