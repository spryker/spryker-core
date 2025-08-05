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
use Generated\Shared\Transfer\SspAssetTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\SspAssetOrderItemsPostSavePlugin;
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
 * @group SspAssetOrderItemsPostSavePluginTest
 * Add your own group annotations below this line
 */
class SspAssetOrderItemsPostSavePluginTest extends Unit
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
        $this->tester->ensureSalesOrderItemSspAssetTableIsEmpty();
    }

    public function testExecuteDoesNotCreateSspAssetsWhenNoAssetsProvided(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithOrderItems();
        $saveOrderTransfer = $this->createSaveOrderTransfer();

        // Act
        (new SspAssetOrderItemsPostSavePlugin())->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $this->assertSame(0, $this->tester->countSalesOrderItemSspAssets());
    }

    public function testExecuteCreatesSspAssetWhenAssetProvided(): void
    {
        // Arrange
        $sspAssetTransfer = $this->tester->haveAsset([
            'reference' => 'TEST-ASSET-001',
            'name' => 'Test Asset',
            'serialNumber' => 'SN-12345',
        ]);

        $quoteTransfer = $this->createQuoteTransferWithOrderItems();
        $saveOrderTransfer = $this->createSaveOrderTransfer();
        $saveOrderTransfer->setOrderItems($quoteTransfer->getItems());

        $itemTransfer = $quoteTransfer->getItems()[0];
        $itemTransfer->setSspAsset($sspAssetTransfer);

        // Act
        (new SspAssetOrderItemsPostSavePlugin())->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $this->assertSame(1, $this->tester->countSalesOrderItemSspAssets());
        $this->assertSalesOrderItemSspAssetExists(
            $itemTransfer->getIdSalesOrderItem(),
            $sspAssetTransfer->getReferenceOrFail(),
        );
    }

    public function testExecuteCreatesMultipleSspAssetRelations(): void
    {
        // Arrange
        $sspAsset1Transfer = $this->tester->haveAsset([
            'reference' => 'TEST-ASSET-001',
            'name' => 'Test Asset 1',
        ]);

        $sspAsset2Transfer = $this->tester->haveAsset([
            'reference' => 'TEST-ASSET-002',
            'name' => 'Test Asset 2',
        ]);

        $quoteTransfer = $this->createQuoteTransferWithMultipleOrderItems();
        $saveOrderTransfer = $this->createSaveOrderTransfer();
        $saveOrderTransfer->setOrderItems($quoteTransfer->getItems());

        $items = $quoteTransfer->getItems();
        $items[0]->setSspAsset($sspAsset1Transfer);
        $items[1]->setSspAsset($sspAsset2Transfer);

        // Act
        (new SspAssetOrderItemsPostSavePlugin())->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $this->assertSame(2, $this->tester->countSalesOrderItemSspAssets());
        $this->assertSalesOrderItemSspAssetExists(
            $items[0]->getIdSalesOrderItem(),
            $sspAsset1Transfer->getReferenceOrFail(),
        );
        $this->assertSalesOrderItemSspAssetExists(
            $items[1]->getIdSalesOrderItem(),
            $sspAsset2Transfer->getReferenceOrFail(),
        );
    }

    public function testExecuteHandlesAssetWithoutReferenceGracefully(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithOrderItems();
        $saveOrderTransfer = $this->createSaveOrderTransfer();

        $itemTransfer = $quoteTransfer->getItems()[0];
        $itemTransfer->setSspAsset((new SspAssetTransfer())->setName('Asset without reference'));

        // Act
        (new SspAssetOrderItemsPostSavePlugin())->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $this->assertSame(0, $this->tester->countSalesOrderItemSspAssets());
    }

    public function testExecuteHandlesNonExistentAssetReferenceGracefully(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithOrderItems();
        $saveOrderTransfer = $this->createSaveOrderTransfer();

        $itemTransfer = $quoteTransfer->getItems()[0];
        $itemTransfer->setSspAsset(
            (new SspAssetTransfer())
                ->setReference('NON-EXISTENT-REFERENCE')
                ->setName('Non-existent Asset'),
        );

        // Act
        (new SspAssetOrderItemsPostSavePlugin())->execute($saveOrderTransfer, $quoteTransfer);

        // Assert
        $this->assertSame(0, $this->tester->countSalesOrderItemSspAssets());
    }

    protected function createQuoteTransferWithOrderItems(): QuoteTransfer
    {
        $productTransfer = $this->tester->haveFullProduct([
            'name' => 'Test Product',
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

        $quoteTransfer = new QuoteTransfer();
        $orderItems = $saveOrderTransfer->getOrderItems();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem());
        $itemTransfer->setSku($productTransfer->getSkuOrFail());
        $quoteTransfer->setItems(new ArrayObject([$itemTransfer]));

        return $quoteTransfer;
    }

    protected function createQuoteTransferWithMultipleOrderItems(): QuoteTransfer
    {
        $product1Transfer = $this->tester->haveFullProduct([
            'name' => 'Test Product 1',
        ]);
        $product2Transfer = $this->tester->haveFullProduct([
            'name' => 'Test Product 2',
        ]);

        $saveOrderTransfer = $this->tester->haveOrder([
            'items' => [
                [
                    'sku' => $product1Transfer->getSkuOrFail(),
                    'name' => $product1Transfer->getNameOrFail(),
                    'quantity' => 1,
                    'unit_price' => 1000,
                    'unit_gross_price' => 1000,
                    'group_key' => 'key1',
                ],
                [
                    'sku' => $product2Transfer->getSkuOrFail(),
                    'name' => $product2Transfer->getNameOrFail(),
                    'quantity' => 1,
                    'unit_price' => 2000,
                    'unit_gross_price' => 2000,
                    'group_key' => 'key2',
                ],
            ],
        ], static::OMS_PROCESS_NAME);

        $quoteTransfer = new QuoteTransfer();
        $orderItems = $saveOrderTransfer->getOrderItems();

        $item1Transfer = new ItemTransfer();
        $item1Transfer->setIdSalesOrderItem($orderItems[0]->getIdSalesOrderItem());
        $item1Transfer->setSku($product1Transfer->getSkuOrFail());

        $item2Transfer = new ItemTransfer();
        $item2Transfer->setIdSalesOrderItem($orderItems[1]->getIdSalesOrderItem());
        $item2Transfer->setSku($product2Transfer->getSkuOrFail());

        $quoteTransfer->setItems(new ArrayObject([$item1Transfer, $item2Transfer]));

        return $quoteTransfer;
    }

    protected function createSaveOrderTransfer(): SaveOrderTransfer
    {
        return new SaveOrderTransfer();
    }

    protected function assertSalesOrderItemSspAssetExists(int $idSalesOrderItem, string $assetReference): void
    {
        $relation = $this->tester->findSalesOrderItemSspAsset($idSalesOrderItem, $assetReference);

        $this->assertNotNull($relation, 'Expected relation between sales order item and SSP asset not found');
        $this->assertSame($idSalesOrderItem, $relation->getFkSalesOrderItem());
        $this->assertSame($assetReference, $relation->getReference());
    }
}
