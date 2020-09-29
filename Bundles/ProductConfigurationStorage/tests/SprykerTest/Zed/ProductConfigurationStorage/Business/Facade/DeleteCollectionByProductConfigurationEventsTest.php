<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
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
 * @group DeleteCollectionByProductConfigurationEventsTest
 * Add your own group annotations below this line
 */
class DeleteCollectionByProductConfigurationEventsTest extends Unit
{
    protected const FAKE_PRODUCT_CONFIGURATION_ID = 66666;

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
    }

    /**
     * @return void
     */
    public function testDeleteCollectionByProductConfigurationEventsShouldRemoveProductConfigurationStorage(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]
        );

        $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $productConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productConfigurationTransfer->getIdProductConfiguration()),
        ];

        // Act
        $this->tester->getFacade()->deleteCollectionByProductConfigurationEvents($eventTransfers);

        // Assert
        $this->assertSame(0, $this->tester->countProductConfigurationStorageEntities());
    }

    /**
     * @return void
     */
    public function testDeleteCollectionShouldRemoveProductConfigurationStorageWithoutIds(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]
        );

        $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $productConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $eventTransfers = [
            new EventEntityTransfer(),
            new EventEntityTransfer(),
            new EventEntityTransfer(),
        ];

        // Act
        $this->tester->getFacade()->deleteCollectionByProductConfigurationEvents($eventTransfers);

        // Assert
        $this->assertSame(1, $this->tester->countProductConfigurationStorageEntities());
    }

    /**
     * @return void
     */
    public function testDeleteCollectionShouldRemoveProductConfigurationStorageWithFakeId(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]
        );

        $this->tester->haveProductConfigurationStorage([
            ProductConfigurationStorageTransfer::FK_PRODUCT_CONFIGURATION => $productConfigurationTransfer->getIdProductConfiguration(),
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setId(static::FAKE_PRODUCT_CONFIGURATION_ID),
        ];

        // Act
        $this->tester->getFacade()->deleteCollectionByProductConfigurationEvents($eventTransfers);

        // Assert
        $this->assertSame(1, $this->tester->countProductConfigurationStorageEntities());
    }
}
