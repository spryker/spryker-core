<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetStorage\Communication\Plugin\Publisher\Asset;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Asset\AssetConfig;
use Spryker\Zed\AssetStorage\Communication\Plugin\Publisher\Asset\AssetWritePublisherPlugin;
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
 * @group AssetWritePublisherTest
 * Add your own group annotations below this line
 */
class AssetWritePublisherTest extends Unit
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
        $this->tester->mockAssetFacade($this->assetTransfer);
    }

    /**
     * @return void
     */
    public function testHandleWithCorrectDataSuccessfully(): void
    {
        // Arrange
        $assetWritePublisherPlugin = new AssetWritePublisherPlugin();
        $assetWritePublisherPlugin->setFacade($this->tester->getFacade());

        $eventTransfer = (new EventEntityTransfer())
            ->setId($this->assetTransfer->getIdAsset())
            ->setAdditionalValues($this->assetTransfer->toArray());

        // Act
        $assetWritePublisherPlugin->handleBulk(
            [$eventTransfer],
            AssetConfig::ASSET_PUBLISH,
        );

        // Assert
        $this->tester->assertAssetStorage([
            'asset_slot:de:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [[
                    'assetId' => $this->assetTransfer->getIdAsset(),
                    'assetUuid' => $this->assetTransfer->getAssetUuid(),
                    'assetContent' => $this->assetTransfer->getAssetContent(),
                ]],
            ],
            'asset_slot:at:header-test' => [
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
    public function testRemovesAssetDataIfStoreWasDeleted(): void
    {
        // Arrange
        $assetWritePublisherPlugin = new AssetWritePublisherPlugin();
        $assetWritePublisherPlugin->setFacade($this->tester->getFacade());

        $this->assetTransfer->setStores(['DE']);

        $eventTransfer = (new EventEntityTransfer())
            ->setId($this->assetTransfer->getIdAsset())
            ->setAdditionalValues($this->assetTransfer->toArray());

        // Act
        $assetWritePublisherPlugin->handleBulk(
            [$eventTransfer],
            AssetConfig::ASSET_PUBLISH,
        );

        // Assert
        $this->tester->assertAssetStorage([
            'asset_slot:de:header-test' => [
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
    public function testAddsAssetDataIfStoreWasAdded(): void
    {
        // Arrange
        $assetWritePublisherPlugin = new AssetWritePublisherPlugin();
        $assetWritePublisherPlugin->setFacade($this->tester->getFacade());

        $this->assetTransfer->setStores(['DE', 'AT']);

        $eventTransfer = (new EventEntityTransfer())
            ->setId($this->assetTransfer->getIdAsset())
            ->setAdditionalValues($this->assetTransfer->toArray());

        // Act
        $assetWritePublisherPlugin->handleBulk(
            [$eventTransfer],
            AssetConfig::ASSET_PUBLISH,
        );

        // Assert
        $this->tester->assertAssetStorage([
            'asset_slot:de:header-test' => [
                'assetSlot' => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
                'assets' => [[
                    'assetId' => $this->assetTransfer->getIdAsset(),
                    'assetUuid' => $this->assetTransfer->getAssetUuid(),
                    'assetContent' => $this->assetTransfer->getAssetContent(),
                ]],
            ],
            'asset_slot:at:header-test' => [
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
    public function testRemovesAssetDataIfSlotWasChanged(): void
    {
        // Arrange
        $assetWritePublisherPlugin = new AssetWritePublisherPlugin();
        $assetWritePublisherPlugin->setFacade($this->tester->getFacade());

        $accessSlot = 'another-slot';
        $this->assetTransfer->setAssetSlot($accessSlot);

        $eventTransfer = (new EventEntityTransfer())
            ->setId($this->assetTransfer->getIdAsset())
            ->setAdditionalValues($this->assetTransfer->toArray());

        // Act
        $assetWritePublisherPlugin->handleBulk(
            [$eventTransfer],
            AssetConfig::ASSET_PUBLISH,
        );

        // Assert
        $this->tester->assertAssetStorage([
            sprintf('asset_slot:de:%s', $accessSlot) => [
                'assetSlot' => $accessSlot,
                'assets' => [[
                    'assetId' => $this->assetTransfer->getIdAsset(),
                    'assetUuid' => $this->assetTransfer->getAssetUuid(),
                    'assetContent' => $this->assetTransfer->getAssetContent(),
                ]],
            ],
            sprintf('asset_slot:at:%s', $accessSlot) => [
                'assetSlot' => $accessSlot,
                'assets' => [[
                    'assetId' => $this->assetTransfer->getIdAsset(),
                    'assetUuid' => $this->assetTransfer->getAssetUuid(),
                    'assetContent' => $this->assetTransfer->getAssetContent(),
                ]],
            ],
        ], $accessSlot);
    }

    /**
     * @return void
     */
    public function testAddsAssetDataIfThereIsAnotherAssetForCurrentSlot(): void
    {
        // Arrange
        $assetWritePublisherPlugin = new AssetWritePublisherPlugin();
        $assetWritePublisherPlugin->setFacade($this->tester->getFacade());

        $eventTransfer1 = (new EventEntityTransfer())
            ->setId($this->assetTransfer->getIdAsset())
            ->setAdditionalValues($this->assetTransfer->toArray());

        $sameSlotAssetTransfer = $this->tester->haveAssetTransfer([
            'idAsset' => 20,
            'stores' => AssetStorageCommunicationTester::STORE_NAMES_DEFAULT,
        ]);

        $eventTransfer2 = (new EventEntityTransfer())
            ->setId($sameSlotAssetTransfer->getIdAsset())
            ->setAdditionalValues($sameSlotAssetTransfer->toArray());

        // Act
        $assetWritePublisherPlugin->handleBulk(
            [$eventTransfer1, $eventTransfer2],
            AssetConfig::ASSET_PUBLISH,
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
    }
}
