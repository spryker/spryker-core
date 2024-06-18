<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreContextStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\StoreContext\Persistence\Map\SpyStoreContextTableMap;
use Spryker\Zed\StoreContextStorage\Business\StoreContextStorageBusinessFactory;
use Spryker\Zed\StoreContextStorage\Business\Writer\StoreContextStorageWriter;
use Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToStoreStorageFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreContextStorage
 * @group Business
 * @group Facade
 * @group StoreContextStorageFacadeTest
 * Add your own group annotations below this line
 */
class StoreContextStorageFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\StoreContextStorage\StoreContextStorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWriteStoreContextStorageCollectionByStoreEventsCallsStoreStorageFacadeMethod(): void
    {
        //Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $eventEntityTransfer = (new EventEntityTransfer())
            ->setForeignKeys([SpyStoreContextTableMap::COL_FK_STORE => $storeTransfer->getIdStore()]);
        $expectedEventEntityTransfer = $eventEntityTransfer
            ->setId($storeTransfer->getIdStore());

        //Assert
        $storeStorageFacadeMock = $this->getStoreStorageFacadeMock();
        $storeStorageFacadeMock->expects($this->once())
            ->method('writeCollectionByStoreEvents')
            ->with([$expectedEventEntityTransfer]);

        $eventBehaviorFacadeMock = $this->getEventBehaviorFacadeMock();
        $eventBehaviorFacadeMock->expects($this->once())
            ->method('getEventTransferForeignKeys')
            ->willReturn([$storeTransfer->getIdStore()]);
        $storeContextStorageWriter = new StoreContextStorageWriter(
            $eventBehaviorFacadeMock,
            $storeStorageFacadeMock,
        );
        $factoryMock = $this->getBusinessFactoryMock();
        $factoryMock->method('createStoreContextStorageWriter')->willReturn($storeContextStorageWriter);
        $storeContextStorageFacade = $this->tester->createStoreContextStorageFacade();
        $storeContextStorageFacade->setFactory($factoryMock);

        //Act
        $storeContextStorageFacade->writeStoreContextStorageCollectionByStoreEvents([$eventEntityTransfer]);
    }

    /**
     * @return \Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToStoreStorageFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getStoreStorageFacadeMock(): StoreContextStorageToStoreStorageFacadeInterface
    {
        return $this->getMockBuilder(StoreContextStorageToStoreStorageFacadeInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToEventBehaviorFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getEventBehaviorFacadeMock(): StoreContextStorageToEventBehaviorFacadeInterface
    {
        return $this->getMockBuilder(StoreContextStorageToEventBehaviorFacadeInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\StoreContextStorage\Business\StoreContextStorageBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getBusinessFactoryMock(): StoreContextStorageBusinessFactory
    {
        return $this->getMockBuilder(StoreContextStorageBusinessFactory::class)
            ->getMock();
    }
}
