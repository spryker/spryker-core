<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OmsFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OmsFacade
 * @group ExpandOrderItemsWithStateHistoryTest
 * Add your own group annotations below this line
 */
class ExpandOrderItemsWithStateHistoryTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_SALES_ORDER_ITEM_ID = 666;

    protected const SHIPPED_STATE_NAME = 'shipped';

    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
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
    public function testExpandOrderItemsWithStateHistoryExpandOrderItemsWithStateHistory(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithStateHistory($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertCount(2, $itemTransfers[0]->getStateHistory());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithStateHistoryCompareIdSalesOrderItemIds(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithStateHistory($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertSame($itemTransfer->getIdSalesOrderItem(), $itemTransfers[0]->getStateHistory()->offsetGet(0)->getIdSalesOrderItem());
        $this->assertSame($itemTransfer->getIdSalesOrderItem(), $itemTransfers[0]->getStateHistory()->offsetGet(1)->getIdSalesOrderItem());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithStateHistoryWithoutSalesOrderItemId(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithStateHistory([new ItemTransfer()]);

        // Assert
        $this->assertEmpty($itemTransfers[0]->getStateHistory());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithStateHistoryWithFakeSalesOrderItemId(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithStateHistory([(new ItemTransfer())->setIdSalesOrderItem(static::FAKE_SALES_ORDER_ITEM_ID)]);

        // Assert
        $this->assertEmpty($itemTransfers[0]->getStateHistory());
    }
}
