<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\OrderItemSspAssetExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Extractor\SalesOrderItemIdExtractor;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\SspAssetOrderExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group SspAssetOrderExpanderPluginTest
 */
class SspAssetOrderExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    public function testHydrateExpandsOrderItemsWithSspAssets(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderTransferWithItems();
        $sspAssetTransfersIndexedBySalesOrderItemId = $this->tester->createSspAssetTransfersIndexedBySalesOrderItemId();

        $sspAssetReaderMock = $this->createMock(SspAssetReaderInterface::class);
        $sspAssetReaderMock->method('indexSspAssetsBySalesOrderItemIds')
            ->with([1, 2])
            ->willReturn($sspAssetTransfersIndexedBySalesOrderItemId);

        $businessFactoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $businessFactoryMock->method('createOrderItemSspAssetExpander')
            ->willReturn(new OrderItemSspAssetExpander($sspAssetReaderMock, new SalesOrderItemIdExtractor()));

        $sspAssetOrderExpanderPlugin = new SspAssetOrderExpanderPlugin();
        $sspAssetOrderExpanderPlugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultOrderTransfer = $sspAssetOrderExpanderPlugin->hydrate($orderTransfer);

        // Assert
        $this->assertNotNull($resultOrderTransfer->getItems()[0]->getSspAsset());
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_REFERENCE,
            $resultOrderTransfer->getItems()[0]->getSspAssetOrFail()->getReferenceOrFail(),
        );
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_NAME,
            $resultOrderTransfer->getItems()[0]->getSspAssetOrFail()->getNameOrFail(),
        );
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_SERIAL_NUMBER,
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

        $sspAssetReaderMock = $this->createMock(SspAssetReaderInterface::class);
        $sspAssetReaderMock->expects($this->never())
            ->method('indexSspAssetsBySalesOrderItemIds');

        $businessFactoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $businessFactoryMock->method('createOrderItemSspAssetExpander')
            ->willReturn(new OrderItemSspAssetExpander($sspAssetReaderMock, new SalesOrderItemIdExtractor()));

        $sspAssetOrderExpanderPlugin = new SspAssetOrderExpanderPlugin();
        $sspAssetOrderExpanderPlugin->setBusinessFactory($businessFactoryMock);

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

        $sspAssetReaderMock = $this->createMock(SspAssetReaderInterface::class);
        $sspAssetReaderMock->method('indexSspAssetsBySalesOrderItemIds')
            ->willReturn([]);

        $businessFactoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $businessFactoryMock->method('createOrderItemSspAssetExpander')
            ->willReturn(new OrderItemSspAssetExpander($sspAssetReaderMock, new SalesOrderItemIdExtractor()));

        $sspAssetOrderExpanderPlugin = new SspAssetOrderExpanderPlugin();
        $sspAssetOrderExpanderPlugin->setBusinessFactory($businessFactoryMock);

        // Act
        $resultOrderTransfer = $sspAssetOrderExpanderPlugin->hydrate($orderTransfer);

        // Assert
        $this->assertNull($resultOrderTransfer->getItems()[0]->getSspAsset());
        $this->assertNull($resultOrderTransfer->getItems()[1]->getSspAsset());
    }
}
