<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityStorage\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Spryker\Client\Storage\StorageDependencyProvider;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConfig;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConstants;
use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Subscriber\AvailabilityStorageEventSubscriber;
use Spryker\Zed\Event\Communication\Plugin\Queue\EventQueueMessageProcessorPlugin;
use Spryker\Zed\Queue\QueueDependencyProvider;
use Spryker\Zed\Synchronization\Communication\Plugin\Queue\SynchronizationStorageQueueMessageProcessorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityStorage
 * @group Persistence
 * @group PublishAndSynchronizeTest
 * Add your own group annotations below this line
 */
class PublishAndSynchronizeTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_STORE = 1;

    /**
     * @var string
     */
    protected const STORAGE_KEY = 'availability:de:%d';

    /**
     * @var \SprykerTest\Zed\AvailabilityStorage\AvailabilityStoragePersistenceTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    protected $productAbstractAvailabilityEntity;

    /**
     * All tests in here require to have a valid availability entity to work on.
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

        $this->tester->addEventSubscriber(new AvailabilityStorageEventSubscriber());

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_MESSAGE_PROCESSOR_PLUGINS, [
            AvailabilityStorageConfig::PUBLISH_AVAILABILITY => new EventQueueMessageProcessorPlugin(),
            AvailabilityStorageConstants::AVAILABILITY_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
        ]);

        $this->tester->setDependency(
            StorageDependencyProvider::PLUGIN_STORAGE,
            $this->tester->getInMemoryStoragePlugin(),
        );

        $this->productConcreteTransfer = $this->tester->haveProduct();
        $this->productAbstractAvailabilityEntity = $this->tester->haveAvailabilityAbstract(
            $this->productConcreteTransfer,
            new Decimal(2),
            static::ID_STORE,
        );

        $this->tester->haveAvailabilityConcrete($this->productConcreteTransfer->getSku(), $this->tester->haveStore([StoreTransfer::NAME => 'DE']));
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testAvailabilityStoragePublishAndSynchronize(): void
    {
        $this->assertCreatedEntityIsSynchronizedToStorage();
        $this->assertUpdatedEntityIsUpdatedInStorage();
        $this->assertDeletedEntityIsRemovedFromStorage();
    }

    /**
     * @return void
     */
    protected function assertCreatedEntityIsSynchronizedToStorage(): void
    {
        $this->tester->assertEntityIsPublished(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_CREATE, AvailabilityStorageConfig::PUBLISH_AVAILABILITY);
        $this->tester->assertEntityIsSynchronizedToStorage(AvailabilityStorageConstants::AVAILABILITY_SYNC_STORAGE_QUEUE);

        $this->tester->assertStorageHasKey($this->getExpectedStorageKey());
    }

    /**
     * @return void
     */
    public function assertUpdatedEntityIsUpdatedInStorage(): void
    {
        // Act
        $availabilityAbstractEntity = SpyAvailabilityAbstractQuery::create()->findOneByAbstractSku($this->productAbstractAvailabilityEntity->getAbstractSku());

        /** @var \Orm\Zed\Availability\Persistence\SpyAvailability $availabilityEntity */
        $availabilityEntity = $availabilityAbstractEntity->getSpyAvailabilities()->getFirst();
        $availabilityEntity->setQuantity((int)$availabilityEntity->getQuantity() + 1);
        $availabilityAbstractEntity->save();

        // Assert
        $this->tester->assertEntityIsPublished(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_UPDATE, AvailabilityStorageConfig::PUBLISH_AVAILABILITY);
        $this->tester->assertEntityIsUpdatedInStorage(AvailabilityStorageConstants::AVAILABILITY_SYNC_STORAGE_QUEUE);
    }

    /**
     * @return void
     */
    public function assertDeletedEntityIsRemovedFromStorage(): void
    {
        // Act - Delete created entities
        $availabilityAbstractEntity = SpyAvailabilityAbstractQuery::create()->findOneByAbstractSku($this->productAbstractAvailabilityEntity->getAbstractSku());
        $availabilityAbstractEntity->getSpyAvailabilities()->delete();
        $availabilityAbstractEntity->delete();

        // Assert
        $this->tester->assertEntityIsPublished(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_DELETE, AvailabilityStorageConfig::PUBLISH_AVAILABILITY);
        $this->tester->assertEntityIsRemovedFromStorage(AvailabilityStorageConstants::AVAILABILITY_SYNC_STORAGE_QUEUE);

        $this->tester->assertStorageNotHasKey($this->getExpectedStorageKey());
    }

    /**
     * @return string
     */
    protected function getExpectedStorageKey(): string
    {
        return sprintf(static::STORAGE_KEY, $this->productConcreteTransfer->getFkProductAbstractOrFail());
    }
}
