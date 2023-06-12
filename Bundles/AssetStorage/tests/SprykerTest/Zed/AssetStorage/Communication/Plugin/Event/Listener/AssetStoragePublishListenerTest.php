<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetStorage\Comminication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Asset\Dependency\AssetEvents;
use Spryker\Zed\AssetStorage\Communication\Plugin\Event\Listener\AssetStoragePublishListener;
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
 * @group AssetStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class AssetStoragePublishListenerTest extends Unit
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
            AssetTransfer::ASSET_SLOT => AssetStorageCommunicationTester::ASSET_SLOT_DEFAULT,
        ]);
        $this->tester->mockAssetFacade($this->assetTransfer);
    }

    /**
     * @return void
     */
    public function testHandleWithCorrectDataSuccessfully(): void
    {
        // Arrange
        $assetStoragePublishListener = new AssetStoragePublishListener();
        $assetStoragePublishListener->setFacade($this->tester->getFacade());

        $eventTransfer = (new EventEntityTransfer())->setId($this->assetTransfer->getIdAsset());

        // Act
        $assetStoragePublishListener->handle(
            $eventTransfer,
            AssetEvents::ENTITY_SPY_ASSET_CREATE,
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
}
