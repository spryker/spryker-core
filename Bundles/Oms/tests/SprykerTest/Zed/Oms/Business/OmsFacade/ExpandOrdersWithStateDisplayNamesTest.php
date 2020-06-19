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
 * @group ExpandOrdersWithStateDisplayNamesTest
 * Add your own group annotations below this line
 */
class ExpandOrdersWithStateDisplayNamesTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_SALES_ORDER_ITEM_ID = 666;

    protected const SHIPPED_STATE_NAME = 'shipped';
    protected const DELIVERED_STATE_NAME = 'delivered';
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
    public function testExpandOrdersWithItemStateDisplayNamesShouldExpandOrdersWithStateDisplayNames(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();
        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        // Act
        $expandedOrderTransfers = $this->tester
            ->getFacade()
            ->expandOrdersWithItemStateDisplayNames([$orderTransfer]);

        // Assert
        $this->assertContains(static::SHIPPED_STATE_DISPLAY_NAME, $expandedOrderTransfers[0]->getItemStateDisplayNames());
    }

    /**
     * @return void
     */
    public function testExpandOrdersWithItemStateDisplayNamesDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder())->build();
        $orderTransfer = (new OrderTransfer())->addItem($itemTransfer);

        // Act
        $expandedOrderTransfers = $this->tester
            ->getFacade()
            ->expandOrdersWithItemStateDisplayNames([$orderTransfer]);

        // Assert
        $this->assertEmpty($expandedOrderTransfers[0]->getItemStateDisplayNames());
    }
}
