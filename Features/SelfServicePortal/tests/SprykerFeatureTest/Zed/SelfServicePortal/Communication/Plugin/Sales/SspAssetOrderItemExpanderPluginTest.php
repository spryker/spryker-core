<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\OrderItemSspAssetExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Extractor\SalesOrderItemIdExtractor;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\SspAssetOrderItemExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group SspAssetOrderItemExpanderPluginTest
 */
class SspAssetOrderItemExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function testExpandExpandsItemsWithSspAssets(): void
    {
        // Arrange
        $itemTransfers = $this->createItemTransfers();
        $sspAssetTransfersIndexedBySalesOrderItemId = $this->tester->createSspAssetTransfersIndexedBySalesOrderItemId();

        $sspAssetReaderMock = $this->createMock(SspAssetReaderInterface::class);
        $sspAssetReaderMock->method('getSspAssetsIndexedBySalesOrderItemIds')
            ->with([1, 2])
            ->willReturn($sspAssetTransfersIndexedBySalesOrderItemId);

        $businessFactoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $businessFactoryMock->method('createOrderItemSspAssetExpander')
            ->willReturn(new OrderItemSspAssetExpander($sspAssetReaderMock, new SalesOrderItemIdExtractor()));

        $sspAssetOrderItemExpanderPlugin = new SspAssetOrderItemExpanderPlugin();
        $sspAssetOrderItemExpanderPlugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultItemTransfers = $sspAssetOrderItemExpanderPlugin->expand($itemTransfers);

        // Assert
        $this->assertCount(2, $resultItemTransfers);
        $this->assertNotNull($resultItemTransfers[0]->getSspAsset());
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_REFERENCE,
            $resultItemTransfers[0]->getSspAssetOrFail()->getReferenceOrFail(),
        );
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_NAME,
            $resultItemTransfers[0]->getSspAssetOrFail()->getNameOrFail(),
        );
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_SERIAL_NUMBER,
            $resultItemTransfers[0]->getSspAssetOrFail()->getSerialNumberOrFail(),
        );
        $this->assertNotNull($resultItemTransfers[1]->getSspAsset());
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_REFERENCE_2,
            $resultItemTransfers[1]->getSspAssetOrFail()->getReferenceOrFail(),
        );
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_NAME_2,
            $resultItemTransfers[1]->getSspAssetOrFail()->getNameOrFail(),
        );
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_SERIAL_NUMBER_2,
            $resultItemTransfers[1]->getSspAssetOrFail()->getSerialNumberOrFail(),
        );
    }

    public function testExpandDoesNothingWhenNoItemsProvided(): void
    {
        // Arrange
        $itemTransfers = [];

        $sspAssetReaderMock = $this->createMock(SspAssetReaderInterface::class);
        $sspAssetReaderMock->expects($this->never())
            ->method('getSspAssetsIndexedBySalesOrderItemIds');

        $businessFactoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $businessFactoryMock->method('createOrderItemSspAssetExpander')
            ->willReturn(new OrderItemSspAssetExpander($sspAssetReaderMock, new SalesOrderItemIdExtractor()));

        $sspAssetOrderItemExpanderPlugin = new SspAssetOrderItemExpanderPlugin();
        $sspAssetOrderItemExpanderPlugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultItemTransfers = $sspAssetOrderItemExpanderPlugin->expand($itemTransfers);

        // Assert
        $this->assertCount(0, $resultItemTransfers);
    }

    public function testExpandDoesNothingWhenNoAssetsAssociatedWithItems(): void
    {
        // Arrange
        $itemTransfers = $this->createItemTransfers();

        $sspAssetReaderMock = $this->createMock(SspAssetReaderInterface::class);
        $sspAssetReaderMock->method('getSspAssetsIndexedBySalesOrderItemIds')
            ->willReturn([]);

        $businessFactoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $businessFactoryMock->method('createOrderItemSspAssetExpander')
            ->willReturn(new OrderItemSspAssetExpander($sspAssetReaderMock, new SalesOrderItemIdExtractor()));

        $sspAssetOrderItemExpanderPlugin = new SspAssetOrderItemExpanderPlugin();
        $sspAssetOrderItemExpanderPlugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultItemTransfers = $sspAssetOrderItemExpanderPlugin->expand($itemTransfers);

        // Assert
        $this->assertCount(2, $resultItemTransfers);
        $this->assertNull($resultItemTransfers[0]->getSspAsset());
        $this->assertNull($resultItemTransfers[1]->getSspAsset());
    }

    /**
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function createItemTransfers(): array
    {
        $itemTransfer1 = new ItemTransfer();
        $itemTransfer1->setIdSalesOrderItem(1);

        $itemTransfer2 = new ItemTransfer();
        $itemTransfer2->setIdSalesOrderItem(2);

        return [$itemTransfer1, $itemTransfer2];
    }
}
