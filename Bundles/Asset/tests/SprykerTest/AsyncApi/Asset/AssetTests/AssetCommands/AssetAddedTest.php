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
 * @group AssetAddedTest
 * Add your own group annotations below this line
 */
class AssetAddedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\Asset\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testAssetAddedMessageCreatesAnAssetEntity(): void
    {
        // Arrange
        $assetUuid = Uuid::uuid4()->toString();
        $assetAddedTransfer = $this->tester->haveAssetAddedTransfer(['assetIdentifier' => $assetUuid]);

        // Act
        $this->tester->runMessageReceiveTest($assetAddedTransfer, 'asset-commands');

        // Assert
        $this->tester->assertAssetWithUuidExists($assetUuid);
    }
}
