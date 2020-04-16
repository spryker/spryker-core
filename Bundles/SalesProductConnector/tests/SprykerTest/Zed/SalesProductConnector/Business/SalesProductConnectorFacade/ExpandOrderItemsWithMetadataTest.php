<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector\Business\SalesProductConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConnector
 * @group Business
 * @group SalesProductConnectorFacade
 * @group ExpandOrderItemsWithMetadataTest
 * Add your own group annotations below this line
 */
class ExpandOrderItemsWithMetadataTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    protected const FAKE_ID_SALES_ORDER_ITEM = 6666;

    /**
     * @var \SprykerTest\Zed\SalesProductConnector\SalesProductConnectorBusinessTester
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
    public function testExpandOrderItemsWithMetadata(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithMetadata($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertNotNull($itemTransfers[0]->getMetadata());
        $this->assertNotNull($itemTransfers[0]->getMetadata()->getImage());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithMetadataWithoutIdSalesOrderItem(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithMetadata([
                new ItemTransfer(),
                new ItemTransfer(),
                new ItemTransfer(),
            ]);

        // Assert
        $this->assertNull($itemTransfers[0]->getMetadata());
        $this->assertNull($itemTransfers[1]->getMetadata());
        $this->assertNull($itemTransfers[2]->getMetadata());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithMetadataWithFakeIdSalesOrderItem(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithMetadata([
                (new ItemTransfer())->setIdSalesOrderItem(static::FAKE_ID_SALES_ORDER_ITEM),
            ]);

        // Assert
        $this->assertNull($itemTransfers[0]->getMetadata());
    }
}
