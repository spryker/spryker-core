<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group ExpandOrderItemsWithOrderReferenceTest
 * Add your own group annotations below this line
 */
class ExpandOrderItemsWithOrderReferenceTest extends Test
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_ORDER_REFERENCE = 'FAKE_ORDER_REFERENCE';
    protected const FAKE_ID_SALES_ORDER_ITEM = 6666;

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
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
    public function testExpandOrderItemsWithOrderReferenceCopyOrderReferenceToItems(): void
    {
        // Arrange
        $firstSaveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $firstItemTransfer = $firstSaveOrderTransfer->getOrderItems()->getIterator()->current();

        $secondSaveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $secondItemTransfer = $secondSaveOrderTransfer->getOrderItems()->getIterator()->current();

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithOrderReference([$firstItemTransfer, $secondItemTransfer]);

        // Assert
        $this->assertSame($firstSaveOrderTransfer->getOrderReference(), $itemTransfers[0]->getOrderReference());
        $this->assertSame($secondSaveOrderTransfer->getOrderReference(), $itemTransfers[1]->getOrderReference());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithOrderReferenceWithDuplicatedItems(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $saveOrderTransfer->getOrderItems()->getIterator()->current();

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithOrderReference([$itemTransfer, $itemTransfer]);

        // Assert
        $this->assertSame($saveOrderTransfer->getOrderReference(), $itemTransfers[0]->getOrderReference());
        $this->assertSame($saveOrderTransfer->getOrderReference(), $itemTransfers[1]->getOrderReference());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithOrderReferenceWithFakeSalesOrderItem(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $saveOrderTransfer->getOrderItems()->getIterator()->current();

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithOrderReference([
                $itemTransfer,
                (new ItemTransfer())->setIdSalesOrderItem(static::FAKE_ID_SALES_ORDER_ITEM),
            ]);

        // Assert
        $this->assertSame($saveOrderTransfer->getOrderReference(), $itemTransfers[0]->getOrderReference());
        $this->assertNull($itemTransfers[1]->getOrderReference());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithOrderReferenceThrowsExceptionWithoutIdSalesOrderItem(): void
    {
        // Arrange
        $firstSaveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $firstItemTransfer = $firstSaveOrderTransfer->getOrderItems()->getIterator()->current();

        $secondSaveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $secondItemTransfer = $secondSaveOrderTransfer->getOrderItems()->getIterator()->current();
        $secondItemTransfer->setIdSalesOrderItem(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->expandOrderItemsWithOrderReference([$firstItemTransfer, $secondItemTransfer]);
    }
}
