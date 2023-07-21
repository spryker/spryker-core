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
 * @group AssetDeletedTest
 * Add your own group annotations below this line
 */
class AssetDeletedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\Asset\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @uses \SprykerTest\AsyncApi\Asset\Helper\AssetCommandsAssetDeletedHelper
     *
     * @return void
     */
    public function testAssetDeletedMessageDeletesAsset(): void
    {
        // Arrange
        $assetUuid = Uuid::uuid4()->toString();

        // Create the Asset in the database
        $this->tester->haveAsset(['assetUuid' => $assetUuid]);

        // Create delete message
        $assetDeletedTransfer = $this->tester->haveAssetDeletedTransfer(['assetIdentifier' => $assetUuid]);

        // Act
        $this->tester->runMessageReceiveTest($assetDeletedTransfer, 'asset-commands');

        // Assert
        $this->tester->assertAssetWithUuidIsInactive($assetUuid);
    }
}
