<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorageQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManager;
use Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfiguration
 * @group Business
 * @group Facade
 * @group ProductConfigurationStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductConfigurationStorageFacadeTest extends Unit
{
    /**
     * @var int
     */
    protected const DEFAULT_QUERY_OFFSET = 0;

    /**
     * @var int
     */
    protected const DEFAULT_QUERY_LIMIT = 100;

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManager
     */
    protected $productConfigurationStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepository
     */
    protected $productConfigurationStorageRepository;

    /**
     * @var \SprykerTest\Zed\ProductConfigurationStorage\ProductConfigurationStorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->productConfigurationStorageRepository = new ProductConfigurationStorageRepository();
        $this->productConfigurationStorageEntityManager = new ProductConfigurationStorageEntityManager();
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductConfigurationEventsShouldSaveProductConfigurationStorage(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();

        $productConfigurationTransfer = $this->tester->haveProductConfigurationTransferPersisted(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
            ],
        );

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productConfigurationTransfer->getIdProductConfiguration()),
        ];

        // Act
        $this->tester->getFacade()
            ->writeCollectionByProductConfigurationEvents($eventTransfers);

        // Assert
        $productConfigurationStorageEntity = SpyProductConfigurationStorageQuery::create()->filterByFkProductConfiguration(
            $productConfigurationTransfer->getIdProductConfiguration(),
        )->findOne();

        $this->assertSame(
            $productTransfer->getSku(),
            $productConfigurationStorageEntity->getSku(),
            'Expected that product configuration will be saved to the storage.',
        );

        $this->assertSame(
            $productConfigurationTransfer->getIdProductConfiguration(),
            $productConfigurationStorageEntity->getFkProductConfiguration(),
            'Expects that will save product configuration to the storage.',
        );
    }

    /**
     * @return void
     */
    public function testDeleteCollectionShouldRemoveProductConfigurationStorageShouldRemoveProductConfigurationStorage(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();

        $productConfigurationTransfer = $this->tester->haveProductConfigurationTransferPersisted(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
            ],
        );

        $productConfigurationStorageTransfer = $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::SKU => $productTransfer->getSku(),
                ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $productConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productConfigurationTransfer->getIdProductConfiguration()),
        ];

        // Act
        $this->tester->getFacade()
            ->deleteCollectionByProductConfigurationEvents($eventTransfers);

        $productConfigurationStorageEntity = SpyProductConfigurationStorageQuery::create()->filterByIdProductConfigurationStorage(
            $productConfigurationStorageTransfer->getIdProductConfigurationStorage(),
        )->findOne();

        // Assert
        $this->assertEmpty(
            $productConfigurationStorageEntity,
            'Expected that product configuration will be removed from the storage.',
        );
    }

    /**
     * @return void
     */
    public function testGetFilteredProductConfigurationStorageDataTransfersWillReturnSynchronizationDataTransfers(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();

        $productConfigurationTransfer = $this->tester->haveProductConfigurationTransferPersisted([
                ProductConfigurationTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
        ]);

        $productConfigurationStorageTransfer = $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::SKU => $productTransfer->getSku(),
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $productConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $filter = (new FilterTransfer())->setOffset(static::DEFAULT_QUERY_OFFSET)->setLimit(static::DEFAULT_QUERY_LIMIT);

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()->getFilteredProductConfigurationStorageDataTransfers(
            $filter,
            [$productConfigurationStorageTransfer->getIdProductConfigurationStorage()],
        );

        // Assert
        $this->assertCount(
            1,
            $synchronizationDataTransfers,
            'Expects that will return synchronization data transfer.',
        );
    }
}
