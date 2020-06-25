<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OmsFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OmsFacade
 * @group ExpandOrdersWithAggregatedItemStatesTest
 * Add your own group annotations below this line
 */
class ExpandOrdersWithAggregatedItemStatesTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const SHIPPED_STATE_NAME = 'shipped';
    protected const SHIPPED_STATE_DISPLAY_NAME = 'in progress';

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
    public function testExpandOrdersWithAggregatedItemStatesExpandsOrders(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();
        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        // Act
        $expandedOrderTransfers = $this->tester
            ->getFacade()
            ->expandOrdersWithAggregatedItemStates([$orderTransfer]);

        // Assert
        $this->assertStringContainsStringIgnoringCase(static::SHIPPED_STATE_DISPLAY_NAME, $expandedOrderTransfers[0]->getAggregatedItemStates()[0]->getDisplayName());
    }

    /**
     * @return void
     */
    public function testExpandOrdersWithAggregatedItemStatesDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder())->build();
        $orderTransfer = (new OrderTransfer())->addItem($itemTransfer);

        // Act
        $expandedOrderTransfers = $this->tester
            ->getFacade()
            ->expandOrdersWithAggregatedItemStates([$orderTransfer]);

        // Assert
        $this->assertEmpty($expandedOrderTransfers[0]->getAggregatedItemStates());
    }
}
