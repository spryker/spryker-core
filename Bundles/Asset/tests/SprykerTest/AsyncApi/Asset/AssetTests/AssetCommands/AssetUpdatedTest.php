<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\Asset\AssetTests\AssetCommands;

use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use SprykerTest\AsyncApi\Asset\AsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group Asset
 * @group AssetTests
 * @group AssetCommands
 * @group AssetUpdatedTest
 * Add your own group annotations below this line
 */
class AssetUpdatedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\Asset\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testAssetUpdatedMessageUpdatesAssetSlot(): void
    {
        // Arrange
        $assetUuid = Uuid::uuid4()->toString();
        $assetTransfer = $this->tester->haveAsset(['assetUuid' => $assetUuid]);
        $assetTransfer->setAssetSlot('updated-slot');

        $assetData = $assetTransfer->toArray(true, true);
        // TODO Wha do we have the different keys and thus require mapping here??
        $assetData['assetIdentifier'] = $assetTransfer->getAssetUuid();
        $assetData['assetView'] = $assetTransfer->getAssetContent();

        $assetUpdatedTransfer = $this->tester->haveAssetUpdatedTransfer($assetData);

        // Act
        $this->tester->runMessageReceiveTest($assetUpdatedTransfer, 'asset-commands');

        // Assert
        $this->tester->assertAssetWithUuidEquals($assetUuid, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testAssetUpdatedMessageUpdatesAssetContent(): void
    {
        // Arrange
        $assetUuid = Uuid::uuid4()->toString();
        $assetTransfer = $this->tester->haveAsset(['assetUuid' => $assetUuid]);
        $assetTransfer->setAssetContent('updated-content');

        $assetData = $assetTransfer->toArray(true, true);
        // TODO Wha do we have the different keys and thus require mapping here??
        $assetData['assetIdentifier'] = $assetTransfer->getAssetUuid();
        $assetData['assetView'] = $assetTransfer->getAssetContent();

        $assetUpdatedTransfer = $this->tester->haveAssetUpdatedTransfer($assetData);

        // Act
        $this->tester->runMessageReceiveTest($assetUpdatedTransfer, 'asset-commands');

        // Assert
        $this->tester->assertAssetWithUuidEquals($assetUuid, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testAssetUpdatedMessageDoesNotUpdateAssetName(): void
    {
        // Arrange
        $assetUuid = Uuid::uuid4()->toString();
        $assetTransfer = $this->tester->haveAsset(['assetUuid' => $assetUuid]);

        $assetData = $assetTransfer->toArray(true, true);
        // TODO Wha do we have the different keys and thus require mapping here??
        $assetData['assetIdentifier'] = $assetTransfer->getAssetUuid();
        $assetData['assetView'] = $assetTransfer->getAssetContent();
        $assetData['assetName'] = 'updated-name';

        $assetUpdatedTransfer = $this->tester->haveAssetUpdatedTransfer($assetData);

        // Act
        $this->tester->runMessageReceiveTest($assetUpdatedTransfer, 'asset-commands');

        // Assert
        $this->tester->assertAssetWithUuidEquals($assetUuid, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testAssetUpdatedMessageAddsAssetWhenAssetDoesNotExists(): void
    {
        // Arrange
        $assetUuid = Uuid::uuid4()->toString();
        $assetUpdatedTransfer = $this->tester->haveAssetUpdatedTransfer(['assetIdentifier' => $assetUuid]);

        // Act
        $this->tester->runMessageReceiveTest($assetUpdatedTransfer, 'asset-commands');

        // Assert
        $this->tester->assertAssetWithUuidExists($assetUuid);
    }
}
