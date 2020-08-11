<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationStorageBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
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
    protected const DEFAULT_QUERY_OFFSET = 0;

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
    public function testGetProductConfigurationCollection(): void
    {
        $productTransfer = $this->tester->haveProduct();

        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
            ]
        );

        $productConfigurationCriteriaTransfer = (new ProductConfigurationFilterTransfer())
            ->setProductConfigurationIds([$productConfigurationTransfer->getIdProductConfiguration()]);

        $productConfigurations = $this->tester->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        /** @var \Generated\Shared\Transfer\ProductConfigurationTransfer $createdProductConfigurationTransfer */
        $createdProductConfigurationTransfer = $productConfigurations->getProductConfigurations()->getIterator()->current();

        $this->assertNotEmpty($productConfigurations->getProductConfigurations());
        $this->assertEquals($productTransfer->getIdProductConcrete(), $createdProductConfigurationTransfer->getFkProduct());
    }

    /**
     * @return void
     */
    public function testWriteProductConfigurationStorageCollectionByProductConfigurationEvents(): void
    {
        $productTransfer = $this->tester->haveProduct();

        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
            ]
        );

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productConfigurationTransfer->getIdProductConfiguration()),
        ];

        $this->tester->getFacade()
            ->writeProductConfigurationStorageCollectionByProductConfigurationEvents($eventTransfers);

        $productConfigurationStorageEntity = SpyProductConfigurationStorageQuery::create()->filterByFkProductConfiguration(
            $productConfigurationTransfer->getIdProductConfiguration()
        )->findOne();

        $this->assertEquals(
            $productTransfer->getIdProductConcrete(),
            $productConfigurationStorageEntity->getFkProduct()
        );

        $this->assertEquals(
            $productConfigurationTransfer->getIdProductConfiguration(),
            $productConfigurationStorageEntity->getFkProductConfiguration()
        );
    }

    /**
     * @return void
     */
    public function testDeleteProductConfigurationStorageCollection(): void
    {
        $productTransfer = $this->tester->haveProduct();

        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
            ]
        );

        $productConfigurationStorageTransfer = $this->productConfigurationStorageEntityManager->saveProductConfigurationStorage(
            (new ProductConfigurationStorageBuilder([
                ProductConfigurationStorageTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
                ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $productConfigurationTransfer->getIdProductConfiguration(),
            ]))->build()
        );

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productConfigurationTransfer->getIdProductConfiguration()),
        ];

        $this->tester->getFacade()
            ->deleteProductConfigurationStorageCollection($eventTransfers);

        $filter = (new FilterTransfer())->setOffset(static::DEFAULT_QUERY_OFFSET)->setLimit(static::DEFAULT_QUERY_LIMIT);

        $syncTransfers = $this->productConfigurationStorageRepository
            ->getProductConfigurationStorageDataTransfersByCriteria(
                (new ProductConfigurationFilterTransfer())->setProductConfigurationStorageIds([
                    $productConfigurationStorageTransfer->getIdProductConfigurationStorage(),
                ])->setFilter($filter)
            );

        $this->assertEmpty($syncTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationStorageDataTransfersByCriteria(): void
    {
        $productTransfer = $this->tester->haveProduct();

        $productConfigurationTransfer = $this->tester->haveProductConfiguration([
                ProductConfigurationTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
        ]);

        $productConfigurationStorageTransfer = $this->productConfigurationStorageEntityManager->saveProductConfigurationStorage(
            (new ProductConfigurationStorageBuilder([
                ProductConfigurationStorageTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
                ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $productConfigurationTransfer->getIdProductConfiguration(),
            ]))->build()
        );

        $filter = (new FilterTransfer())->setOffset(static::DEFAULT_QUERY_OFFSET)->setLimit(static::DEFAULT_QUERY_LIMIT);

        $syncTransfers = $this->tester->getFacade()->getProductConfigurationStorageDataTransfersByCriteria(
            (new ProductConfigurationFilterTransfer())->setProductConfigurationStorageIds([
                $productConfigurationStorageTransfer->getIdProductConfigurationStorage(),
            ])->setFilter($filter)
        );

        /** @var \Generated\Shared\Transfer\SynchronizationDataTransfer $syncTransfer */
        $syncTransfer = array_shift($syncTransfers);

        $this->assertEquals($productTransfer->getIdProductConcrete(), $syncTransfer->getData()['fk_product']);
        $this->assertEquals(
            $productConfigurationTransfer->getIdProductConfiguration(),
            $syncTransfer->getData()['fk_product_configuration']
        );
    }
}
