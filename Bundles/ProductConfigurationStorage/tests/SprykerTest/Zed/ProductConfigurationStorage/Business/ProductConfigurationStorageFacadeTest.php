<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationStorageBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Zed\ProductConfigurationStorage\Business\ProductConfigurationStorageBusinessFactory;
use Spryker\Zed\ProductConfigurationStorage\Business\ProductConfigurationStorageFacade;
use Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManager;
use Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepository;
use Spryker\Zed\ProductConfigurationStorage\ProductConfigurationStorageConfig;

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
    const DEFAULT_QUERY_OFFSET = 0;

    const DEFAULT_QUERY_LIMIT = 100;

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManager
     */
    protected $productConfigurationStorageEntityManager;
    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Business\ProductConfigurationStorageFacade
     */
    protected $productConfigurationStorageFacade;
    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepository
     */
    protected $productConfigurationStorageRepository;
    /**
     * @var \SprykerTest\Zed\ProductConfigurationStorage\ProductConfigurationStorageBusinessTester
     */
    protected $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $productConfigurationStorageFactory = new ProductConfigurationStorageBusinessFactory();
        $productConfigurationStorageConfigMock = $this
            ->getMockBuilder(ProductConfigurationStorageConfig::class)
            ->onlyMethods(['isSynchronizationEnabled'])
            ->getMock();

        $productConfigurationStorageConfigMock->method('isSynchronizationEnabled')->willReturn(false);

        $productConfigurationStorageFactory->setConfig($productConfigurationStorageConfigMock);
        $productConfigurationStorageFacade = new ProductConfigurationStorageFacade();
        $productConfigurationStorageFacade->setFactory($productConfigurationStorageFactory);

        $this->productConfigurationStorageFacade = $productConfigurationStorageFacade;
        $this->productConfigurationStorageRepository = new ProductConfigurationStorageRepository();
        $this->productConfigurationStorageEntityManager = new ProductConfigurationStorageEntityManager();
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationCollection(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();

        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
            ]
        );

        // Act
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

        $this->productConfigurationStorageFacade
            ->writeProductConfigurationStorageCollectionByProductConfigurationEvents($eventTransfers);

        $productConfigurationStorageTransfers = $this->productConfigurationStorageRepository
            ->findProductConfigurationStorageTransfersByProductConfigurationIds(
                [
                    $productConfigurationTransfer->getIdProductConfiguration()
                ]
            );

        $productConfigurationStorageTransfer = array_shift($productConfigurationStorageTransfers);

        $this->assertEquals(
            $productTransfer->getIdProductConcrete(),
            $productConfigurationStorageTransfer['FkProduct']
        );

        $this->assertEquals(
            $productConfigurationTransfer->getIdProductConfiguration(),
            $productConfigurationStorageTransfer['FkProductConfiguration']
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

        $this->productConfigurationStorageFacade
            ->deleteProductConfigurationStorageCollection($eventTransfers);

        $syncTransfers = $this->productConfigurationStorageRepository
            ->findProductConfigurationStorageDataTransferByIds(
                static::DEFAULT_QUERY_OFFSET,
                static::DEFAULT_QUERY_LIMIT,
                [
                    $productConfigurationStorageTransfer->getIdProductConfigurationStorage()
                ]
            );

        $this->assertEmpty($syncTransfers);

    }

    /**
     * @return void
     */
    public function testFindProductConfigurationStorageDataTransferByIds(): void
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

        $syncTransfers = $this->productConfigurationStorageFacade->findProductConfigurationStorageDataTransferByIds(
            static::DEFAULT_QUERY_OFFSET,
            static::DEFAULT_QUERY_LIMIT,
                [
                    $productConfigurationStorageTransfer->getIdProductConfigurationStorage()
                ]
        );
        /** @var  \Generated\Shared\Transfer\SynchronizationDataTransfer $syncTransfer */
        $syncTransfer = array_shift($syncTransfers);

        $this->assertEquals($productTransfer->getIdProductConcrete(), $syncTransfer->getData()['fk_product']);
        $this->assertEquals(
            $productConfigurationTransfer->getIdProductConfiguration(),
            $syncTransfer->getData()['fk_product_configuration']
        );
    }
}
