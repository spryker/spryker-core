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
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductAbstractTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesProductAbstractTypeQuery;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\ProductTypeOrderItemsPostSavePlugin;
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
 * @group ProductTypeOrderItemsPostSavePluginTest
 * Add your own group annotations below this line
 */
class ProductTypeOrderItemsPostSavePluginTest extends Unit
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
     * @var string
     */
    protected const TEST_ORDER_REFERENCE = 'TEST-ORDER-001';

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\ProductTypeOrderItemsPostSavePlugin
     */
    protected ProductTypeOrderItemsPostSavePlugin $plugin;

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

        $this->cleanUpDatabase();

        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE_PROCESS]);

        $this->plugin = new ProductTypeOrderItemsPostSavePlugin();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cleanUpDatabase();
    }

    /**
     * @return void
     */
    protected function cleanUpDatabase(): void
    {
        SpySalesOrderItemProductAbstractTypeQuery::create()->deleteAll();

        SpySalesProductAbstractTypeQuery::create()
            ->filterByName_In([static::PRODUCT_TYPE_NAME, static::ADDITIONAL_PRODUCT_TYPE_NAME])
            ->delete();
    }

    /**
     * @return void
     */
    public function testExecuteSavesProductTypeToDatabase(): void
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
        $itemTransfer->addProductType(static::PRODUCT_TYPE_NAME);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $resultSaveOrderTransfer = $this->plugin->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $this->assertSame($saveOrderTransfer, $resultSaveOrderTransfer, 'SaveOrderTransfer should be returned unchanged');

        $savedProductType = SpySalesProductAbstractTypeQuery::create()
            ->filterByName(static::PRODUCT_TYPE_NAME)
            ->findOne();

        $this->assertNotNull($savedProductType, 'Product type should be saved in the database');

        $orderItemProductType = SpySalesOrderItemProductAbstractTypeQuery::create()
            ->filterByFkSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->findOne();

        $this->assertNotNull($orderItemProductType, 'Order item - product type relation should exist');
        $this->assertEquals(
            $savedProductType->getIdSalesProductAbstractType(),
            $orderItemProductType->getFkSalesProductAbstractType(),
            'Order item should be related to the correct product type',
        );
    }

    /**
     * @return void
     */
    public function testExecuteWithMultipleProductTypes(): void
    {
        // Arrange
        $itemData = [
            ItemTransfer::SKU => 'sku-multi-types',
            ItemTransfer::NAME => 'Test product with multiple types',
            ItemTransfer::UNIT_PRICE => 10000,
            ItemTransfer::QUANTITY => 1,
        ];

        $saveOrderTransfer = $this->tester->haveOrder(
            [$itemData],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $itemTransfer = $saveOrderTransfer->getOrderItems()[0];

        $itemTransfer->addProductType(static::PRODUCT_TYPE_NAME);
        $itemTransfer->addProductType(static::ADDITIONAL_PRODUCT_TYPE_NAME);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $this->plugin->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $serviceType = SpySalesProductAbstractTypeQuery::create()
            ->filterByName(static::PRODUCT_TYPE_NAME)
            ->findOne();

        $additionalType = SpySalesProductAbstractTypeQuery::create()
            ->filterByName(static::ADDITIONAL_PRODUCT_TYPE_NAME)
            ->findOne();

        $this->assertNotNull($serviceType, 'Service product type should be created');
        $this->assertNotNull($additionalType, 'Additional product type should be created');

        $itemProductTypes = SpySalesOrderItemProductAbstractTypeQuery::create()
            ->filterByFkSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->find();

        $this->assertEquals(2, $itemProductTypes->count(), 'Two product type relations should be created');
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

        $itemTransfers[0]->addProductType(static::PRODUCT_TYPE_NAME);
        $itemTransfers[1]->addProductType(static::ADDITIONAL_PRODUCT_TYPE_NAME);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfers[0]);
        $quoteTransfer->addItem($itemTransfers[1]);

        // Act
        $this->plugin->execute($combinedSaveOrderTransfer, $quoteTransfer);

        // Assert
        $serviceType = SpySalesProductAbstractTypeQuery::create()
            ->filterByName(static::PRODUCT_TYPE_NAME)
            ->findOne();

        $additionalType = SpySalesProductAbstractTypeQuery::create()
            ->filterByName(static::ADDITIONAL_PRODUCT_TYPE_NAME)
            ->findOne();

        $this->assertNotNull($serviceType, 'Service product type should be created');
        $this->assertNotNull($additionalType, 'Additional product type should be created');

        $item1ProductTypes = SpySalesOrderItemProductAbstractTypeQuery::create()
            ->filterByFkSalesOrderItem($itemTransfers[0]->getIdSalesOrderItem())
            ->find();

        $this->assertEquals(1, $item1ProductTypes->count(), 'First item should have one product type');

        $item2ProductTypes = SpySalesOrderItemProductAbstractTypeQuery::create()
            ->filterByFkSalesOrderItem($itemTransfers[1]->getIdSalesOrderItem())
            ->find();

        $this->assertEquals(1, $item2ProductTypes->count(), 'Second item should have one product type');
    }

    /**
     * @return void
     */
    public function testExecuteWithEmptyProductTypes(): void
    {
        // Arrange
        $itemData = [
            ItemTransfer::SKU => 'sku-no-types',
            ItemTransfer::NAME => 'Test product without types',
            ItemTransfer::UNIT_PRICE => 10000,
            ItemTransfer::QUANTITY => 1,
        ];

        $saveOrderTransfer = $this->tester->haveOrder(
            [$itemData],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $itemTransfer = $saveOrderTransfer->getOrderItems()[0];

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $this->plugin->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $itemProductTypes = SpySalesOrderItemProductAbstractTypeQuery::create()
            ->filterByFkSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->find();

        $this->assertEquals(0, $itemProductTypes->count(), 'No product type relations should be created for items without types');
    }
}
