<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesServicePoint\Communication\Plugin\SalesOrderAmendment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Zed\SalesServicePoint\Communication\Plugin\SalesOrderAmendment\SalesServicePointSalesOrderItemCollectorPlugin;
use SprykerTest\Zed\SalesServicePoint\SalesServicePointCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesServicePoint
 * @group Communication
 * @group Plugin
 * @group SalesOrderAmendment
 * @group SalesServicePointSalesOrderItemCollectorPluginTest
 * Add your own group annotations below this line
 */
class SalesServicePointSalesOrderItemCollectorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesServicePoint\SalesServicePointCommunicationTester
     */
    protected SalesServicePointCommunicationTester $tester;

    /**
     * @return void
     */
    public function testAddsItemWithUpdatedServicePointToItemsToUpdateAndRemovesFromItemsToSkip(): void
    {
        // Arrange
        $salesServicePointSalesOrderItemCollectorPlugin = new SalesServicePointSalesOrderItemCollectorPlugin();
        $orderTransfer = (new OrderTransfer())->addItem(
            (new ItemTransfer())
                ->setIdSalesOrderItem(1)
                ->setSalesOrderItemServicePoint(
                    (new SalesOrderItemServicePointTransfer())->setKey('key 1'),
                ),
        );
        $salesOrderAmendmentItemCollectionTransfer = (new SalesOrderAmendmentItemCollectionTransfer())
            ->addItemToSkip(
                (new ItemTransfer())
                    ->setIdSalesOrderItem(1)
                    ->setServicePoint(
                        (new ServicePointTransfer())->setKey('key 2'),
                    ),
            );

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = $salesServicePointSalesOrderItemCollectorPlugin->collect(
            $orderTransfer,
            $salesOrderAmendmentItemCollectionTransfer,
        );

        // Assert
        $this->assertCount(0, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip());
        $this->assertCount(1, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate());
    }

    /**
     * @return void
     */
    public function testDoesNotAddItemWithSameServicePointToItemsToUpdateAndDoesNotRemoveFromItemsToSkip(): void
    {
// Arrange
        $salesServicePointSalesOrderItemCollectorPlugin = new SalesServicePointSalesOrderItemCollectorPlugin();
        $orderTransfer = (new OrderTransfer())->addItem(
            (new ItemTransfer())
                ->setIdSalesOrderItem(1)
                ->setSalesOrderItemServicePoint(
                    (new SalesOrderItemServicePointTransfer())->setKey('key 1'),
                ),
        );
        $salesOrderAmendmentItemCollectionTransfer = (new SalesOrderAmendmentItemCollectionTransfer())
            ->addItemToSkip(
                (new ItemTransfer())
                    ->setIdSalesOrderItem(1)
                    ->setServicePoint(
                        (new ServicePointTransfer())->setKey('key 1'),
                    ),
            );

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = $salesServicePointSalesOrderItemCollectorPlugin->collect(
            $orderTransfer,
            $salesOrderAmendmentItemCollectionTransfer,
        );

        // Assert
        $this->assertCount(1, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip());
        $this->assertCount(0, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate());
    }
}
