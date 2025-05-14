<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspAssetManagement\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use SprykerFeature\Zed\SspAssetManagement\Business\Expander\SspAssetItemExpander;
use SprykerFeature\Zed\SspAssetManagement\Communication\Plugin\Cart\SspAssetItemExpanderPlugin;
use SprykerFeature\Zed\SspAssetManagement\Communication\SspAssetManagementCommunicationFactory;
use SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface;
use SprykerFeatureTest\Zed\SspAssetManagement\SspAssetManagementCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspAssetManagement
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group SspAssetItemExpanderPluginTest
 */
class SspAssetItemExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspAssetManagement\SspAssetManagementCommunicationTester
     */
    protected SspAssetManagementCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandItemsExpandsItemsWithSspAssetWhenSspAssetReferenceProvided(): void
    {
        // Arrange
        $sspAssetCollectionTransfer = $this->tester->createSspAssetCollectionTransfer();

        $sspAssetManagementRepositoryMock = $this->createMock(SspAssetManagementRepositoryInterface::class);
        $sspAssetManagementRepositoryMock->method('getSspAssetCollection')
            ->willReturn($sspAssetCollectionTransfer);

        $factoryMock = $this->createMock(SspAssetManagementCommunicationFactory::class);
        $factoryMock->method('createSspAssetItemExpander')
            ->willReturn(new SspAssetItemExpander($sspAssetManagementRepositoryMock));

        $sspAssetItemExpanderPlugin = new SspAssetItemExpanderPlugin();
        $sspAssetItemExpanderPlugin->setFactory($factoryMock);

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithSspAsset(
            SspAssetManagementCommunicationTester::TEST_ASSET_REFERENCE,
        );

        // Act
        $resultCartChangeTransfer = $sspAssetItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getSspAsset());
        $this->assertSame(
            SspAssetManagementCommunicationTester::TEST_ASSET_REFERENCE,
            $itemTransfer->getSspAssetOrFail()->getReferenceOrFail(),
        );
        $this->assertSame(
            SspAssetManagementCommunicationTester::TEST_ASSET_NAME,
            $itemTransfer->getSspAssetOrFail()->getNameOrFail(),
        );
        $this->assertSame(
            SspAssetManagementCommunicationTester::TEST_ASSET_SERIAL_NUMBER,
            $itemTransfer->getSspAssetOrFail()->getSerialNumberOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testExpandItemsDoesNothingWhenNoSspAssetReferenceProvided(): void
    {
        // Arrange
        $sspAssetManagementRepositoryMock = $this->createMock(SspAssetManagementRepositoryInterface::class);
        $sspAssetManagementRepositoryMock->expects($this->never())
            ->method('getSspAssetCollection');

        $factoryMock = $this->createMock(SspAssetManagementCommunicationFactory::class);
        $factoryMock->method('createSspAssetItemExpander')
            ->willReturn(new SspAssetItemExpander($sspAssetManagementRepositoryMock));

        $sspAssetItemExpanderPlugin = new SspAssetItemExpanderPlugin();
        $sspAssetItemExpanderPlugin->setFactory($factoryMock);

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
        $sspAssetManagementRepositoryMock = $this->createMock(SspAssetManagementRepositoryInterface::class);
        $sspAssetManagementRepositoryMock->expects($this->never())
            ->method('getSspAssetCollection');

        $factoryMock = $this->createMock(SspAssetManagementCommunicationFactory::class);
        $factoryMock->method('createSspAssetItemExpander')
            ->willReturn(new SspAssetItemExpander($sspAssetManagementRepositoryMock));

        $sspAssetItemExpanderPlugin = new SspAssetItemExpanderPlugin();
        $sspAssetItemExpanderPlugin->setFactory($factoryMock);

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
        $sspAssetManagementRepositoryMock = $this->createMock(SspAssetManagementRepositoryInterface::class);
        $sspAssetManagementRepositoryMock->method('getSspAssetCollection')
            ->willReturn(new SspAssetCollectionTransfer());

        $factoryMock = $this->createMock(SspAssetManagementCommunicationFactory::class);
        $factoryMock->method('createSspAssetItemExpander')
            ->willReturn(new SspAssetItemExpander($sspAssetManagementRepositoryMock));

        $sspAssetItemExpanderPlugin = new SspAssetItemExpanderPlugin();
        $sspAssetItemExpanderPlugin->setFactory($factoryMock);

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithSspAsset(
            SspAssetManagementCommunicationTester::TEST_ASSET_REFERENCE,
        );

        // Act
        $resultCartChangeTransfer = $sspAssetItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $sspAssetTransfer = $itemTransfer->getSspAsset();
        $this->assertNotNull($sspAssetTransfer);
        $this->assertSame(SspAssetManagementCommunicationTester::TEST_ASSET_REFERENCE, $sspAssetTransfer->getReference());
        $this->assertNull($sspAssetTransfer->getName());
    }
}
