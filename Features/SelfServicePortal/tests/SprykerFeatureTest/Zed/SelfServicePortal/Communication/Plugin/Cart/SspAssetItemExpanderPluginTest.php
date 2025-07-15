<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\SspAssetItemExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReader;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Cart\SspAssetItemExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group SspAssetItemExpanderPluginTest
 */
class SspAssetItemExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandItemsExpandsItemsWithSspAssetWhenSspAssetReferenceProvided(): void
    {
        // Arrange
        $sspAssetCollectionTransfer = $this->tester->createSspAssetCollectionTransfer();

        $sspAssetReaderMock = $this->createMock(SspAssetReader::class);
        $sspAssetReaderMock->method('getSspAssetCollection')
            ->willReturn($sspAssetCollectionTransfer);

        $businessFactoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $businessFactoryMock->method('createSspAssetItemExpander')
            ->willReturn(new SspAssetItemExpander($sspAssetReaderMock));

        $sspAssetItemExpanderPlugin = new SspAssetItemExpanderPlugin();
        $sspAssetItemExpanderPlugin->setBusinessFactory($businessFactoryMock);

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithSspAsset(
            SelfServicePortalCommunicationTester::TEST_ASSET_REFERENCE,
        );

        // Act
        $resultCartChangeTransfer = $sspAssetItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getSspAsset());
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_REFERENCE,
            $itemTransfer->getSspAssetOrFail()->getReferenceOrFail(),
        );
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_NAME,
            $itemTransfer->getSspAssetOrFail()->getNameOrFail(),
        );
        $this->assertSame(
            SelfServicePortalCommunicationTester::TEST_ASSET_SERIAL_NUMBER,
            $itemTransfer->getSspAssetOrFail()->getSerialNumberOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testExpandItemsDoesNothingWhenNoSspAssetReferenceProvided(): void
    {
        // Arrange
        $sspAssetReaderMock = $this->createMock(SspAssetReader::class);
        $sspAssetReaderMock->expects($this->never())
            ->method('getSspAssetCollection');

        $businessFactoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $businessFactoryMock->method('createSspAssetItemExpander')
            ->willReturn(new SspAssetItemExpander($sspAssetReaderMock));

        $sspAssetItemExpanderPlugin = new SspAssetItemExpanderPlugin();
        $sspAssetItemExpanderPlugin->setBusinessFactory($businessFactoryMock);

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithoutSspAsset();

        // Act
        $resultCartChangeTransfer = $sspAssetItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNull($itemTransfer->getSspAsset());
    }

    /**
     * @return void
     */
    public function testExpandItemsDoesNothingWhenNoItemsProvided(): void
    {
        // Arrange
        $sspAssetReaderMock = $this->createMock(SspAssetReader::class);
        $sspAssetReaderMock->expects($this->never())
            ->method('getSspAssetCollection');

        $businessFactoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $businessFactoryMock->method('createSspAssetItemExpander')
            ->willReturn(new SspAssetItemExpander($sspAssetReaderMock));

        $sspAssetItemExpanderPlugin = new SspAssetItemExpanderPlugin();
        $sspAssetItemExpanderPlugin->setBusinessFactory($businessFactoryMock);

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();

        // Act
        $resultCartChangeTransfer = $sspAssetItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $this->assertCount(0, $resultCartChangeTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testExpandItemsDoesNothingWhenRepositoryReturnsNoResults(): void
    {
        // Arrange
        $sspAssetReaderMock = $this->createMock(SspAssetReader::class);
        $sspAssetReaderMock->method('getSspAssetCollection')
            ->willReturn(new SspAssetCollectionTransfer());

        $businessFactoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $businessFactoryMock->method('createSspAssetItemExpander')
            ->willReturn(new SspAssetItemExpander($sspAssetReaderMock));

        $sspAssetItemExpanderPlugin = new SspAssetItemExpanderPlugin();
        $sspAssetItemExpanderPlugin->setBusinessFactory($businessFactoryMock);

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithSspAsset(
            SelfServicePortalCommunicationTester::TEST_ASSET_REFERENCE,
        );

        // Act
        $resultCartChangeTransfer = $sspAssetItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $sspAssetTransfer = $itemTransfer->getSspAsset();
        $this->assertNotNull($sspAssetTransfer);
        $this->assertSame(SelfServicePortalCommunicationTester::TEST_ASSET_REFERENCE, $sspAssetTransfer->getReference());
        $this->assertNull($sspAssetTransfer->getName());
    }
}
