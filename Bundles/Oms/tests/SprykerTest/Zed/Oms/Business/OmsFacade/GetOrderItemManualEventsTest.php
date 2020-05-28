<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OmsFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderItemFilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OmsFacade
 * @group GetOrderItemManualEventsTest
 * Add your own group annotations below this line
 */
class GetOrderItemManualEventsTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_SALES_ORDER_ITEM_ID = 666;

    protected const SHIPPED_STATE_NAME = 'shipped';
    protected const DELIVERED_STATE_NAME = 'delivered';

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
    public function testGetOrderItemManualEventsReturnsAvailableEvents(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemIds = $this->extractSalesOrderItemIds($orderTransfer->getItems());

        // Act
        $orderItemManualEvents = $this->tester->getFacade()->getOrderItemManualEvents(
            (new OrderItemFilterTransfer())->setSalesOrderItemIds($salesOrderItemIds)
        );

        // Assert
        $this->assertCount(count($salesOrderItemIds), $orderItemManualEvents);
    }

    /**
     * @return void
     */
    public function testGetOrderItemManualEventsReturnsEmptyArray(): void
    {
        // Arrange
        $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $orderItemManualEvents = $this->tester->getFacade()->getOrderItemManualEvents(
            (new OrderItemFilterTransfer())->setSalesOrderItemIds([-1])
        );

        // Assert
        $this->assertCount(0, $orderItemManualEvents);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIds(ArrayObject $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }
}
