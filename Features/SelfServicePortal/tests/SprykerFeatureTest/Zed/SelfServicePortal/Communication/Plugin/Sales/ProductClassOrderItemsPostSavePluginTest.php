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
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\ProductClassOrderItemsPostSavePlugin;
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
 * @group ProductClassOrderItemsPostSavePluginTest
 * Add your own group annotations below this line
 */
class ProductClassOrderItemsPostSavePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const OMS_PROCESS_NAME = 'test01';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemProductClassTableIsEmpty();
    }

    public function testExecuteDoesNotCreateProductClassesWhenNoProductClassesProvided(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithOrderItems();
        $saveOrderTransfer = $this->createSaveOrderTransfer();

        // Act
        (new ProductClassOrderItemsPostSavePlugin())->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $this->assertSame(0, $this->tester->countSalesOrderItemProductClasses());
    }

    public function testExecuteCreatesProductClassesWhenProductClassesProvided(): void
    {
        // Arrange
        $productClassTransfer = $this->tester->haveProductClass();
        $quoteTransfer = $this->createQuoteTransferWithOrderItems();
        $saveOrderTransfer = $this->createSaveOrderTransfer();

        // Add product classes to the item
        $itemTransfer = $quoteTransfer->getItems()[0];
        $itemTransfer->setProductClasses(new ArrayObject([$productClassTransfer]));

        // Act
        (new ProductClassOrderItemsPostSavePlugin())->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $this->assertSalesOrderItemProductClassesExist($itemTransfer->getIdSalesOrderItem(), $productClassTransfer->getNameOrFail());
    }

    public function testExecuteCreatesMultipleProductClassRelations(): void
    {
        // Arrange
        $productClass1Transfer = $this->tester->haveProductClass();
        $productClass2Transfer = $this->tester->haveProductClass();
        $quoteTransfer = $this->createQuoteTransferWithOrderItems();
        $saveOrderTransfer = $this->createSaveOrderTransfer();

        // Add multiple product classes to the item
        $itemTransfer = $quoteTransfer->getItems()[0];
        $itemTransfer->setProductClasses(new ArrayObject([
            $productClass1Transfer,
            $productClass2Transfer,
        ]));

        // Act
        (new ProductClassOrderItemsPostSavePlugin())->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $this->assertSame(2, $this->tester->countSalesOrderItemProductClasses());
        $this->assertSalesOrderItemProductClassesExist($itemTransfer->getIdSalesOrderItem(), $productClass1Transfer->getNameOrFail());
        $this->assertSalesOrderItemProductClassesExist($itemTransfer->getIdSalesOrderItem(), $productClass2Transfer->getNameOrFail());
    }

    protected function createQuoteTransferWithOrderItems(): QuoteTransfer
    {
        // Create a product with a name to avoid null value errors
        $productTransfer = $this->tester->haveFullProduct([
            'name' => 'Test Product',
        ]);

        // Create order with a valid sales order item in database
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

        // Create quote transfer with the created sales order item
        $quoteTransfer = new QuoteTransfer();
        $orderItems = $saveOrderTransfer->getOrderItems();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem());
        $itemTransfer->setSku($productTransfer->getSkuOrFail());
        $quoteTransfer->setItems(new ArrayObject([$itemTransfer]));

        return $quoteTransfer;
    }

    protected function createSaveOrderTransfer(): SaveOrderTransfer
    {
        return new SaveOrderTransfer();
    }

    protected function assertSalesOrderItemProductClassesExist(int $idSalesOrderItem, string $productClassName): void
    {
        $relation = $this->tester->findSalesOrderItemProductClass($idSalesOrderItem, $productClassName);

        $this->assertNotNull($relation, 'Expected relation between sales order item and product class not found');
        $this->assertSame($idSalesOrderItem, $relation->getFkSalesOrderItem());
        $this->assertSame($productClassName, $relation->getSpySalesProductClass()->getName());
    }
}
