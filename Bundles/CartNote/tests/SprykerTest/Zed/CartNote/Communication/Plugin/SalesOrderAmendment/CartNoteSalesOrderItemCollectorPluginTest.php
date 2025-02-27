<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartNote\Communication\Plugin\SalesOrderAmendment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;
use Spryker\Zed\CartNote\Communication\Plugin\SalesOrderAmendment\CartNoteSalesOrderItemCollectorPlugin;
use SprykerTest\Zed\CartNote\CartNoteCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CartNote
 * @group Communication
 * @group Plugin
 * @group SalesOrderAmendment
 * @group CartNoteSalesOrderItemCollectorPluginTest
 * Add your own group annotations below this line
 */
class CartNoteSalesOrderItemCollectorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CartNote\CartNoteCommunicationTester
     */
    protected CartNoteCommunicationTester $tester;

    /**
     * @return void
     */
    public function testAddsItemWithUpdatedCartNoteToItemsToUpdateAndRemovesFromItemsToSkip(): void
    {
        // Arrange
        $cartNoteSalesOrderItemCollectorPlugin = new CartNoteSalesOrderItemCollectorPlugin();
        $orderTransfer = (new OrderTransfer())->addItem(
            (new ItemTransfer())->setIdSalesOrderItem(1)->setCartNote('cart note 1'),
        );
        $salesOrderAmendmentItemCollectionTransfer = (new SalesOrderAmendmentItemCollectionTransfer())
            ->addItemToSkip(
                (new ItemTransfer())->setIdSalesOrderItem(1)->setCartNote('cart note 2'),
            );

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = $cartNoteSalesOrderItemCollectorPlugin->collect(
            $orderTransfer,
            $salesOrderAmendmentItemCollectionTransfer,
        );

        // Assert
        $this->assertCount(0, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip());
        $this->assertCount(1, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate());
        $this->assertSame($updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate()->offsetGet(0)->getCartNote(), 'cart note 2');
    }

    /**
     * @return void
     */
    public function testDoesNotAddItemWithSameCartNoteToItemsToUpdateAndDoesNotRemoveFromItemsToSkip(): void
    {
        // Arrange
        $cartNoteSalesOrderItemCollectorPlugin = new CartNoteSalesOrderItemCollectorPlugin();
        $orderTransfer = (new OrderTransfer())->addItem(
            (new ItemTransfer())->setIdSalesOrderItem(1)->setCartNote('cart note 1'),
        );
        $salesOrderAmendmentItemCollectionTransfer = (new SalesOrderAmendmentItemCollectionTransfer())
            ->addItemToSkip(
                (new ItemTransfer())->setIdSalesOrderItem(1)->setCartNote('cart note 1'),
            );

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = $cartNoteSalesOrderItemCollectorPlugin->collect(
            $orderTransfer,
            $salesOrderAmendmentItemCollectionTransfer,
        );

        // Assert
        $this->assertCount(0, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate());
        $this->assertCount(1, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip());
        $this->assertSame($updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip()->offsetGet(0)->getCartNote(), 'cart note 1');
    }
}
