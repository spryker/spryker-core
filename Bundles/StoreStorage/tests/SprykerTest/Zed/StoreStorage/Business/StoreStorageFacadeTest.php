<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StoreStorageConditionsTransfer;
use Generated\Shared\Transfer\StoreStorageCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\Store\StoreDependencyProvider;
use Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreStorage
 * @group Business
 * @group Facade
 * @group StoreStorageFacadeTest
 * Add your own group annotations below this line
 */
class StoreStorageFacadeTest extends Unit
{
    /**
     * @var int
     */
    protected const INVALID_STORE_ID = 0;

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var \SprykerTest\Zed\StoreStorage\StoreStorageBusinessTester
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
    public function testGetStoreStorageSynchronizationDataTransfersReturnsNotEmptyArray(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $storeStorageCriteriaTransfer = (new StoreStorageCriteriaTransfer())
            ->setStoreStorageConditions((new StoreStorageConditionsTransfer())
                ->setStoreIds([$storeTransfer->getIdStore()]));

        $this->tester->setDependency(StoreDependencyProvider::STORE, $this->getStoreToStoreInterface());

        $eventEntityTransfer = (new EventEntityTransfer())->setId($storeTransfer->getIdStore());

        // Act
        $storeStorageFacade = $this->tester->getLocator()->storeStorage()->facade();
        $storeStorageFacade->writeCollectionByStoreEvents([$eventEntityTransfer]);

        // Assert
        $synchronizationDataTransfers = $storeStorageFacade->getStoreStorageSynchronizationDataTransfers(
            $storeStorageCriteriaTransfer,
        );

        $this->assertCount(1, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetStoreStorageSynchronizationDataTransfersReturnsEmptyArray(): void
    {
        // Act
        $storeStorageFacade = $this->tester->getLocator()->storeStorage()->facade();
        $storeStorageCriteriaTransfer = (new StoreStorageCriteriaTransfer())
            ->setStoreStorageConditions((new StoreStorageConditionsTransfer())
                ->setStoreIds([static::INVALID_STORE_ID]));

        // Assert
        $synchronizationDataTransfers = $storeStorageFacade->getStoreStorageSynchronizationDataTransfers(
            $storeStorageCriteriaTransfer,
        );

        $this->assertCount(0, $synchronizationDataTransfers);
    }

    /**
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getStoreToStoreInterface(): StoreToStoreInterface
    {
        $storeToStoreInterfaceMock = $this->getMockBuilder(StoreToStoreInterface::class)->getMock();
        $storeToStoreInterfaceMock->method('getAvailableLocaleIsoCodesFor')->willReturn([static::LOCALE_DE]);

        return $storeToStoreInterfaceMock;
    }
}
