<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Sales;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\ProductTypeOrderExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group ProductTypeOrderExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductTypeOrderExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_TYPE_NAME = 'service';

    /**
     * @var string
     */
    protected const ADDITIONAL_PRODUCT_TYPE_NAME = 'additional_type';

    /**
     * @var string
     */
    protected const TEST_STATE_MACHINE_PROCESS = 'Test01';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\ProductTypeOrderExpanderPlugin
     */
    protected ProductTypeOrderExpanderPlugin $plugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->cleanUpSalesOrderItemProductTypeRelations();
        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE_PROCESS]);
        $this->plugin = new ProductTypeOrderExpanderPlugin();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->cleanUpSalesOrderItemProductTypeRelations();
    }

    /**
     * @return void
     */
    public function testHydrateExpandsOrderItemsWithProductTypes(): void
    {
        // Arrange
        $itemData = [
            ItemTransfer::SKU => 'test-sku',
            ItemTransfer::NAME => 'Test product',
            ItemTransfer::UNIT_PRICE => 10000,
            ItemTransfer::QUANTITY => 1,
        ];

        $saveOrderTransfer = $this->tester->haveOrder(
            [$itemData],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setItems(new ArrayObject($saveOrderTransfer->getOrderItems()));
        $orderTransfer->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $this->tester->haveSalesOrderItemProductType(static::PRODUCT_TYPE_NAME, $orderTransfer->getItems()[0]->getIdSalesOrderItemOrFail());

        // Act
        $resultOrderTransfer = $this->plugin->hydrate($orderTransfer);

        // Assert
        $this->assertSame($orderTransfer, $resultOrderTransfer, 'Order transfer should be returned unchanged');

        $itemTransfer = $resultOrderTransfer->getItems()[0];
        $this->assertNotEmpty($itemTransfer->getProductTypes(), 'Product types should be set on the item transfer');
        $this->assertContains(static::PRODUCT_TYPE_NAME, $itemTransfer->getProductTypes(), 'Product type should match the one in the database');
    }

    /**
     * @return void
     */
    public function testHydrateExpandsOrderItemsWithMultipleProductTypes(): void
    {
        // Arrange
        $itemData = [
            ItemTransfer::SKU => 'test-sku-multi',
            ItemTransfer::NAME => 'Test product with multiple types',
            ItemTransfer::UNIT_PRICE => 10000,
            ItemTransfer::QUANTITY => 1,
        ];

        $saveOrderTransfer = $this->tester->haveOrder(
            [$itemData],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setItems(new ArrayObject($saveOrderTransfer->getOrderItems()));
        $orderTransfer->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $idSalesOrderItem = $orderTransfer->getItems()[0]->getIdSalesOrderItemOrFail();
        $this->tester->haveSalesOrderItemProductType(static::PRODUCT_TYPE_NAME, $idSalesOrderItem);
        $this->tester->haveSalesOrderItemProductType(static::ADDITIONAL_PRODUCT_TYPE_NAME, $idSalesOrderItem);

        // Act
        $resultOrderTransfer = $this->plugin->hydrate($orderTransfer);

        // Assert
        $itemTransfer = $resultOrderTransfer->getItems()[0];
        $this->assertCount(2, $itemTransfer->getProductTypes(), 'Item should have two product types');
        $this->assertContains(static::PRODUCT_TYPE_NAME, $itemTransfer->getProductTypes(), 'First product type should be set');
        $this->assertContains(static::ADDITIONAL_PRODUCT_TYPE_NAME, $itemTransfer->getProductTypes(), 'Second product type should be set');
    }

    /**
     * @return void
     */
    public function testHydrateDoesNothingWhenNoOrderItemsProvided(): void
    {
        // Arrange
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setItems(new ArrayObject());

        // Act
        $resultOrderTransfer = $this->plugin->hydrate($orderTransfer);

        // Assert
        $this->assertSame($orderTransfer, $resultOrderTransfer, 'Order transfer should be returned unchanged');
        $this->assertCount(0, $resultOrderTransfer->getItems(), 'Order should have no items');
    }

    /**
     * @return void
     */
    public function testHydrateDoesNothingWhenNoProductTypesFound(): void
    {
        // Arrange
        $itemData = [
            ItemTransfer::SKU => 'test-sku-no-types',
            ItemTransfer::NAME => 'Test product with no types',
            ItemTransfer::UNIT_PRICE => 10000,
            ItemTransfer::QUANTITY => 1,
        ];

        $saveOrderTransfer = $this->tester->haveOrder(
            [$itemData],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setItems(new ArrayObject($saveOrderTransfer->getOrderItems()));
        $orderTransfer->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $resultOrderTransfer = $this->plugin->hydrate($orderTransfer);

        // Assert
        $itemTransfer = $resultOrderTransfer->getItems()[0];
        $this->assertEmpty($itemTransfer->getProductTypes(), 'Product types should be empty when none found in database');
    }
}
