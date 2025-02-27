<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Communication\Plugin\SalesOrderAmendment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Communication\Plugin\SalesOrderAmendment\ShipmentSalesOrderItemCollectorPlugin;
use SprykerTest\Zed\Shipment\ShipmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Communication
 * @group Plugin
 * @group SalesOrderAmendment
 * @group ShipmentSalesOrderItemCollectorPluginTest
 * Add your own group annotations below this line
 */
class ShipmentSalesOrderItemCollectorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentCommunicationTester
     */
    protected ShipmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testAddsItemWithUpdatedShipmentToItemsToUpdateAndRemovesFromItemsToSkip(): void
    {
        // Arrange
        $cartNoteSalesOrderItemCollectorPlugin = new ShipmentSalesOrderItemCollectorPlugin();
        $orderTransfer = (new OrderTransfer())->addItem(
            (new ItemTransfer())->setIdSalesOrderItem(1)->setShipment(
                (new ShipmentTransfer())->setIdSalesShipment(1),
            ),
        );
        $salesOrderAmendmentItemCollectionTransfer = (new SalesOrderAmendmentItemCollectionTransfer())
            ->addItemToSkip(
                (new ItemTransfer())->setIdSalesOrderItem(1)->setShipment(
                    (new ShipmentTransfer())->setIdSalesShipment(2),
                ),
            );

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = $cartNoteSalesOrderItemCollectorPlugin->collect(
            $orderTransfer,
            $salesOrderAmendmentItemCollectionTransfer,
        );

        // Assert
        $this->assertCount(0, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip());
        $this->assertCount(1, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate());
        $this->assertSame(
            $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate()->offsetGet(0)->getShipment()->getIdSalesShipment(),
            2,
        );
    }

    /**
     * @return void
     */
    public function testDoesNotAddItemWithSameShipmentToItemsToUpdateAndDoesNotRemoveFromItemsToSkip(): void
    {
        // Arrange
        $cartNoteSalesOrderItemCollectorPlugin = new ShipmentSalesOrderItemCollectorPlugin();
        $orderTransfer = (new OrderTransfer())->addItem(
            (new ItemTransfer())->setIdSalesOrderItem(1)->setShipment(
                (new ShipmentTransfer())->setIdSalesShipment(1),
            ),
        );
        $salesOrderAmendmentItemCollectionTransfer = (new SalesOrderAmendmentItemCollectionTransfer())
            ->addItemToSkip(
                (new ItemTransfer())->setIdSalesOrderItem(1)->setShipment(
                    (new ShipmentTransfer())->setIdSalesShipment(1),
                ),
            );

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = $cartNoteSalesOrderItemCollectorPlugin->collect(
            $orderTransfer,
            $salesOrderAmendmentItemCollectionTransfer,
        );

        // Assert
        $this->assertCount(1, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip());
        $this->assertCount(0, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate());
        $this->assertSame(
            $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip()->offsetGet(0)->getShipment()->getIdSalesShipment(),
            1,
        );
    }
}
