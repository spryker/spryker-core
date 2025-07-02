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
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\ProductClassOrderExpanderPlugin;
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
 * @group ProductClassOrderExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductClassOrderExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const OMS_PROCESS_NAME = 'test01';

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

        $this->tester->configureTestStateMachine([static::OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testHydrateExpandsOrderItemsWithProductClasses(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct([
            'name' => 'Test Product',
        ]);
        $productClassTransfer = $this->tester->haveProductClass();
        $this->tester->haveProductToProductClass(
            $productTransfer->getIdProductConcrete(),
            $productClassTransfer->getIdProductClass(),
        );

        // Create a real order in the database with our product
        $saveOrderTransfer = $this->tester->haveOrder([
            'items' => [
                [
                    'sku' => $productTransfer->getSkuOrFail(),
                    'name' => $productTransfer->getNameOrFail(),
                    'quantity' => 1,
                    'unit_price' => 1000,
                    'unit_gross_price' => 1000,
                    'group_key' => 'key1',
                ],
            ],
        ], static::OMS_PROCESS_NAME);

        $orderTransfer = new OrderTransfer();

        // Get the first order item from the created order
        $orderItems = $saveOrderTransfer->getOrderItems();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem());
        $itemTransfer->setSku($productTransfer->getSkuOrFail());

        $orderTransfer->setItems(new ArrayObject([$itemTransfer]));

        // Act
        $plugin = new ProductClassOrderExpanderPlugin();
        $resultOrderTransfer = $plugin->hydrate($orderTransfer);

        // Assert
        $this->assertCount(1, $resultOrderTransfer->getItems());

        $resultItemTransfer = $resultOrderTransfer->getItems()[0];
        $this->assertNotNull($resultItemTransfer->getProductClasses());
        $this->assertNotEmpty($resultItemTransfer->getProductClasses());

        // Find our specific product class in the results
        $found = false;
        foreach ($resultItemTransfer->getProductClasses() as $resultProductClass) {
            if (
                $resultProductClass->getName() === $productClassTransfer->getName() &&
                $resultProductClass->getIdProductClass() === $productClassTransfer->getIdProductClass()
            ) {
                $found = true;

                break;
            }
        }

        $this->assertTrue($found, 'Expected product class not found in results');
    }

    /**
     * @return void
     */
    public function testHydrateExpandsOrderItemsWithMultipleProductClasses(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct([
            'name' => 'Test Product Multiple Classes',
        ]);
        $productClass1Transfer = $this->tester->haveProductClass();
        $productClass2Transfer = $this->tester->haveProductClass();

        $this->tester->haveProductToProductClass(
            $productTransfer->getIdProductConcrete(),
            $productClass1Transfer->getIdProductClass(),
        );

        $this->tester->haveProductToProductClass(
            $productTransfer->getIdProductConcrete(),
            $productClass2Transfer->getIdProductClass(),
        );

        // Create a real order in the database with our product
        $saveOrderTransfer = $this->tester->haveOrder([
            'items' => [
                [
                    'sku' => $productTransfer->getSkuOrFail(),
                    'name' => $productTransfer->getNameOrFail(),
                    'quantity' => 1,
                    'unit_price' => 1000,
                    'unit_gross_price' => 1000,
                    'group_key' => 'key1',
                ],
            ],
        ], static::OMS_PROCESS_NAME);

        $orderTransfer = new OrderTransfer();

        $orderItems = $saveOrderTransfer->getOrderItems();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem());
        $itemTransfer->setSku($productTransfer->getSkuOrFail());

        $orderTransfer->setItems(new ArrayObject([$itemTransfer]));

        // Act
        $plugin = new ProductClassOrderExpanderPlugin();
        $resultOrderTransfer = $plugin->hydrate($orderTransfer);

        // Assert
        $this->assertCount(1, $resultOrderTransfer->getItems());
        $resultItemTransfer = $resultOrderTransfer->getItems()[0];
        $this->assertNotNull($resultItemTransfer->getProductClasses());
        $this->assertGreaterThanOrEqual(2, count($resultItemTransfer->getProductClasses()));

        // Verify both product classes are present
        $foundClasses = [];
        foreach ($resultItemTransfer->getProductClasses() as $resultProductClass) {
            if ($resultProductClass->getIdProductClass() === $productClass1Transfer->getIdProductClass()) {
                $foundClasses[] = $productClass1Transfer->getIdProductClass();
            }
            if ($resultProductClass->getIdProductClass() === $productClass2Transfer->getIdProductClass()) {
                $foundClasses[] = $productClass2Transfer->getIdProductClass();
            }
        }

        $this->assertCount(2, $foundClasses, 'Not all expected product classes were found');
    }

    /**
     * @return void
     */
    public function testHydrateHandlesOrderWithNoProductClasses(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveFullProduct([
            'name' => 'Product Without Classes',
        ]);

        $saveOrderTransfer = $this->tester->haveOrder([
            'items' => [
                [
                    'sku' => $productTransfer->getSkuOrFail(),
                    'name' => $productTransfer->getNameOrFail(),
                    'quantity' => 1,
                    'unit_price' => 1000,
                    'unit_gross_price' => 1000,
                    'group_key' => 'key1',
                ],
            ],
        ], static::OMS_PROCESS_NAME);

        $orderTransfer = new OrderTransfer();

        $orderItems = $saveOrderTransfer->getOrderItems();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem());
        $itemTransfer->setSku($productTransfer->getSkuOrFail());

        $orderTransfer->setItems(new ArrayObject([$itemTransfer]));

        // Act
        $plugin = new ProductClassOrderExpanderPlugin();
        $resultOrderTransfer = $plugin->hydrate($orderTransfer);

        // Assert
        $this->assertCount(1, $resultOrderTransfer->getItems());
        $resultItemTransfer = $resultOrderTransfer->getItems()[0];
        $this->assertEmpty($resultItemTransfer->getProductClasses());
    }

    /**
     * @return void
     */
    public function testHydrateWithEmptyOrderReturnsUnchangedOrder(): void
    {
        // Arrange
        $orderTransfer = new OrderTransfer();

        // Act
        $resultOrderTransfer = (new ProductClassOrderExpanderPlugin())
            ->hydrate($orderTransfer);

        // Assert
        $this->assertEmpty($resultOrderTransfer->getItems());
    }
}
