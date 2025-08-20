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
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Synchronization\Storage\SspModelListSynchronizationDataBulkRepositoryPlugin;
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
 * @group SspModelListSynchronizationDataBulkRepositoryPluginTest
 * Add your own group annotations below this line
 */
class SspModelListSynchronizationDataBulkRepositoryPluginTest extends Unit
{
    /**
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::SSP_MODEL_RESOURCE_NAME
     *
     * @var string
     */
    protected const SSP_MODEL_RESOURCE_NAME = 'ssp_model';

    /**
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::QUEUE_NAME_SYNC_STORAGE_SSP_MODEL
     *
     * @var string
     */
    protected const QUEUE_NAME_SYNC_STORAGE_SSP_MODEL = 'sync.storage.ssp_model';

    /**
     * @var int
     */
    protected const TEST_INVALID_ID = 999999;

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

        $this->tester->clearSspModelData();
        $this->tester->clearSspModelStorageData();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->clearSspModelData();
        $this->tester->clearSspModelStorageData();
    }

    public function testGetDataReturnsEmptyArrayWithInvalidIds(): void
    {
        // Act
        $synchronizationDataTransfers = (new SspModelListSynchronizationDataBulkRepositoryPlugin())->getData(
            0,
            1,
            [static::TEST_INVALID_ID],
        );

        // Assert
        $this->assertEmpty($synchronizationDataTransfers);
    }

    public function testGetDataReturnsDataWithoutIds(): void
    {
        // Arrange
        $this->tester->clearSspModelStorageData();

        $sspModelTransfer = $this->tester->haveSspModel();
        $this->tester->haveSspModelStorage($sspModelTransfer->getIdSspModel(), [1, 2, 3]);

        // Act
        $synchronizationDataTransfers = (new SspModelListSynchronizationDataBulkRepositoryPlugin())->getData(0, 10);

        // Assert
        $this->assertNotEmpty($synchronizationDataTransfers);
        $this->assertInstanceOf(SynchronizationDataTransfer::class, $synchronizationDataTransfers[0]);
    }

    public function testGetDataReturnsDataWithSpecificIds(): void
    {
        // Arrange
        $this->tester->clearSspModelStorageData();

        $sspModelTransfer1 = $this->tester->haveSspModel();
        $sspModelTransfer2 = $this->tester->haveSspModel();

        $this->tester->haveSspModelStorage($sspModelTransfer1->getIdSspModel(), [1, 2]);
        $this->tester->haveSspModelStorage($sspModelTransfer2->getIdSspModel(), [3, 4]);

        // Act
        $synchronizationDataTransfers = (new SspModelListSynchronizationDataBulkRepositoryPlugin())->getData(
            0,
            10,
            [$sspModelTransfer1->getIdSspModel()],
        );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
        $this->assertInstanceOf(SynchronizationDataTransfer::class, $synchronizationDataTransfers[0]);
    }

    public function testGetDataReturnsEmptyArrayWhenLimitIsZero(): void
    {
        // Arrange
        $sspModelTransfer = $this->tester->haveSspModel();
        $this->tester->haveSspModelStorage($sspModelTransfer->getIdSspModel(), [1, 2, 3]);

        // Act
        $synchronizationDataTransfers = (new SspModelListSynchronizationDataBulkRepositoryPlugin())->getData(0, 0);

        // Assert
        $this->assertEmpty($synchronizationDataTransfers);
    }

    public function testGetResourceNameReturnsCorrectResourceName(): void
    {
        // Act
        $resourceName = (new SspModelListSynchronizationDataBulkRepositoryPlugin())->getResourceName();

        // Assert
        $this->assertSame(static::SSP_MODEL_RESOURCE_NAME, $resourceName);
    }

    public function testHasStoreReturnsFalse(): void
    {
        // Act
        $hasStore = (new SspModelListSynchronizationDataBulkRepositoryPlugin())->hasStore();

        // Assert
        $this->assertFalse($hasStore);
    }

    public function testGetParamsReturnsEmptyArray(): void
    {
        // Act
        $params = (new SspModelListSynchronizationDataBulkRepositoryPlugin())->getParams();

        // Assert
        $this->assertIsArray($params);
        $this->assertEmpty($params);
    }

    public function testGetQueueNameReturnsCorrectQueueName(): void
    {
        // Act
        $queueName = (new SspModelListSynchronizationDataBulkRepositoryPlugin())->getQueueName();

        // Assert
        $this->assertSame(static::QUEUE_NAME_SYNC_STORAGE_SSP_MODEL, $queueName);
    }

    public function testGetSynchronizationQueuePoolNameReturnsString(): void
    {
        // Act
        $poolName = (new SspModelListSynchronizationDataBulkRepositoryPlugin())->getSynchronizationQueuePoolName();

        // Assert
        $this->assertIsString($poolName);
        $this->assertNotEmpty($poolName);
    }
}
