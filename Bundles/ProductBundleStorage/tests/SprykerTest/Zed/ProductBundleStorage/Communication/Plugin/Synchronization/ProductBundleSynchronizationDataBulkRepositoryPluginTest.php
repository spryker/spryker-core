<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundleStorage\Communication\Plugin\Synchronization;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductBundleStorage\Communication\Plugin\Synchronization\ProductBundleSynchronizationDataBulkRepositoryPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundleStorage
 * @group Communication
 * @group Plugin
 * @group Synchronization
 * @group ProductConcreteProductBundleWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class ProductBundleSynchronizationDataBulkRepositoryPluginTest extends Unit
{
    protected const FAKE_ID_PRODUCT_CONCRETE = 6666;

    /**
     * @var \SprykerTest\Zed\ProductBundleStorage\ProductBundleStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductBundleStorage\Business\ProductBundleStorageFacade
     */
    protected $facade;

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

        $this->facade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testProductBundleSynchronizationDataBulkRepositoryPlugin(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());
        $eventTransfers = [
            (new EventEntityTransfer())->setId($productConcreteTransfer->getIdProductConcrete()),
        ];

        $this->facade->writeCollectionByProductBundlePublishEvents($eventTransfers);

        // Act
        $productBundleSynchronizationDataBulkRepositoryPlugin = new ProductBundleSynchronizationDataBulkRepositoryPlugin();
        $synchronizationDataTransfers = $productBundleSynchronizationDataBulkRepositoryPlugin->getData(
            0,
            10,
            [$productConcreteTransfer->getIdProductConcrete()]
        );

        // Assert
        $this->assertNotEmpty($synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testProductBundleSynchronizationDataBulkRepositoryPluginWithInvalidIds(): void
    {
        // Act
        $productBundleSynchronizationDataBulkRepositoryPlugin = new ProductBundleSynchronizationDataBulkRepositoryPlugin();
        $synchronizationDataTransfers = $productBundleSynchronizationDataBulkRepositoryPlugin->getData(
            0,
            1,
            [static::FAKE_ID_PRODUCT_CONCRETE]
        );

        // Assert
        $this->assertEmpty($synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testProductBundleSynchronizationDataBulkRepositoryPluginWithoutIds(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());
        $eventTransfers = [
            (new EventEntityTransfer())->setId($productConcreteTransfer->getIdProductConcrete()),
        ];

        $this->facade->writeCollectionByProductBundlePublishEvents($eventTransfers);

        // Act
        $productBundleSynchronizationDataBulkRepositoryPlugin = new ProductBundleSynchronizationDataBulkRepositoryPlugin();
        $synchronizationDataTransfers = $productBundleSynchronizationDataBulkRepositoryPlugin->getData(0, 10);

        // Assert
        $this->assertNotEmpty($synchronizationDataTransfers);
    }
}
