<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetStorage\Communication\Plugin\Publisher\Asset;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Asset\AssetConfig;
use Spryker\Zed\AssetStorage\Communication\Plugin\Publisher\Asset\AssetDeletePublisherPlugin;
use SprykerTest\Zed\AssetStorage\AssetStorageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AssetStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group Asset
 * @group AssetDeletePublisherTest
 * Add your own group annotations below this line
 */
class AssetDeletePublisherTest extends Unit
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
        $assetDeletePublisherPlugin = new AssetDeletePublisherPlugin();
        $assetDeletePublisherPlugin->setFacade($this->tester->getFacade());

        $eventTransfer = (new EventEntityTransfer())
            ->setId($this->assetTransfer->getIdAsset())
            ->setAdditionalValues($this->assetTransfer->toArray());

        // Act
        $assetDeletePublisherPlugin->handleBulk(
            [$eventTransfer],
            AssetConfig::ASSET_UNPUBLISH,
        );

        // Assert
        $this->tester->assertAssetStorage([]);
    }

    /**
     * @return void
     */
    public function testHandleWithWrongAssetIdReturnsDataWithoutChanges(): void
    {
        // Arrange
        $assetDeletePublisherPlugin = new AssetDeletePublisherPlugin();
        $assetDeletePublisherPlugin->setFacade($this->tester->getFacade());

        $assetTransfer = $this->tester->haveAssetTransfer([
            'idAsset' => 10,
            'stores' => AssetStorageCommunicationTester::STORE_NAMES_DEFAULT,
        ]);

        $eventTransfer = (new EventEntityTransfer())
            ->setId($assetTransfer->getIdAsset())
            ->setAdditionalValues($assetTransfer->toArray());

        // Act
        $assetDeletePublisherPlugin->handleBulk(
            [$eventTransfer],
            AssetConfig::ASSET_UNPUBLISH,
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
            'asset_slot:at:header-test' => [
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

    /**
     * @return void
     */
    public function testRemovesAssetDataOfAnotherAssetForCurrentSlot(): void
    {
        // Arrange
        $assetDeletePublisherPlugin = new AssetDeletePublisherPlugin();
        $assetDeletePublisherPlugin->setFacade($this->tester->getFacade());

        $sameSlotAssetTransfer = $this->tester->haveAssetTransfer([
            'idAsset' => 20,
            'stores' => AssetStorageCommunicationTester::STORE_NAMES_DEFAULT,
        ]);
        $this->tester->haveAssetSlotStorageForAssetTransfer($sameSlotAssetTransfer);

        $eventTransfer2 = (new EventEntityTransfer())
            ->setId($sameSlotAssetTransfer->getIdAsset())
            ->setAdditionalValues($sameSlotAssetTransfer->toArray());

        // Assert
        $this->tester->assertAssetStorage([
            'asset_slot:de:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [
                    [
                        'assetId' => $this->assetTransfer->getIdAsset(),
                        'assetUuid' => $this->assetTransfer->getAssetUuid(),
                        'assetContent' => $this->assetTransfer->getAssetContent(),
                    ],
                    [
                        'assetId' => $sameSlotAssetTransfer->getIdAsset(),
                        'assetUuid' => $sameSlotAssetTransfer->getAssetUuid(),
                        'assetContent' => $sameSlotAssetTransfer->getAssetContent(),
                    ],
                ],
            ],
            'asset_slot:at:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [
                    [
                        'assetId' => $this->assetTransfer->getIdAsset(),
                        'assetUuid' => $this->assetTransfer->getAssetUuid(),
                        'assetContent' => $this->assetTransfer->getAssetContent(),
                    ],
                    [
                        'assetId' => $sameSlotAssetTransfer->getIdAsset(),
                        'assetUuid' => $sameSlotAssetTransfer->getAssetUuid(),
                        'assetContent' => $sameSlotAssetTransfer->getAssetContent(),
                    ],
                ],
            ],
        ]);

        // Act
        $assetDeletePublisherPlugin->handleBulk(
            [$eventTransfer2],
            AssetConfig::ASSET_UNPUBLISH,
        );

        // Assert
        $this->tester->assertAssetStorage([
            'asset_slot:de:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [
                    [
                        'assetId' => $this->assetTransfer->getIdAsset(),
                        'assetUuid' => $this->assetTransfer->getAssetUuid(),
                        'assetContent' => $this->assetTransfer->getAssetContent(),
                    ],
                ],
            ],
            'asset_slot:at:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [
                    [
                        'assetId' => $this->assetTransfer->getIdAsset(),
                        'assetUuid' => $this->assetTransfer->getAssetUuid(),
                        'assetContent' => $this->assetTransfer->getAssetContent(),
                    ],
                ],
            ],
        ]);
    }
}
