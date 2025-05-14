<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspAssetManagement\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use SprykerFeature\Zed\SspAssetManagement\Communication\Expander\OrderItemSspAssetExpander;
use SprykerFeature\Zed\SspAssetManagement\Communication\Extractor\SalesOrderItemIdExtractor;
use SprykerFeature\Zed\SspAssetManagement\Communication\Plugin\Sales\SspAssetOrderExpanderPlugin;
use SprykerFeature\Zed\SspAssetManagement\Communication\SspAssetManagementCommunicationFactory;
use SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface;
use SprykerFeatureTest\Zed\SspAssetManagement\SspAssetManagementCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspAssetManagement
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group SspAssetOrderExpanderPluginTest
 */
class SspAssetOrderExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspAssetManagement\SspAssetManagementCommunicationTester
     */
    protected SspAssetManagementCommunicationTester $tester;

    /**
     * @return void
     */
    public function testHydrateExpandsOrderItemsWithSspAssets(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderTransferWithItems();
        $sspAssetTransfersIndexedBySalesOrderItemId = $this->tester->createSspAssetTransfersIndexedBySalesOrderItemId();

        $sspAssetManagementRepositoryMock = $this->createMock(SspAssetManagementRepositoryInterface::class);
        $sspAssetManagementRepositoryMock->method('getSspAssetsIndexedByIdSalesOrderItem')
            ->with([1, 2])
            ->willReturn($sspAssetTransfersIndexedBySalesOrderItemId);

        $factoryMock = $this->createMock(SspAssetManagementCommunicationFactory::class);
        $factoryMock->method('createOrderItemSspAssetExpander')
            ->willReturn(new OrderItemSspAssetExpander($sspAssetManagementRepositoryMock, new SalesOrderItemIdExtractor()));

        $sspAssetOrderExpanderPlugin = new SspAssetOrderExpanderPlugin();
        $sspAssetOrderExpanderPlugin->setFactory($factoryMock);

        // Act
        $resultOrderTransfer = $sspAssetOrderExpanderPlugin->hydrate($orderTransfer);

        // Assert
        $this->assertNotNull($resultOrderTransfer->getItems()[0]->getSspAsset());
        $this->assertSame(
            SspAssetManagementCommunicationTester::TEST_ASSET_REFERENCE,
            $resultOrderTransfer->getItems()[0]->getSspAssetOrFail()->getReferenceOrFail(),
        );
        $this->assertSame(
            SspAssetManagementCommunicationTester::TEST_ASSET_NAME,
            $resultOrderTransfer->getItems()[0]->getSspAssetOrFail()->getNameOrFail(),
        );
        $this->assertSame(
            SspAssetManagementCommunicationTester::TEST_ASSET_SERIAL_NUMBER,
            $resultOrderTransfer->getItems()[0]->getSspAssetOrFail()->getSerialNumberOrFail(),
        );
        $this->assertNotNull($resultOrderTransfer->getItems()[1]->getSspAsset());
    }

    /**
     * @return void
     */
    public function testHydrateDoesNothingWhenNoItemsProvided(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createEmptyOrderTransfer();

        $sspAssetManagementRepositoryMock = $this->createMock(SspAssetManagementRepositoryInterface::class);
        $sspAssetManagementRepositoryMock->expects($this->never())
            ->method('getSspAssetsIndexedByIdSalesOrderItem');

        $factoryMock = $this->createMock(SspAssetManagementCommunicationFactory::class);
        $factoryMock->method('createOrderItemSspAssetExpander')
            ->willReturn(new OrderItemSspAssetExpander($sspAssetManagementRepositoryMock, new SalesOrderItemIdExtractor()));

        $sspAssetOrderExpanderPlugin = new SspAssetOrderExpanderPlugin();
        $sspAssetOrderExpanderPlugin->setFactory($factoryMock);

        // Act
        $resultOrderTransfer = $sspAssetOrderExpanderPlugin->hydrate($orderTransfer);

        // Assert
        $this->assertCount(0, $resultOrderTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testHydrateDoesNothingWhenNoAssetsAssociatedWithItem(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderTransferWithItems();

        $sspAssetManagementRepositoryMock = $this->createMock(SspAssetManagementRepositoryInterface::class);
        $sspAssetManagementRepositoryMock->method('getSspAssetsIndexedByIdSalesOrderItem')
            ->willReturn([]);

        $factoryMock = $this->createMock(SspAssetManagementCommunicationFactory::class);
        $factoryMock->method('createOrderItemSspAssetExpander')
            ->willReturn(new OrderItemSspAssetExpander($sspAssetManagementRepositoryMock, new SalesOrderItemIdExtractor()));

        $sspAssetOrderExpanderPlugin = new SspAssetOrderExpanderPlugin();
        $sspAssetOrderExpanderPlugin->setFactory($factoryMock);

        // Act
        $resultOrderTransfer = $sspAssetOrderExpanderPlugin->hydrate($orderTransfer);

        // Assert
        $this->assertNull($resultOrderTransfer->getItems()[0]->getSspAsset());
        $this->assertNull($resultOrderTransfer->getItems()[1]->getSspAsset());
    }
}
