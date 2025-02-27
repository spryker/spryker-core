<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleNote\Communication\Plugin\SalesOrderAmendment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;
use Spryker\Zed\ConfigurableBundleNote\Communication\Plugin\SalesOrderAmendment\ConfigurableBundleNoteSalesOrderItemCollectorPlugin;
use SprykerTest\Zed\ConfigurableBundleNote\ConfigurableBundleNoteCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundleNote
 * @group Communication
 * @group Plugin
 * @group SalesOrderAmendment
 * @group ConfigurableBundleNoteSalesOrderItemCollectorPluginTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleNoteSalesOrderItemCollectorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ConfigurableBundleNote\ConfigurableBundleNoteCommunicationTester
     */
    protected ConfigurableBundleNoteCommunicationTester $tester;

    /**
     * @return void
     */
    public function testAddsItemWithUpdatedCartNoteToItemsToUpdateAndRemovesFromItemsToSkip(): void
    {
        // Arrange
        $configurableBundleNoteSalesOrderItemCollectorPlugin = new ConfigurableBundleNoteSalesOrderItemCollectorPlugin();
        $orderTransfer = (new OrderTransfer())->addItem(
            (new ItemTransfer())
                ->setIdSalesOrderItem(1)
                ->setConfiguredBundle(
                    (new ConfiguredBundleTransfer())->setNote('note 1'),
                ),
        );
        $salesOrderAmendmentItemCollectionTransfer = (new SalesOrderAmendmentItemCollectionTransfer())
            ->addItemToSkip(
                (new ItemTransfer())
                    ->setIdSalesOrderItem(1)
                    ->setConfiguredBundle(
                        (new ConfiguredBundleTransfer())->setNote('note 2'),
                    ),
            );

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = $configurableBundleNoteSalesOrderItemCollectorPlugin->collect(
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
    public function testDoesNotAddItemWithSameCartNoteToItemsToUpdateAndDoesNotRemoveFromItemsToSkip(): void
    {
        // Arrange
        $configurableBundleNoteSalesOrderItemCollectorPlugin = new ConfigurableBundleNoteSalesOrderItemCollectorPlugin();
        $orderTransfer = (new OrderTransfer())->addItem(
            (new ItemTransfer())
                ->setIdSalesOrderItem(1)
                ->setConfiguredBundle(
                    (new ConfiguredBundleTransfer())->setNote('note 1'),
                ),
        );
        $salesOrderAmendmentItemCollectionTransfer = (new SalesOrderAmendmentItemCollectionTransfer())
            ->addItemToSkip(
                (new ItemTransfer())
                    ->setIdSalesOrderItem(1)
                    ->setConfiguredBundle(
                        (new ConfiguredBundleTransfer())->setNote('note 1'),
                    ),
            );

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = $configurableBundleNoteSalesOrderItemCollectorPlugin->collect(
            $orderTransfer,
            $salesOrderAmendmentItemCollectionTransfer,
        );

        // Assert
        $this->assertCount(1, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip());
        $this->assertCount(0, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate());
    }
}
