<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetStorage\Comminication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Asset\Dependency\AssetEvents;
use Spryker\Zed\AssetStorage\AssetStorageConfig;
use Spryker\Zed\AssetStorage\Communication\Exception\NoForeignKeyException;
use Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener\AssetStoreStorageUnpublishListener;
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
 * @group AssetStoreStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class AssetStoreStorageUnpublishListenerTest extends Unit
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
     * @var array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected $storeTransfers = [];

    /**
     * @var \Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener\AssetStoreStoragePublishListener
     */
    protected $assetStoreStorageUnpublishListener;

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
        $this->tester->mockAssetFacade($this->assetTransfer);

        foreach (AssetStorageCommunicationTester::STORE_NAMES_DEFAULT as $storeName) {
            $this->storeTransfers[$storeName] = $this->tester->haveStore([
                'name' => $storeName,
            ]);
        }

        $this->assetStoreStorageUnpublishListener = (new AssetStoreStorageUnpublishListener())
            ->setFacade($this->tester->getFacade());
    }

    /**
     * @return void
     */
    public function testHandleWithCorrectDataSuccessfully(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())
            ->setForeignKeys([
            AssetStorageConfig::COL_FK_STORE => $this
                ->storeTransfers[AssetStorageCommunicationTester::STORE_NAME_DE]
                ->getIdStore(),
            AssetStorageConfig::COL_FK_ASSET => $this->assetTransfer->getIdAsset(),
        ]);

        $this->tester->haveAssetSlotStorageForAssetTransfer($this->assetTransfer);

        // Act
        $this->assetStoreStorageUnpublishListener->handle(
            $eventTransfer,
            AssetEvents::ENTITY_SPY_ASSET_STORE_DELETE,
        );

        // Assert
        $this->tester->assertAssetStorage([
            'asset_slot:de:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [],
            ],
            'asset_slot:en:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [[
                    'assetId' => $this->assetTransfer->getIdAsset(),
                    'assetUuid' => $this->assetTransfer->getAssetUuid(),
                    'assetContent' => $this->assetTransfer->getAssetContent(),
                ]],
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testHandleWithoutFkStoreThrowsException(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())->setForeignKeys([
            AssetStorageConfig::COL_FK_ASSET => $this->assetTransfer->getIdAsset(),
        ]);

        $this->expectException(NoForeignKeyException::class);

        // Act
        $this->assetStoreStorageUnpublishListener->handle(
            $eventTransfer,
            AssetEvents::ENTITY_SPY_ASSET_STORE_CREATE,
        );
    }

    /**
     * @return void
     */
    public function testHandleWithoutFkAssetThrowsException(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())->setForeignKeys([
            AssetStorageConfig::COL_FK_STORE => $this
                ->storeTransfers[AssetStorageCommunicationTester::STORE_NAME_DE]
                ->getIdStore(),
        ]);

        $this->expectException(NoForeignKeyException::class);

        // Act
        $this->assetStoreStorageUnpublishListener->handle(
            $eventTransfer,
            AssetEvents::ENTITY_SPY_ASSET_STORE_CREATE,
        );
    }
}
