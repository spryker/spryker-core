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
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
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
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }
    }

    /**
     * @return void
     */
    public function testAvailabilityStorageListenerStoreData()
    {
        SpyAvailabilityStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $availabilityStorageCount = SpyAvailabilityStorageQuery::create()->count();

        // Act
        $availabilityStorageListener = new AvailabilityStorageListener();
        $availabilityStorageListener->setFacade($this->getAvailabilityStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
            (new EventEntityTransfer())->setId(2),
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
        SpyAvailabilityStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $availabilityStorageCount = SpyAvailabilityStorageQuery::create()->count();

        // Act
        $availabilityStorageListener = new AvailabilityProductStorageListener();
        $availabilityStorageListener->setFacade($this->getAvailabilityStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
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
        $this->assertEquals($previousCount + 2, $availabilityStorageCount);
        $availabilityStorageEntity = SpyAvailabilityStorageQuery::create()->orderByIdAvailabilityStorage()->findOneByFkProductAbstract(1);
        $this->assertNotNull($availabilityStorageEntity);
        $data = $availabilityStorageEntity->getData();
        $this->assertEquals(10, $data['quantity']);
    }
}
