<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\NavigationStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Navigation\Persistence\Map\SpyNavigationNodeLocalizedAttributesTableMap;
use Orm\Zed\Navigation\Persistence\Map\SpyNavigationNodeTableMap;
use Orm\Zed\NavigationStorage\Persistence\SpyNavigationStorageQuery;
use Spryker\Zed\Navigation\Dependency\NavigationEvents;
use Spryker\Zed\NavigationStorage\Business\NavigationStorageBusinessFactory;
use Spryker\Zed\NavigationStorage\Business\NavigationStorageFacade;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener\NavigationNodeLocalizedAttributeStorageListener;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener\NavigationNodeStorageListener;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener\NavigationStorageListener;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener\NavigationStoragePublishListener;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener\NavigationStorageUnpublishListener;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener\NavigationUrlRelationStorageListener;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\NavigationStorage\NavigationStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group NavigationStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group NavigationStorageListenerTest
 * Add your own group annotations below this line
 */
class NavigationStorageListenerTest extends Unit
{
    /**
     * @return void
     */
    public function testNavigationStorageListenerStoreData(): void
    {
        SpyNavigationStorageQuery::create()->filterByFkNavigation(1)->delete();
        $beforeCount = SpyNavigationStorageQuery::create()->count();

        $navigationStorageListener = new NavigationStorageListener();
        $navigationStorageListener->setFacade($this->getNavigationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $navigationStorageListener->handleBulk($eventTransfers, NavigationEvents::NAVIGATION_KEY_PUBLISH);

        // Assert
        $this->assertNavigationStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testNavigationStoragePublishListener(): void
    {
        SpyNavigationStorageQuery::create()->filterByFkNavigation(1)->delete();
        $beforeCount = SpyNavigationStorageQuery::create()->count();

        $navigationStoragePublishListener = new NavigationStoragePublishListener();
        $navigationStoragePublishListener->setFacade($this->getNavigationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $navigationStoragePublishListener->handleBulk($eventTransfers, NavigationEvents::NAVIGATION_KEY_PUBLISH);

        // Assert
        $this->assertNavigationStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testNavigationStorageUnpublishListener(): void
    {
        $navigationStorageUnpublishListener = new NavigationStorageUnpublishListener();
        $navigationStorageUnpublishListener->setFacade($this->getNavigationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $navigationStorageUnpublishListener->handleBulk($eventTransfers, NavigationEvents::NAVIGATION_KEY_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyNavigationStorageQuery::create()->filterByFkNavigation(1)->count());
    }

    /**
     * @return void
     */
    public function testNavigationNodeStorageListenerStoreData(): void
    {
        SpyNavigationStorageQuery::create()->filterByFkNavigation(1)->delete();
        $beforeCount = SpyNavigationStorageQuery::create()->count();

        $navigationNodeStorageListener = new NavigationNodeStorageListener();
        $navigationNodeStorageListener->setFacade($this->getNavigationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyNavigationNodeTableMap::COL_FK_NAVIGATION => 1,
            ]),
        ];
        $navigationNodeStorageListener->handleBulk($eventTransfers, NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_CREATE);

        // Assert
        $this->assertNavigationStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testNavigationNodeLocalizedAttributeStorageListenerStoreData(): void
    {
        SpyNavigationStorageQuery::create()->filterByFkNavigation(1)->delete();
        $beforeCount = SpyNavigationStorageQuery::create()->count();

        $navigationNodeLocalizedAttributeStorageListener = new NavigationNodeLocalizedAttributeStorageListener();
        $navigationNodeLocalizedAttributeStorageListener->setFacade($this->getNavigationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyNavigationNodeLocalizedAttributesTableMap::COL_FK_NAVIGATION_NODE => 1,
            ]),
        ];
        $navigationNodeLocalizedAttributeStorageListener->handleBulk($eventTransfers, NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_CREATE);

        // Assert
        $this->assertNavigationStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testNavigationUrlRelationStorageListenerStoreData(): void
    {
        SpyNavigationStorageQuery::create()->filterByFkNavigation(1)->delete();
        $beforeCount = SpyNavigationStorageQuery::create()->count();

        $navigationUrlRelationStorageListener = new NavigationUrlRelationStorageListener();
        $navigationUrlRelationStorageListener->setFacade($this->getNavigationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(11),
        ];
        $navigationUrlRelationStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_UPDATE);

        // Assert
        $this->assertNavigationStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\NavigationStorage\Business\NavigationStorageFacade
     */
    protected function getNavigationStorageFacade()
    {
        $factory = new NavigationStorageBusinessFactory();
        $factory->setConfig(new NavigationStorageConfigMock());

        $facade = new NavigationStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertNavigationStorage(int $beforeCount): void
    {
        $navigationStorageCount = SpyNavigationStorageQuery::create()->count();

        $this->assertGreaterThan($beforeCount, $navigationStorageCount);
        $spyNavigationStorage = SpyNavigationStorageQuery::create()
            ->orderByIdNavigationStorage()
            ->findOneByFkNavigation(1);
        $this->assertNotNull($spyNavigationStorage);

        $data = $spyNavigationStorage->getData();
        $this->assertSame('MAIN_NAVIGATION', $data['key']);
        $this->assertSame(7, count($data['nodes']));
    }
}
