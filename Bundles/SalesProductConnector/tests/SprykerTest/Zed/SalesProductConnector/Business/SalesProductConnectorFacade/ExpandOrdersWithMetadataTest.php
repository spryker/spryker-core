<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector\Business\SalesProductConnectorFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConnector
 * @group Business
 * @group SalesProductConnectorFacade
 * @group ExpandOrdersWithMetadataTest
 * Add your own group annotations below this line
 */
class ExpandOrdersWithMetadataTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

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
    public function testExpandOrdersWithMetadataWillExpandItems(): void
    {
        // Arrange
        $orderTransfers = [
            $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME),
            $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME),
        ];

        // Act
        $expandedOrderTransfers = $this->tester->getFacade()->expandOrdersWithMetadata($orderTransfers);

        // Assert
        $this->assertCount(count($orderTransfers), $expandedOrderTransfers);

        foreach ($expandedOrderTransfers as $index => $orderTransfer) {
            $this->assertSame(
                $orderTransfer->getItems()->count(),
                $orderTransfers[$index]->getItems()->count()
            );

            $this->assertOrderItemsMetadata($orderTransfer->getItems());
        }
    }

    /**
     * @return void
     */
    public function testExpandOrdersWithMetadataWillThrowExceptionOnMissingIdSalesOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer->setIdSalesOrder(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->expandOrdersWithMetadata([$orderTransfer]);
    }

    /**
     * @return void
     */
    public function testExpandOrdersWithMetadataWillThrowExceptionOnMissingFkSalesOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer->getItems()->getIterator()->current()->setFkSalesOrder(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->expandOrdersWithMetadata([$orderTransfer]);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    protected function assertOrderItemsMetadata(ArrayObject $itemTransfers): void
    {
        foreach ($itemTransfers as $itemTransfer) {
            $this->assertNotNull($itemTransfer->getMetadata());
        }
    }
}
