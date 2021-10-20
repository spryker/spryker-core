<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitStorage\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;
use Spryker\Shared\ProductStorage\ProductStorageConstants;
use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\Event\Communication\Plugin\Queue\EventQueueMessageProcessorPlugin;
use Spryker\Zed\ProductMeasurementUnit\Dependency\ProductMeasurementUnitEvents;
use Spryker\Zed\ProductMeasurementUnitStorage\Communication\Plugin\Event\Subscriber\ProductMeasurementUnitStorageEventSubscriber;
use Spryker\Zed\Queue\QueueDependencyProvider;
use Spryker\Zed\Synchronization\Communication\Plugin\Queue\SynchronizationStorageQueueMessageProcessorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnitStorage
 * @group Persistence
 * @group PublishAndSynchronizeTest
 * Add your own group annotations below this line
 */
class PublishAndSynchronizeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStoragePersistenceTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer|null
     */
    protected $productMeasurementUnitTransfer;

    /**
     * All tests in here require to have a valid entity to work on.
     * We setup only once and let the main test method re-use this data for:
     *
     * - Gets published and synchronized after create
     * - Gets published and updated after update
     * - Gets published and removed after delete
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->addEventSubscriber(new ProductMeasurementUnitStorageEventSubscriber());

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_MESSAGE_PROCESSOR_PLUGINS, [
            PublisherConfig::PUBLISH_QUEUE => new EventQueueMessageProcessorPlugin(),
            ProductStorageConstants::PRODUCT_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
        ]);

        $code = Uuid::uuid4()->toString();

        $this->productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testProductMeasurementUnitStoragePublishAndSynchronize(): void
    {
        $this->assertCreatedEntityIsSynchronizedToStorage();
        $this->assertUpdatedEntityIsUpdatedInStorage();
    }

    /**
     * @return void
     */
    protected function assertCreatedEntityIsSynchronizedToStorage(): void
    {
        $this->tester->assertEntityCanBeManuallyPublished(
            ProductMeasurementUnitEvents::PRODUCT_MEASUREMENT_UNIT_PUBLISH,
            [$this->productMeasurementUnitTransfer->getIdProductMeasurementUnitOrFail()],
            PublisherConfig::PUBLISH_QUEUE,
        );
        $this->tester->assertEntityIsSynchronizedToStorage(ProductMeasurementUnitStorageConfig::PRODUCT_MEASUREMENT_UNIT_SYNC_STORAGE_QUEUE);

        $this->tester->assertStorageHasKey($this->getExpectedStorageKey());
    }

    /**
     * @return void
     */
    public function assertUpdatedEntityIsUpdatedInStorage(): void
    {
        // Act
        $productMeasurementUnitEntity = SpyProductMeasurementUnitQuery::create()->findOneByCode(
            $this->productMeasurementUnitTransfer->getCodeOrFail(),
        );

        $productMeasurementUnitEntity->setName('foo');
        $productMeasurementUnitEntity->save();

        // Assert
        $this->tester->assertEntityCanBeManuallyPublished(
            ProductMeasurementUnitEvents::PRODUCT_MEASUREMENT_UNIT_PUBLISH,
            [$this->productMeasurementUnitTransfer->getIdProductMeasurementUnitOrFail()],
            PublisherConfig::PUBLISH_QUEUE,
        );
        $this->tester->assertEntityIsUpdatedInStorage(ProductMeasurementUnitStorageConfig::PRODUCT_MEASUREMENT_UNIT_SYNC_STORAGE_QUEUE);

        $this->tester->assertStorageHasKey($this->getExpectedStorageKey());
    }

    /**
     * @return string
     */
    protected function getExpectedStorageKey(): string
    {
        return sprintf('product_measurement_unit:code:%s', $this->productMeasurementUnitTransfer->getCodeOrFail());
    }
}
