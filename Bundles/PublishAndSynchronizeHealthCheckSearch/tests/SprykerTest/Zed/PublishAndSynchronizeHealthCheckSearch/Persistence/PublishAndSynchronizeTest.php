<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery;
use Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearchQuery;
use Orm\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\SpyPublishAndSynchronizeHealthCheckStorageQuery;
use Spryker\Shared\PublishAndSynchronizeHealthCheck\PublishAndSynchronizeHealthCheckConfig;
use Spryker\Shared\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig as SharedPublishAndSynchronizeHealthCheckSearchConfig;
use Spryker\Zed\Event\Communication\Plugin\Queue\EventQueueMessageProcessorPlugin;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Dependency\PublishAndSynchronizeHealthCheckEvents;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Communication\Plugin\Publisher\PublishAndSynchronizeHealthCheckSearchWritePublisherPlugin;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig;
use Spryker\Zed\Publisher\PublisherDependencyProvider;
use Spryker\Zed\Queue\QueueDependencyProvider;
use Spryker\Zed\Synchronization\Communication\Plugin\Queue\SynchronizationSearchQueueMessageProcessorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PublishAndSynchronizeHealthCheckSearch
 * @group Persistence
 * @group PublishAndSynchronizeTest
 * Add your own group annotations below this line
 */
class PublishAndSynchronizeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchPersistenceTester
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

        SpyPublishAndSynchronizeHealthCheckSearchQuery::create()->find()->delete();
        SpyPublishAndSynchronizeHealthCheckStorageQuery::create()->find()->delete();

        $this->tester->setDependency(PublisherDependencyProvider::PLUGINS_PUBLISHER, [
            PublishAndSynchronizeHealthCheckConfig::PUBLISH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK => [
                new PublishAndSynchronizeHealthCheckSearchWritePublisherPlugin(),
            ],
        ]);

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_MESSAGE_PROCESSOR_PLUGINS, [
            PublishAndSynchronizeHealthCheckConfig::PUBLISH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK => new EventQueueMessageProcessorPlugin(),
            SharedPublishAndSynchronizeHealthCheckSearchConfig::SYNC_SEARCH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK => new SynchronizationSearchQueueMessageProcessorPlugin(),
        ]);

        $this->publishAndSynchronizeHealthCheckTransfer = $this->tester->havePublishAndSynchronizeHealthCheck();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        SpyPublishAndSynchronizeHealthCheckSearchQuery::create()->find()->delete();
        SpyPublishAndSynchronizeHealthCheckStorageQuery::create()->find()->delete();
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testPublishAndSynchronizeHealthCheckSearchPublishAndSynchronize(): void
    {
        $this->assertCreatedEntityIsSynchronizedToSearch();
        $this->assertUpdatedEntityIsUpdatedInSearch();
    }

    /**
     * @return void
     */
    protected function assertCreatedEntityIsSynchronizedToSearch(): void
    {
        $this->tester->assertEntityIsPublished(
            PublishAndSynchronizeHealthCheckEvents::ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_CREATE,
            PublishAndSynchronizeHealthCheckConfig::PUBLISH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK
        );
        $this->tester->assertEntityIsSynchronizedToSearch(SharedPublishAndSynchronizeHealthCheckSearchConfig::SYNC_SEARCH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK);

        $this->tester->assertSearchHasKey($this->getExpectedSearchKey(), PublishAndSynchronizeHealthCheckSearchConfig::SOURCE_IDENTIFIER);
    }

    /**
     * @return void
     */
    protected function assertUpdatedEntityIsUpdatedInSearch(): void
    {
        $publishAndSynchronizeHealthCheckEntity = SpyPublishAndSynchronizeHealthCheckQuery::create()
            ->findOneByHealthCheckKey($this->publishAndSynchronizeHealthCheckTransfer->getHealthCheckKey());

        $publishAndSynchronizeHealthCheckEntity->setHealthCheckData('Updated health check data');
        $publishAndSynchronizeHealthCheckEntity->save();

        $this->tester->assertEntityIsPublished(
            PublishAndSynchronizeHealthCheckEvents::ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_UPDATE,
            PublishAndSynchronizeHealthCheckConfig::PUBLISH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK
        );
        $this->tester->assertEntityIsUpdatedInSearch(SharedPublishAndSynchronizeHealthCheckSearchConfig::SYNC_SEARCH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK);
    }

    /**
     * We test only existence of one store with this key.
     *
     * @return string
     */
    protected function getExpectedSearchKey(): string
    {
        return sprintf('publish_and_synchronize_health_check:%s', $this->publishAndSynchronizeHealthCheckTransfer->getHealthCheckKey());
    }
}
