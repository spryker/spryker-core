<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SspAssetTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAssetPublisherTriggerPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group SspAsset
 * @group SspAssetPublisherTriggerPluginTest
 * Add your own group annotations below this line
 */
class SspAssetPublisherTriggerPluginTest extends Unit
{
    /**
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::SSP_ASSET_RESOURCE_NAME
     *
     * @var string
     */
    protected const SSP_ASSET_RESOURCE_NAME = 'ssp_asset';

    /**
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::SSP_ASSET_PUBLISH
     *
     * @var string
     */
    protected const SSP_ASSET_PUBLISH = 'SspAsset.ssp_asset.publish';

    /**
     * @uses \Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetTableMap::COL_ID_SSP_ASSET
     *
     * @var string
     */
    protected const COL_ID_SSP_ASSET = 'spy_ssp_asset.id_ssp_asset';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->clearSspAssetData();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->clearSspAssetData();
    }

    public function testGetDataReturnsSspAssetTransfersAccordingToOffsetAndLimit(): void
    {
        // Arrange
        $this->tester->clearSspAssetData();

        $this->tester->haveSspAsset([
            SspAssetTransfer::NAME => 'First Asset',
        ]);
        $this->tester->haveSspAsset([
            SspAssetTransfer::NAME => 'Second Asset',
        ]);
        $this->tester->haveSspAsset([
            SspAssetTransfer::NAME => 'Third Asset',
        ]);

        // Act
        $allAssets = (new SspAssetPublisherTriggerPlugin())->getData(0, 10);
        $paginatedAssets = (new SspAssetPublisherTriggerPlugin())->getData(1, 2);

        // Assert
        $this->assertGreaterThanOrEqual(3, count($allAssets));

        $this->assertCount(2, $paginatedAssets);
        $this->assertInstanceOf(SspAssetTransfer::class, $paginatedAssets[0]);
        $this->assertInstanceOf(SspAssetTransfer::class, $paginatedAssets[1]);

        $this->assertNotEquals($allAssets[0]->getIdSspAsset(), $paginatedAssets[0]->getIdSspAsset());
    }

    public function testGetDataReturnsNoSspAssetTransfersWhenLimitIsEqualToZero(): void
    {
        // Arrange
        $this->tester->clearSspAssetData();

        $this->tester->haveSspAsset([
            SspAssetTransfer::NAME => 'Test Asset',
        ]);

        // Act
        $sspAssetTransfers = (new SspAssetPublisherTriggerPlugin())->getData(0, 0);

        // Assert
        $this->assertCount(0, $sspAssetTransfers);
    }

    public function testGetResourceNameReturnsCorrectResourceName(): void
    {
        // Act
        $resourceName = (new SspAssetPublisherTriggerPlugin())->getResourceName();

        // Assert
        $this->assertSame(static::SSP_ASSET_RESOURCE_NAME, $resourceName);
    }

    public function testGetEventNameReturnsCorrectEventName(): void
    {
        // Act
        $eventName = (new SspAssetPublisherTriggerPlugin())->getEventName();

        // Assert
        $this->assertSame(static::SSP_ASSET_PUBLISH, $eventName);
    }

    public function testGetIdColumnNameReturnsCorrectColumnName(): void
    {
        // Act
        $idColumnName = (new SspAssetPublisherTriggerPlugin())->getIdColumnName();

        // Assert
        $this->assertSame(static::COL_ID_SSP_ASSET, $idColumnName);
    }
}
