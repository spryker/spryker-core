<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business;

use ArrayObject;
use Codeception\Test\Unit;
use SprykerTest\Zed\Shipment\ShipmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group ExpandOrderItemsWithShipmentTest
 * Add your own group annotations below this line
 */
class ExpandOrderItemsWithShipmentTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected ShipmentBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandOrderItemsWithShipmentShouldReturnExpandedItems(): void
    {
        // Arrange
        $savedOrderTransfer = $this->tester
            ->createOrderWithMultiShipment(
                $this->tester->createQuoteTransfer(),
            );

        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $orderItemTransferCollection */
        $orderItemTransferCollection = $savedOrderTransfer->getOrderItems();
        $orderItemTransferCollection = $this->clearShipment($orderItemTransferCollection);

        // Act
        $expandedOrderItems = $this->tester->getFacade()
            ->expandOrderItemsWithShipment(
                $orderItemTransferCollection->getArrayCopy(),
            );

        // Assert
        $this->assertCount(count($orderItemTransferCollection), $expandedOrderItems);
        foreach ($expandedOrderItems as $orderItem) {
            $this->assertNotNull($orderItem->getShipment());
        }
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithShipmentShouldReturnSameItemsWhenShipmentMissed(): void
    {
        // Arrange
        $savedOrderTransfer = $this->tester
            ->createOrderWithoutShipment(
                $this->tester->createQuoteTransfer(),
            );

        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $orderItemTransferCollection */
        $orderItemTransferCollection = $savedOrderTransfer->getOrderItems();
        $orderItemTransferCollection = $this->clearShipment($orderItemTransferCollection);

        // Act
        $expandedOrderItems = $this->tester->getFacade()
            ->expandOrderItemsWithShipment(
                $orderItemTransferCollection->getArrayCopy(),
            );

        // Assert
        $this->assertCount(count($orderItemTransferCollection), $expandedOrderItems);
        foreach ($expandedOrderItems as $orderItem) {
            $this->assertNull($orderItem->getShipment());
        }
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithShipmentShouldReturnEmptyArrayWhenOrderItemsMissed(): void
    {
        // Act
        $expandedOrderItems = $this->tester->getFacade()
            ->expandOrderItemsWithShipment([]);

        // Assert
        $this->assertCount(0, $expandedOrderItems);
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $orderItemTransferCollection
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function clearShipment(ArrayObject $orderItemTransferCollection): ArrayObject
    {
        foreach ($orderItemTransferCollection as $orderItemTransfer) {
            $orderItemTransfer->setShipment(null);
        }

        return $orderItemTransferCollection;
    }
}
