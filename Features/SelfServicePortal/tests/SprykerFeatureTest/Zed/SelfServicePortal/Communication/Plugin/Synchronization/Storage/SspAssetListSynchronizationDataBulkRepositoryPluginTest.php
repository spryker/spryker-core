<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Synchronization\Storage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Synchronization\Storage\SspAssetListSynchronizationDataBulkRepositoryPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Synchronization
 * @group SspAssetListSynchronizationDataBulkRepositoryPluginTest
 * Add your own group annotations below this line
 */
class SspAssetListSynchronizationDataBulkRepositoryPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const RESOURCE_NAME = 'ssp_asset';

    /**
     * @var string
     */
    protected const QUEUE_NAME = 'sync.storage.ssp_asset';

    /**
     * @var string
     */
    protected const SYNCHRONIZATION_QUEUE_POOL_NAME = 'synchronizationPool';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container): array {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->clearSspAssetStorageData();
        $this->tester->ensureSspAssetRelatedTablesAreEmpty();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->clearSspAssetStorageData();
        $this->tester->ensureSspAssetRelatedTablesAreEmpty();
    }

    public function testGetDataReturnsEmptyArrayWithInvalidIds(): void
    {
        // Act
        $synchronizationDataTransfers = (new SspAssetListSynchronizationDataBulkRepositoryPlugin())->getData(0, 10, [999]);

        // Assert
        $this->assertIsArray($synchronizationDataTransfers);
        $this->assertEmpty($synchronizationDataTransfers);
    }

    public function testGetDataReturnsDataWithoutIds(): void
    {
        // Arrange
        $this->tester->clearSspAssetStorageData();

        $sspAssetTransfer1 = $this->tester->haveAsset();
        $sspAssetTransfer2 = $this->tester->haveAsset();

        $this->tester->haveSspAssetStorage($sspAssetTransfer1->getIdSspAsset(), [1, 2]);
        $this->tester->haveSspAssetStorage($sspAssetTransfer2->getIdSspAsset(), [3, 4]);

        // Act
        $synchronizationDataTransfers = (new SspAssetListSynchronizationDataBulkRepositoryPlugin())->getData(0, 10);

        // Assert
        $this->assertGreaterThanOrEqual(2, count($synchronizationDataTransfers));

        foreach ($synchronizationDataTransfers as $synchronizationDataTransfer) {
            $this->assertInstanceOf(SynchronizationDataTransfer::class, $synchronizationDataTransfer);
        }
    }

    public function testGetDataReturnsDataWithSpecificIds(): void
    {
        // Arrange
        $this->tester->clearSspAssetStorageData();

        $sspAssetTransfer1 = $this->tester->haveAsset();
        $sspAssetTransfer2 = $this->tester->haveAsset();

        $this->tester->haveSspAssetStorage($sspAssetTransfer1->getIdSspAsset(), [1, 2]);
        $this->tester->haveSspAssetStorage($sspAssetTransfer2->getIdSspAsset(), [3, 4]);

        // Act
        $synchronizationDataTransfers = (new SspAssetListSynchronizationDataBulkRepositoryPlugin())->getData(
            0,
            10,
            [$sspAssetTransfer1->getIdSspAsset()],
        );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
        $this->assertInstanceOf(SynchronizationDataTransfer::class, $synchronizationDataTransfers[0]);
    }

    public function testGetDataReturnsEmptyArrayWhenLimitIsZero(): void
    {
        // Arrange
        $sspAssetTransfer = $this->tester->haveAsset();
        $this->tester->haveSspAssetStorage($sspAssetTransfer->getIdSspAsset(), [1, 2, 3]);

        // Act
        $synchronizationDataTransfers = (new SspAssetListSynchronizationDataBulkRepositoryPlugin())->getData(0, 0);

        // Assert
        $this->assertEmpty($synchronizationDataTransfers);
    }

    public function testGetResourceNameReturnsCorrectResourceName(): void
    {
        // Act
        $resourceName = (new SspAssetListSynchronizationDataBulkRepositoryPlugin())->getResourceName();

        // Assert
        $this->assertSame(static::RESOURCE_NAME, $resourceName);
        $this->assertSame(SelfServicePortalConfig::SSP_ASSET_RESOURCE_NAME, $resourceName);
    }

    public function testHasStoreReturnsFalse(): void
    {
        // Act
        $hasStore = (new SspAssetListSynchronizationDataBulkRepositoryPlugin())->hasStore();

        // Assert
        $this->assertFalse($hasStore);
    }

    public function testGetParamsReturnsEmptyArray(): void
    {
        // Act
        $params = (new SspAssetListSynchronizationDataBulkRepositoryPlugin())->getParams();

        // Assert
        $this->assertIsArray($params);
        $this->assertEmpty($params);
    }

    public function testGetQueueNameReturnsCorrectQueueName(): void
    {
        // Act
        $queueName = (new SspAssetListSynchronizationDataBulkRepositoryPlugin())->getQueueName();

        // Assert
        $this->assertSame(static::QUEUE_NAME, $queueName);
        $this->assertSame(SelfServicePortalConfig::QUEUE_NAME_SYNC_STORAGE_SSP_ASSET, $queueName);
    }

    public function testGetSynchronizationQueuePoolNameReturnsCorrectPoolName(): void
    {
        // Act
        $poolName = (new SspAssetListSynchronizationDataBulkRepositoryPlugin())->getSynchronizationQueuePoolName();

        // Assert
        $this->assertSame(static::SYNCHRONIZATION_QUEUE_POOL_NAME, $poolName);
    }
}
