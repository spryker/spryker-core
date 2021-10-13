<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery;
use Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearchQuery;
use Orm\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\SpyPublishAndSynchronizeHealthCheckStorageQuery;
use Spryker\Shared\PublishAndSynchronizeHealthCheck\PublishAndSynchronizeHealthCheckConfig;
use Spryker\Shared\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig;
use Spryker\Zed\Event\Communication\Plugin\Queue\EventQueueMessageProcessorPlugin;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Dependency\PublishAndSynchronizeHealthCheckEvents;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Communication\Plugin\Publisher\PublishAndSynchronizeHealthCheckStorageWritePublisherPlugin;
use Spryker\Zed\Publisher\PublisherDependencyProvider;
use Spryker\Zed\Queue\QueueDependencyProvider;
use Spryker\Zed\Synchronization\Communication\Plugin\Queue\SynchronizationStorageQueueMessageProcessorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PublishAndSynchronizeHealthCheckStorage
 * @group Persistence
 * @group PublishAndSynchronizeTest
 * Add your own group annotations below this line
 */
class PublishAndSynchronizeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStoragePersistenceTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    protected $publishAndSynchronizeHealthCheckTransfer;

    /**
     * All tests in here require to have a valid health check entity to work on.
     * We setup only once and let the main test method re-use this data for:
     *
     * - Gets published and synchronized after create
     * - Gets published and updated after update
     * - Rets published and removed after delete
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        SpyPublishAndSynchronizeHealthCheckStorageQuery::create()->find()->delete();
        SpyPublishAndSynchronizeHealthCheckSearchQuery::create()->find()->delete();

        $this->tester->setDependency(PublisherDependencyProvider::PLUGINS_PUBLISHER, [
            PublishAndSynchronizeHealthCheckConfig::PUBLISH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK => [
                new PublishAndSynchronizeHealthCheckStorageWritePublisherPlugin(),
            ],
        ]);

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_MESSAGE_PROCESSOR_PLUGINS, [
            PublishAndSynchronizeHealthCheckConfig::PUBLISH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK => new EventQueueMessageProcessorPlugin(),
            PublishAndSynchronizeHealthCheckStorageConfig::SYNC_STORAGE_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK => new SynchronizationStorageQueueMessageProcessorPlugin(),
        ]);

        $this->publishAndSynchronizeHealthCheckTransfer = $this->tester->havePublishAndSynchronizeHealthCheck();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        SpyPublishAndSynchronizeHealthCheckStorageQuery::create()->find()->delete();
        SpyPublishAndSynchronizeHealthCheckSearchQuery::create()->find()->delete();
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testPublishAndSynchronizeHealthCheckStoragePublishAndSynchronize(): void
    {
        $this->assertCreatedEntityIsSynchronizedToStorage();
        $this->assertUpdatedEntityIsUpdatedInStorage();
    }

    /**
     * @return void
     */
    protected function assertCreatedEntityIsSynchronizedToStorage(): void
    {
        $this->tester->assertEntityIsPublished(
            PublishAndSynchronizeHealthCheckEvents::ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_CREATE,
            PublishAndSynchronizeHealthCheckConfig::PUBLISH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK
        );
        $this->tester->assertEntityIsSynchronizedToStorage(PublishAndSynchronizeHealthCheckStorageConfig::SYNC_STORAGE_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK);

        $this->tester->assertStorageHasKey($this->getExpectedStorageKey());
    }

    /**
     * @return void
     */
    protected function assertUpdatedEntityIsUpdatedInStorage(): void
    {
        $publishAndSynchronizeHealthCheckEntity = SpyPublishAndSynchronizeHealthCheckQuery::create()
            ->findOneByHealthCheckKey($this->publishAndSynchronizeHealthCheckTransfer->getHealthCheckKey());

        $publishAndSynchronizeHealthCheckEntity->setHealthCheckData('Updated health check data');
        $publishAndSynchronizeHealthCheckEntity->save();

        $this->tester->assertEntityIsPublished(
            PublishAndSynchronizeHealthCheckEvents::ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_UPDATE,
            PublishAndSynchronizeHealthCheckConfig::PUBLISH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK
        );
        $this->tester->assertEntityIsUpdatedInStorage(PublishAndSynchronizeHealthCheckStorageConfig::SYNC_STORAGE_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK);
    }

    /**
     * We test only existence of one store with this key.
     *
     * @return string
     */
    protected function getExpectedStorageKey(): string
    {
        return sprintf('publish_and_synchronize_health_check:%s', $this->publishAndSynchronizeHealthCheckTransfer->getHealthCheckKey());
    }
}
