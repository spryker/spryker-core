<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Sales;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\ServiceDateTimeEnabledOrderItemsPostSavePlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group ServiceDateTimeEnabledOrderItemsPostSavePluginTest
 * Add your own group annotations below this line
 */
class ServiceDateTimeEnabledOrderItemsPostSavePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_STATE_MACHINE_PROCESS = 'Test01';

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\ServiceDateTimeEnabledOrderItemsPostSavePlugin
     */
    protected ServiceDateTimeEnabledOrderItemsPostSavePlugin $plugin;

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE_PROCESS]);

        $this->plugin = new ServiceDateTimeEnabledOrderItemsPostSavePlugin();
    }

    /**
     * @return void
     */
    public function testExecuteSavesServiceDateTimeEnabledFlagToDatabase(): void
    {
        // Arrange
        $itemData = [
            ItemTransfer::SKU => 'sku-1',
            ItemTransfer::NAME => 'Test product',
            ItemTransfer::UNIT_PRICE => 10000,
            ItemTransfer::QUANTITY => 1,
        ];

        $saveOrderTransfer = $this->tester->haveOrder(
            [$itemData],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $itemTransfers = $saveOrderTransfer->getOrderItems();
        $this->assertNotEmpty($itemTransfers, 'Order items should have been created');

        $itemTransfer = $itemTransfers[0];
        $itemTransfer->setIsServiceDateTimeEnabled(true);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $resultSaveOrderTransfer = $this->plugin->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $this->assertSame($saveOrderTransfer, $resultSaveOrderTransfer, 'SaveOrderTransfer should be returned unchanged');

        $salesOrderItem = SpySalesOrderItemQuery::create()
            ->filterByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->findOne();

        $this->assertNotNull($salesOrderItem, 'Sales order item should exist in the database');
        $this->assertTrue($salesOrderItem->getIsServiceDateTimeEnabled(), 'isServiceDateTimeEnabled flag should be set to true');
    }

    /**
     * @return void
     */
    public function testExecuteWithFalseServiceDateTimeEnabledFlag(): void
    {
        // Arrange
        $itemData = [
            ItemTransfer::SKU => 'sku-2',
            ItemTransfer::NAME => 'Test product 2',
            ItemTransfer::UNIT_PRICE => 10000,
            ItemTransfer::QUANTITY => 1,
        ];

        $saveOrderTransfer = $this->tester->haveOrder(
            [$itemData],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $itemTransfer = $saveOrderTransfer->getOrderItems()[0];
        $itemTransfer->setIsServiceDateTimeEnabled(false);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $this->plugin->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $salesOrderItem = SpySalesOrderItemQuery::create()
            ->filterByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->findOne();

        $this->assertNotNull($salesOrderItem, 'Sales order item should exist in the database');
        $this->assertFalse($salesOrderItem->getIsServiceDateTimeEnabled(), 'isServiceDateTimeEnabled flag should be set to false');
    }

    /**
     * @return void
     */
    public function testExecuteWithMultipleItems(): void
    {
        // Arrange
        $itemData1 = [
            ItemTransfer::SKU => 'sku-item-1',
            ItemTransfer::NAME => 'Test product 1',
            ItemTransfer::UNIT_PRICE => 10000,
            ItemTransfer::QUANTITY => 1,
        ];

        $itemData2 = [
            ItemTransfer::SKU => 'sku-item-2',
            ItemTransfer::NAME => 'Test product 2',
            ItemTransfer::UNIT_PRICE => 20000,
            ItemTransfer::QUANTITY => 1,
        ];

        $saveOrderTransfer1 = $this->tester->haveOrder(
            [$itemData1],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $saveOrderTransfer2 = $this->tester->haveOrder(
            [$itemData2],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $itemTransfer1 = $saveOrderTransfer1->getOrderItems()[0];
        $itemTransfer2 = $saveOrderTransfer2->getOrderItems()[0];

        $itemTransfers = [$itemTransfer1, $itemTransfer2];
        $this->assertCount(2, $itemTransfers, 'Two order items should be created');

        $combinedSaveOrderTransfer = new SaveOrderTransfer();
        $combinedSaveOrderTransfer->setOrderItems(new ArrayObject($itemTransfers));

        $itemTransfers[0]->setIsServiceDateTimeEnabled(true);
        $itemTransfers[1]->setIsServiceDateTimeEnabled(false);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfers[0]);
        $quoteTransfer->addItem($itemTransfers[1]);

        // Act
        $this->plugin->execute($combinedSaveOrderTransfer, $quoteTransfer);

        // Assert
        $salesOrderItem1 = SpySalesOrderItemQuery::create()
            ->filterByIdSalesOrderItem($itemTransfers[0]->getIdSalesOrderItem())
            ->findOne();

        $salesOrderItem2 = SpySalesOrderItemQuery::create()
            ->filterByIdSalesOrderItem($itemTransfers[1]->getIdSalesOrderItem())
            ->findOne();

        $this->assertNotNull($salesOrderItem1, 'First sales order item should exist in the database');
        $this->assertNotNull($salesOrderItem2, 'Second sales order item should exist in the database');

        $this->assertTrue($salesOrderItem1->getIsServiceDateTimeEnabled(), 'First item should have isServiceDateTimeEnabled set to true');
        $this->assertFalse($salesOrderItem2->getIsServiceDateTimeEnabled(), 'Second item should have isServiceDateTimeEnabled set to false');
    }

    /**
     * @return void
     */
    public function testExecuteWithNullServiceDateTimeEnabledFlag(): void
    {
        // Arrange
        $itemData = [
            ItemTransfer::SKU => 'sku-null-flag',
            ItemTransfer::NAME => 'Test product with null flag',
            ItemTransfer::UNIT_PRICE => 10000,
            ItemTransfer::QUANTITY => 1,
        ];

        $saveOrderTransfer = $this->tester->haveOrder(
            [$itemData],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $itemTransfer = $saveOrderTransfer->getOrderItems()[0];
        $itemTransfer->setIsServiceDateTimeEnabled(null);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $this->plugin->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $salesOrderItem = SpySalesOrderItemQuery::create()
            ->filterByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->findOne();

        $this->assertNotNull($salesOrderItem, 'Sales order item should exist in the database');
        $this->assertFalse($salesOrderItem->getIsServiceDateTimeEnabled(), 'isServiceDateTimeEnabled flag should be set to false when null is provided');
    }
}
