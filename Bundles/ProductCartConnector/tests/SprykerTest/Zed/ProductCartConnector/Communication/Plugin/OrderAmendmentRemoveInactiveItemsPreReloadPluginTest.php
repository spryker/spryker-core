<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCartConnector\Communication\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductCartConnector\Communication\Plugin\Cart\OrderAmendmentRemoveInactiveItemsPreReloadPlugin;
use SprykerTest\Zed\ProductCartConnector\ProductCartConnectorCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCartConnector
 * @group Communication
 * @group Plugin
 * @group OrderAmendmentRemoveInactiveItemsPreReloadPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentRemoveInactiveItemsPreReloadPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductCartConnector\ProductCartConnectorCommunicationTester
     */
    protected ProductCartConnectorCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPreReloadItemsShouldFilterInactiveItems(): void
    {
        // Arrange
        $storeTransfer = $this->tester->getAllowedStore();
        $productConcreteTransfer1 = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);
        $productConcreteTransfer2 = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);
        $productConcreteTransfer3 = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withItem([ItemTransfer::SKU => $productConcreteTransfer1->getSku()])
            ->withAnotherItem([ItemTransfer::SKU => $productConcreteTransfer2->getSku()])
            ->withAnotherItem([ItemTransfer::SKU => $productConcreteTransfer3->getSku()])
            ->build();

        // Act
        $quoteTransfer = (new OrderAmendmentRemoveInactiveItemsPreReloadPlugin())
            ->preReloadItems($quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getItems());
        $itemTransfer = $quoteTransfer->getItems()->getIterator()->current();
        $this->assertSame($productConcreteTransfer3->getSku(), $itemTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testPreReloadItemsShouldNotFilterInactiveItemsFromOriginalOrder(): void
    {
        // Arrange
        $storeTransfer = $this->tester->getAllowedStore();
        $productConcreteTransfer1 = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);
        $productConcreteTransfer2 = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);
        $productConcreteTransfer3 = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withItem([ItemTransfer::SKU => $productConcreteTransfer1->getSku()])
            ->withAnotherItem([ItemTransfer::SKU => $productConcreteTransfer2->getSku()])
            ->withAnotherItem([ItemTransfer::SKU => $productConcreteTransfer3->getSku()])
            ->build();

        $quoteTransfer->setOriginalSalesOrderItems(new ArrayObject([
            (new OriginalSalesOrderItemTransfer())->setSku($productConcreteTransfer1->getSku()),
            (new OriginalSalesOrderItemTransfer())->setSku($productConcreteTransfer2->getSku()),
            (new OriginalSalesOrderItemTransfer())->setSku($productConcreteTransfer3->getSku()),
        ]));

        // Act
        $quoteTransfer = (new OrderAmendmentRemoveInactiveItemsPreReloadPlugin())
            ->preReloadItems($quoteTransfer);

        // Assert
        $this->assertCount(3, $quoteTransfer->getItems());
    }
}
