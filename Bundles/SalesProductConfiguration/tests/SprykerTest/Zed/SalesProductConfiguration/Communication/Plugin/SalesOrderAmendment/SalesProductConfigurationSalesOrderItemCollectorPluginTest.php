<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConfiguration\Communication\Plugin\SalesOrderAmendment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;
use Spryker\Zed\SalesProductConfiguration\Communication\Plugin\SalesOrderAmendment\SalesProductConfigurationSalesOrderItemCollectorPlugin;
use SprykerTest\Zed\SalesProductConfiguration\SalesProductConfigurationCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConfiguration
 * @group Communication
 * @group Plugin
 * @group SalesOrderAmendment
 * @group SalesProductConfigurationSalesOrderItemCollectorPluginTest
 * Add your own group annotations below this line
 */
class SalesProductConfigurationSalesOrderItemCollectorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesProductConfiguration\SalesProductConfigurationCommunicationTester
     */
    protected SalesProductConfigurationCommunicationTester $tester;

    /**
     * @dataProvider addsItemWithUpdatedCartNoteToItemsToUpdateAndRemovesFromItemsToSkipDataProvider
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
     *
     * @return void
     */
    public function testAddsItemWithUpdatedCartNoteToItemsToUpdateAndRemovesFromItemsToSkip(
        OrderTransfer $orderTransfer,
        SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
    ): void {
        // Arrange
        $salesProductConfigurationSalesOrderItemCollectorPlugin = new SalesProductConfigurationSalesOrderItemCollectorPlugin();

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = $salesProductConfigurationSalesOrderItemCollectorPlugin->collect(
            $orderTransfer,
            $salesOrderAmendmentItemCollectionTransfer,
        );

        // Assert
        $this->assertCount(0, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip());
        $this->assertCount(1, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate());
    }

    /**
     * @dataProvider doesNotAddItemWithSameCartNoteToItemsToUpdateAndDoesNotRemoveFromItemsToSkipDataProvider
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
     *
     * @return void
     */
    public function testDoesNotAddItemWithSameCartNoteToItemsToUpdateAndDoesNotRemoveFromItemsToSkip(
        OrderTransfer $orderTransfer,
        SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
    ): void {
        // Arrange
        $salesProductConfigurationSalesOrderItemCollectorPlugin = new SalesProductConfigurationSalesOrderItemCollectorPlugin();

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = $salesProductConfigurationSalesOrderItemCollectorPlugin->collect(
            $orderTransfer,
            $salesOrderAmendmentItemCollectionTransfer,
        );

        // Assert
        $this->assertCount(0, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToUpdate());
        $this->assertCount(1, $updatedSalesOrderAmendmentItemCollectionTransfer->getItemsToSkip());
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer>>
     */
    protected function addsItemWithUpdatedCartNoteToItemsToUpdateAndRemovesFromItemsToSkipDataProvider(): array
    {
        return [
            'Configurator key is different' => [
                (new OrderTransfer())->addItem(
                    (new ItemTransfer())->setIdSalesOrderItem(1)->setSalesOrderItemConfiguration(
                        (new SalesOrderItemConfigurationTransfer())
                            ->setConfiguratorKey('configurator-key-1')
                            ->setConfiguration('config-1'),
                    ),
                ),
                (new SalesOrderAmendmentItemCollectionTransfer())->addItemToSkip(
                    (new ItemTransfer())->setIdSalesOrderItem(1)->setProductConfigurationInstance(
                        (new ProductConfigurationInstanceTransfer())
                            ->setConfiguratorKey('configurator-key-2')
                            ->setConfiguration('config-1'),
                    ),
                ),
            ],
            'Configuration is different' => [
                (new OrderTransfer())->addItem((new ItemTransfer())->setIdSalesOrderItem(1)
                    ->setSalesOrderItemConfiguration(
                        (new SalesOrderItemConfigurationTransfer())
                            ->setConfiguratorKey('configurator-key-1')
                            ->setConfiguration('config-1'),
                    )),
                (new SalesOrderAmendmentItemCollectionTransfer())->addItemToSkip(
                    (new ItemTransfer())->setIdSalesOrderItem(1)->setProductConfigurationInstance(
                        (new ProductConfigurationInstanceTransfer())
                            ->setConfiguratorKey('configurator-key-1')
                            ->setConfiguration('config-2'),
                    ),
                ),
            ],
            'Order item configuration transfer is not set' => [
                (new OrderTransfer())->addItem((new ItemTransfer())->setIdSalesOrderItem(1)),
                (new SalesOrderAmendmentItemCollectionTransfer())->addItemToSkip(
                    (new ItemTransfer())->setIdSalesOrderItem(1)->setProductConfigurationInstance(
                        (new ProductConfigurationInstanceTransfer())
                            ->setConfiguratorKey('configurator-key-1')
                            ->setConfiguration('config-1'),
                    ),
                ),
            ],
            'Skip item configuration transfer is not set' => [
                (new OrderTransfer())->addItem((new ItemTransfer())->setIdSalesOrderItem(1)
                    ->setSalesOrderItemConfiguration(
                        (new SalesOrderItemConfigurationTransfer())
                            ->setConfiguratorKey('configurator-key-1')
                            ->setConfiguration('config-1'),
                    )),
                (new SalesOrderAmendmentItemCollectionTransfer())->addItemToSkip(
                    (new ItemTransfer())->setIdSalesOrderItem(1),
                ),
            ],
        ];
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer>>
     */
    protected function doesNotAddItemWithSameCartNoteToItemsToUpdateAndDoesNotRemoveFromItemsToSkipDataProvider(): array
    {
        return [
            'Order item configuration and skip item configuration transfers are not set' => [
                (new OrderTransfer())->addItem((new ItemTransfer())->setIdSalesOrderItem(1)),
                (new SalesOrderAmendmentItemCollectionTransfer())->addItemToSkip(
                    (new ItemTransfer())->setIdSalesOrderItem(1),
                ),
            ],
            'Configurator key and configuration are the same' => [
                (new OrderTransfer())->addItem(
                    (new ItemTransfer())->setIdSalesOrderItem(1)->setSalesOrderItemConfiguration(
                        (new SalesOrderItemConfigurationTransfer())
                            ->setConfiguratorKey('configurator-key-1')
                            ->setConfiguration('config-1'),
                    ),
                ),
                (new SalesOrderAmendmentItemCollectionTransfer())->addItemToSkip(
                    (new ItemTransfer())->setIdSalesOrderItem(1)->setProductConfigurationInstance(
                        (new ProductConfigurationInstanceTransfer())
                            ->setConfiguratorKey('configurator-key-1')
                            ->setConfiguration('config-1'),
                    ),
                ),
            ],
        ];
    }
}
