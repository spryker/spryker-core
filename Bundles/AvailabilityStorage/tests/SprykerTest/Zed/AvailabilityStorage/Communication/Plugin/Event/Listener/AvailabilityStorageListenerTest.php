<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorageQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageBusinessFactory;
use Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacade;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener\AvailabilityProductStorageListener;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener\AvailabilityStorageListener;
use Spryker\Zed\Product\Dependency\ProductEvents;
use SprykerTest\Zed\AvailabilityStorage\AvailabilityStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group AvailabilityStorageListenerTest
 * Add your own group annotations below this line
 */
class AvailabilityStorageListenerTest extends Unit
{
    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    protected $spyAvailabilityAbstractEntityTransfer;

    /**
     * @var \SprykerTest\Zed\AvailabilityStorage\AvailabilityStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->productConcreteTransfer = $this->tester->haveProduct();
        $this->spyAvailabilityAbstractEntityTransfer = $this->tester->haveAvailabilityAbstract($this->productConcreteTransfer);
    }

    /**
     * @return void
     */
    public function testAvailabilityStorageListenerStoreData()
    {
        SpyAvailabilityStorageQuery::create()->filterByFkProductAbstract($this->productConcreteTransfer->getFkProductAbstract())->delete();

        $availabilityStorageCount = SpyAvailabilityStorageQuery::create()->count();

        // Act
        $availabilityStorageListener = new AvailabilityStorageListener();
        $availabilityStorageListener->setFacade($this->getAvailabilityStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->spyAvailabilityAbstractEntityTransfer->getIdAvailabilityAbstract()),
        ];

        $availabilityStorageListener->handleBulk($eventTransfers, AvailabilityEvents::AVAILABILITY_ABSTRACT_PUBLISH);

        // Assert
        $this->assertAvailabilityStorage($availabilityStorageCount);
    }

    /**
     * @return void
     */
    public function testAvailabilityProductStorageListenerStoreData()
    {
        SpyAvailabilityStorageQuery::create()->filterByFkProductAbstract($this->productConcreteTransfer->getFkProductAbstract())->delete();
        $availabilityStorageCount = SpyAvailabilityStorageQuery::create()->count();

        // Act
        $availabilityStorageListener = new AvailabilityProductStorageListener();
        $availabilityStorageListener->setFacade($this->getAvailabilityStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productConcreteTransfer->getFkProductAbstract(),
            ]),
        ];
        $availabilityStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_UPDATE);

        // Assert
        $this->assertAvailabilityStorage($availabilityStorageCount);
    }

    /**
     * @return \Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacade
     */
    protected function getAvailabilityStorageFacade()
    {
        $factory = new AvailabilityStorageBusinessFactory();
        $factory->setConfig(new AvailabilityStorageConfigMock());

        $facade = new AvailabilityStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $previousCount
     *
     * @return void
     */
    protected function assertAvailabilityStorage($previousCount)
    {
        $availabilityStorageCount = SpyAvailabilityStorageQuery::create()->count();
        $this->assertGreaterThan($previousCount, $availabilityStorageCount);

        $availabilityStorageEntityList = SpyAvailabilityStorageQuery::create()
            ->findByFkProductAbstract($this->productConcreteTransfer->getFkProductAbstract())
            ->toKeyIndex('fkAvailabilityAbstract');

        $availabilityStorageEntity = $availabilityStorageEntityList[$this->spyAvailabilityAbstractEntityTransfer->getIdAvailabilityAbstract()] ?? null;

        $this->assertNotNull($availabilityStorageEntity);
        $data = $availabilityStorageEntity->getData();
        $this->assertEquals($this->spyAvailabilityAbstractEntityTransfer->getQuantity(), $data['quantity']);
    }
}
