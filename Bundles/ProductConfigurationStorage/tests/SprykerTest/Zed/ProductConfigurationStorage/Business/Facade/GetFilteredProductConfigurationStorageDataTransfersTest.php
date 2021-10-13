<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationStorage
 * @group Business
 * @group Facade
 * @group GetFilteredProductConfigurationStorageDataTransfersTest
 * Add your own group annotations below this line
 */
class GetFilteredProductConfigurationStorageDataTransfersTest extends Unit
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
     * @var int
     */
    protected const FAKE_PRODUCT_CONFIGURATION_STORAGE_ID = 66666;

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

        $this->tester->truncateProductConfigurationStorageEntities();
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    public function testGetFilteredProductConfigurationStorageDataTransfersWillReturnSynchronizationDataTransfers(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]
        );

        $productConfigurationStorageTransfer = $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $productConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $filterTransfer = (new FilterTransfer())
            ->setOffset(static::DEFAULT_QUERY_OFFSET)
            ->setLimit(static::DEFAULT_QUERY_LIMIT);

        // Act
        $synchronizationDataTransfers = $this->tester
            ->getFacade()
            ->getFilteredProductConfigurationStorageDataTransfers(
                $filterTransfer,
                [$productConfigurationStorageTransfer->getIdProductConfigurationStorage()]
            );

        // Assert
        $this->assertCount(
            1,
            $synchronizationDataTransfers,
            'Expects that will return synchronization data transfers.'
        );
    }

    /**
     * @return void
     */
    public function testGetFilteredProductConfigurationStorageDataTransfersWillReturnSynchronizationDataTransfersWithFakeId(): void
    {
        // Arrange
        $filterTransfer = (new FilterTransfer())
            ->setOffset(static::DEFAULT_QUERY_OFFSET)
            ->setLimit(static::DEFAULT_QUERY_LIMIT);

        // Act
        $synchronizationDataTransfers = $this->tester
            ->getFacade()
            ->getFilteredProductConfigurationStorageDataTransfers(
                $filterTransfer,
                [static::FAKE_PRODUCT_CONFIGURATION_STORAGE_ID]
            );

        // Assert
        $this->assertCount(
            0,
            $synchronizationDataTransfers,
            'Expects that will return empty synchronization data transfers when use fake id.'
        );
    }

    /**
     * @return void
     */
    public function testGetFilteredProductConfigurationStorageDataTransfersWillReturnSynchronizationDataTransfersWithoutIds(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]
        );

        $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $productConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $filterTransfer = (new FilterTransfer())
            ->setOffset(static::DEFAULT_QUERY_OFFSET)
            ->setLimit(static::DEFAULT_QUERY_LIMIT);

        // Act
        $synchronizationDataTransfers = $this->tester
            ->getFacade()
            ->getFilteredProductConfigurationStorageDataTransfers($filterTransfer, []);

        // Assert
        $this->assertCount(
            1,
            $synchronizationDataTransfers,
            'Expects that will return synchronization data transfers when no ids specified.'
        );
    }

    /**
     * @return void
     */
    public function testGetFilteredProductConfigurationStorageDataTransfersWillReturnSynchronizationDataTransfersWithLimit(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $secondProductConcreteTransfer = $this->tester->haveProduct();
        $firstProductConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $firstProductConcreteTransfer->getIdProductConcrete()]
        );
        $secondProductConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $secondProductConcreteTransfer->getIdProductConcrete()]
        );

        $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $firstProductConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $secondProductConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $filterTransfer = (new FilterTransfer())
            ->setOffset(static::DEFAULT_QUERY_OFFSET)
            ->setLimit(1);

        // Act
        $synchronizationDataTransfers = $this->tester
            ->getFacade()
            ->getFilteredProductConfigurationStorageDataTransfers($filterTransfer, []);

        // Assert
        $this->assertCount(
            1,
            $synchronizationDataTransfers,
            'Expects that will return synchronization data transfers when limit is specified.'
        );
    }

    /**
     * @return void
     */
    public function testGetFilteredProductConfigurationStorageDataTransfersWillReturnSynchronizationDataTransfersChecksZeroOffset(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $secondProductConcreteTransfer = $this->tester->haveProduct();
        $firstProductConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $firstProductConcreteTransfer->getIdProductConcrete()]
        );
        $secondProductConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $secondProductConcreteTransfer->getIdProductConcrete()]
        );

        $firstProductConfigurationStorageTransfer = $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $firstProductConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $secondProductConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $filterTransfer = (new FilterTransfer())
            ->setOffset(static::DEFAULT_QUERY_OFFSET)
            ->setLimit(static::DEFAULT_QUERY_LIMIT);

        // Act
        $synchronizationDataTransfers = $this->tester
            ->getFacade()
            ->getFilteredProductConfigurationStorageDataTransfers($filterTransfer, []);

        // Assert
        $this->assertSame(
            $firstProductConfigurationStorageTransfer->toArray()['fk_product_configuration'],
            $synchronizationDataTransfers[0]->getData()['fk_product_configuration'],
            'Expects that will return synchronization data transfer with expected product configuration fk when offset is 0.'
        );
        $this->assertSame(
            $firstProductConfigurationStorageTransfer->toArray()['sku'],
            $synchronizationDataTransfers[0]->getData()['sku'],
            'Expects that will return synchronization data transfer with expected sku when offset is 0.'
        );
    }

    /**
     * @return void
     */
    public function testGetFilteredProductConfigurationStorageDataTransfersWillReturnSynchronizationDataTransfersChecksFirstOffset(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $secondProductConcreteTransfer = $this->tester->haveProduct();
        $firstProductConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $firstProductConcreteTransfer->getIdProductConcrete()]
        );
        $secondProductConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $secondProductConcreteTransfer->getIdProductConcrete()]
        );

        $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $firstProductConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $secondProductConfigurationStorageTransfer = $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $secondProductConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $filterTransfer = (new FilterTransfer())
            ->setOffset(1)
            ->setLimit(static::DEFAULT_QUERY_LIMIT);

        // Act
        $synchronizationDataTransfers = $this->tester
            ->getFacade()
            ->getFilteredProductConfigurationStorageDataTransfers($filterTransfer, []);

        // Assert
        $this->assertSame(
            $secondProductConfigurationStorageTransfer->toArray()['fk_product_configuration'],
            $synchronizationDataTransfers[0]->getData()['fk_product_configuration'],
            'Expects that will return synchronization data transfer with expected product configuration fk when offset is 1.'
        );
        $this->assertSame(
            $secondProductConfigurationStorageTransfer->toArray()['sku'],
            $synchronizationDataTransfers[0]->getData()['sku'],
            'Expects that will return synchronization data transfer with expected sku when offset is 1.'
        );
    }
}
