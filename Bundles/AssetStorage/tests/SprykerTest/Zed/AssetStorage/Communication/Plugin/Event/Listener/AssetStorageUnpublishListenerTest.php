<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetStorage\Comminication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Asset\Dependency\AssetEvents;
use Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener\AssetStorageUnpublishListener;
use SprykerTest\Zed\AssetStorage\AssetStorageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AssetStorage
 * @group Comminication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group AssetStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class AssetStorageUnpublishListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AssetStorage\AssetStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\AssetTransfer
     */
    protected $assetTransfer;

    /**
     * @var \Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener\AssetStorageUnpublishListener
     */
    protected $assetStorageUnpublishListener;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->assetTransfer = $this->tester->haveAssetTransfer([
            'idAsset' => AssetStorageCommunicationTester::ID_ASSET_DEFAULT,
            'stores' => AssetStorageCommunicationTester::STORE_NAMES_DEFAULT,
        ]);
        $this->tester->haveAssetSlotStorageForAssetTransfer($this->assetTransfer);
    }

    /**
     * @return void
     */
    public function testHandleWithCorrectDataSuccessfully(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())
            ->setId($this->assetTransfer->getIdAsset());

        $this->tester->mockAssetFacade($this->assetTransfer);
        $this->assetStorageUnpublishListener = (new AssetStorageUnpublishListener())
            ->setFacade($this->tester->getFacade());

        // Act
        $this->assetStorageUnpublishListener->handle(
            $eventTransfer,
            AssetEvents::ENTITY_SPY_ASSET_DELETE,
        );

        // Assert
        $this->tester->assertAssetStorage([
            'asset_slot:de:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [],
            ],
            'asset_slot:en:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [],
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testHandleWithWrongAssetIdReturnsDataWithoutChanges(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())
            ->setId(10);

        $this->tester->mockAssetFacade(null);
        $this->assetStorageUnpublishListener = (new AssetStorageUnpublishListener())
            ->setFacade($this->tester->getFacade());

        // Act
        $this->assetStorageUnpublishListener->handle(
            $eventTransfer,
            AssetEvents::ENTITY_SPY_ASSET_DELETE,
        );

        // Assert
        $this->tester->assertAssetStorage([
            'asset_slot:de:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [
                    [
                        AssetStorageCommunicationTester::ASSET_ID_DATA_KEY => $this->assetTransfer->getIdAsset(),
                        AssetStorageCommunicationTester::ASSET_UUID_DATA_KEY => $this->assetTransfer->getAssetUuid(),
                        AssetStorageCommunicationTester::ASSET_CONTENT_DATA_KEY => $this->assetTransfer->getAssetContent(),
                    ],
                ],
            ],
            'asset_slot:en:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [
                    [
                        AssetStorageCommunicationTester::ASSET_ID_DATA_KEY => $this->assetTransfer->getIdAsset(),
                        AssetStorageCommunicationTester::ASSET_UUID_DATA_KEY => $this->assetTransfer->getAssetUuid(),
                        AssetStorageCommunicationTester::ASSET_CONTENT_DATA_KEY => $this->assetTransfer->getAssetContent(),

                    ],
                ],
            ],
        ]);
    }
}
