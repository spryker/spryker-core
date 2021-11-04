<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
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
 * @group WriteCollectionByProductConfigurationEventsTest
 * Add your own group annotations below this line
 */
class WriteCollectionByProductConfigurationEventsTest extends Unit
{
    /**
     * @var int
     */
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
    public function testWriteCollectionByProductConfigurationEventsShouldSaveProductConfigurationStorage(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()],
        );

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productConfigurationTransfer->getIdProductConfiguration()),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByProductConfigurationEvents($eventTransfers);

        // Assert
        $this->assertSame(
            1,
            $this->tester->countProductConfigurationStorageEntities(),
            'Expects that will save product configuration to the storage.',
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductConfigurationEventsShouldSaveProductConfigurationStorageForMultipleEvents(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $secondProductConcreteTransfer = $this->tester->haveProduct();

        $firstProductConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $firstProductConcreteTransfer->getIdProductConcrete()],
        );

        $secondProductConfigurationTransfer = $this->tester->haveProductConfiguration(
            [ProductConfigurationTransfer::FK_PRODUCT => $secondProductConcreteTransfer->getIdProductConcrete()],
        );

        $eventTransfers = [
            (new EventEntityTransfer())->setId($firstProductConfigurationTransfer->getIdProductConfiguration()),
            (new EventEntityTransfer())->setId($secondProductConfigurationTransfer->getIdProductConfiguration()),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByProductConfigurationEvents($eventTransfers);

        // Assert
        $this->assertSame(
            2,
            $this->tester->countProductConfigurationStorageEntities(),
            'Expects that will save product configuration to the storage for multiple events.',
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductConfigurationEventsShouldSaveProductConfigurationStorageWithoutIds(): void
    {
        // Arrange
        $eventTransfers = [
            new EventEntityTransfer(),
            new EventEntityTransfer(),
            new EventEntityTransfer(),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByProductConfigurationEvents($eventTransfers);

        // Assert
        $this->assertSame(
            0,
            $this->tester->countProductConfigurationStorageEntities(),
            'Expects that wont save product configuration to the storage when id not specified.',
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductConfigurationEventsShouldSaveProductConfigurationStorageWithFakeIds(): void
    {
        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setId(static::FAKE_PRODUCT_CONFIGURATION_ID),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByProductConfigurationEvents($eventTransfers);

        // Assert
        $this->assertSame(
            0,
            $this->tester->countProductConfigurationStorageEntities(),
            'Expects that wont save product configuration to the storage when fake id specified.',
        );
    }
}
