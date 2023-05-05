<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\NavigationStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
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
     * @var \SprykerTest\Zed\NavigationStorage\NavigationStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testNavigationStorageListenerStoreData(): void
    {
        $beforeCount = SpyNavigationStorageQuery::create()->count();

        $navigationStorageListener = new NavigationStorageListener();
        $navigationStorageListener->setFacade($this->getNavigationStorageFacade());

        $navigationTransfer = $this->tester->haveNavigation();

        $eventTransfers = [
            (new EventEntityTransfer())->setId($navigationTransfer->getIdNavigation()),
        ];
        $navigationStorageListener->handleBulk($eventTransfers, NavigationEvents::NAVIGATION_KEY_PUBLISH);

        // Assert
        $this->assertNavigationStorage($beforeCount, $navigationTransfer);
    }

    /**
     * @return void
     */
    public function testNavigationStoragePublishListener(): void
    {
        $beforeCount = SpyNavigationStorageQuery::create()->count();

        $navigationStoragePublishListener = new NavigationStoragePublishListener();
        $navigationStoragePublishListener->setFacade($this->getNavigationStorageFacade());

        $navigationTransfer = $this->tester->haveNavigation();

        $eventTransfers = [
            (new EventEntityTransfer())->setId($navigationTransfer->getIdNavigation()),
        ];
        $navigationStoragePublishListener->handleBulk($eventTransfers, NavigationEvents::NAVIGATION_KEY_PUBLISH);

        // Assert
        $this->assertNavigationStorage($beforeCount, $navigationTransfer);
    }

    /**
     * @return void
     */
    public function testNavigationStorageUnpublishListener(): void
    {
        $navigationStorageUnpublishListener = new NavigationStorageUnpublishListener();
        $navigationStorageUnpublishListener->setFacade($this->getNavigationStorageFacade());

        $navigationTransfer = $this->tester->haveNavigation();

        $eventTransfers = [
            (new EventEntityTransfer())->setId($navigationTransfer->getIdNavigation()),
        ];
        $navigationStorageUnpublishListener->handleBulk($eventTransfers, NavigationEvents::NAVIGATION_KEY_UNPUBLISH);

        // Assert
        $this->assertSame(
            0,
            SpyNavigationStorageQuery::create()->filterByFkNavigation($navigationTransfer->getIdNavigation())->count(),
        );
    }

    /**
     * @return void
     */
    public function testNavigationNodeStorageListenerStoreData(): void
    {
        $beforeCount = SpyNavigationStorageQuery::create()->count();

        $navigationNodeStorageListener = new NavigationNodeStorageListener();
        $navigationNodeStorageListener->setFacade($this->getNavigationStorageFacade());

        $navigationTransfer = $this->tester->haveNavigation();
        $localeTransfer = $this->tester->haveLocale();
        $navigationNodeTransfer = $this->tester->haveLocalizedNavigationNode([
            'fkNavigation' => $navigationTransfer->getIdNavigation(),
            'fkLocale' => $localeTransfer->getIdLocale(),
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyNavigationNodeTableMap::COL_FK_NAVIGATION => $navigationNodeTransfer->getFkNavigation(),
            ]),
        ];
        $navigationNodeStorageListener->handleBulk($eventTransfers, NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_CREATE);

        // Assert
        $this->assertNavigationNodeStorage($beforeCount, $navigationNodeTransfer);
    }

    /**
     * @return void
     */
    public function testNavigationNodeLocalizedAttributeStorageListenerStoreData(): void
    {
        $beforeCount = SpyNavigationStorageQuery::create()->count();

        $navigationNodeLocalizedAttributeStorageListener = new NavigationNodeLocalizedAttributeStorageListener();
        $navigationNodeLocalizedAttributeStorageListener->setFacade($this->getNavigationStorageFacade());

        $navigationTransfer = $this->tester->haveNavigation();
        $localeTransfer = $this->tester->haveLocale();
        $navigationNodeTransfer = $this->tester->haveLocalizedNavigationNode([
            'fkNavigation' => $navigationTransfer->getIdNavigation(),
            'fkLocale' => $localeTransfer->getIdLocale(),
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyNavigationNodeLocalizedAttributesTableMap::COL_FK_NAVIGATION_NODE =>
                    $navigationNodeTransfer->getIdNavigationNode(),
            ]),
        ];
        $navigationNodeLocalizedAttributeStorageListener->handleBulk($eventTransfers, NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_CREATE);

        // Assert
        $this->assertNavigationNodeStorage($beforeCount, $navigationNodeTransfer);
    }

    /**
     * @return void
     */
    public function testNavigationUrlRelationStorageListenerStoreData(): void
    {
        $beforeCount = SpyNavigationStorageQuery::create()->count();

        $navigationUrlRelationStorageListener = new NavigationUrlRelationStorageListener();
        $navigationUrlRelationStorageListener->setFacade($this->getNavigationStorageFacade());

        $urlTransfer = $this->tester->haveUrl();
        $navigationTransfer = $this->tester->haveNavigation();
        $localeTransfer = $this->tester->haveLocale();
        $navigationNodeTransfer = $this->tester->haveLocalizedNavigationNode([
            'fkNavigation' => $navigationTransfer->getIdNavigation(),
            'fkLocale' => $localeTransfer->getIdLocale(),
            'fkUrl' => $urlTransfer->getIdUrl(),
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($urlTransfer->getIdUrl()),
        ];
        $navigationUrlRelationStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_UPDATE);

        // Assert
        $this->assertNavigationNodeStorage($beforeCount, $navigationNodeTransfer);
    }

    /**
     * @return \Spryker\Zed\NavigationStorage\Business\NavigationStorageFacade
     */
    protected function getNavigationStorageFacade(): NavigationStorageFacade
    {
        $factory = new NavigationStorageBusinessFactory();
        $factory->setConfig(new NavigationStorageConfigMock());

        $facade = new NavigationStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    protected function assertNavigationStorage(int $beforeCount, NavigationTransfer $navigationTransfer): void
    {
        $navigationStorageCount = SpyNavigationStorageQuery::create()->count();

        $this->assertGreaterThan($beforeCount, $navigationStorageCount);
        $spyNavigationStorage = SpyNavigationStorageQuery::create()
            ->findOneByFkNavigation($navigationTransfer->getIdNavigation());
        $this->assertNotNull($spyNavigationStorage);

        $data = $spyNavigationStorage->getData();
        $this->assertSame($navigationTransfer->getKey(), $data['key']);
        $this->assertSame($navigationTransfer->getIdNavigation(), $data['id']);
        $this->assertSame($navigationTransfer->getName(), $data['name']);
    }

    /**
     * @param int $beforeCount
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    protected function assertNavigationNodeStorage(int $beforeCount, NavigationNodeTransfer $navigationNodeTransfer): void
    {
        $navigationStorageCount = SpyNavigationStorageQuery::create()->count();

        $this->assertGreaterThan($beforeCount, $navigationStorageCount);
        $spyNavigationStorage = SpyNavigationStorageQuery::create()
            ->findOneByFkNavigation($navigationNodeTransfer->getFkNavigation());
        $this->assertNotNull($spyNavigationStorage);
    }
}
