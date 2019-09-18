<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsVersionTableMap;
use Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorageQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\CmsStorage\Business\CmsStorageBusinessFactory;
use Spryker\Zed\CmsStorage\Business\CmsStorageFacade;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageStorageListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageStoragePublishListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageStorageUnpublishListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageUrlStorageListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageUrlStoragePublishListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageUrlStorageUnpublishListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageVersionStorageListener;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\CmsStorage\CmsStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CmsStorageListenerTest
 * Add your own group annotations below this line
 */
class CmsStorageListenerTest extends Unit
{
    public const NUMBER_OF_LOCALES = 2;
    public const NUMBER_OF_STORES = 3;

    /**
     * @return void
     */
    public function testCmsPageVersionStorageListenerStoreData(): void
    {
        SpyCmsPageStorageQuery::create()->filterByFkCmsPage(1)->delete();
        $beforeCount = SpyCmsPageStorageQuery::create()->count();

        $cmsPageVersionStorageListener = new CmsPageVersionStorageListener();
        $cmsPageVersionStorageListener->setFacade($this->getCmsStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsVersionTableMap::COL_FK_CMS_PAGE => 1,
            ]),
        ];
        $cmsPageVersionStorageListener->handleBulk($eventTransfers, CmsEvents::ENTITY_SPY_CMS_VERSION_CREATE);

        // Assert
        $this->assertCmsPageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsPageStorageListenerStoreData(): void
    {
        SpyCmsPageStorageQuery::create()->filterByFkCmsPage(1)->delete();
        $beforeCount = SpyCmsPageStorageQuery::create()->count();

        $cmsPageStorageListener = new CmsPageStorageListener();
        $cmsPageStorageListener->setFacade($this->getCmsStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $cmsPageStorageListener->handleBulk($eventTransfers, CmsEvents::CMS_VERSION_PUBLISH);

        // Assert
        $this->assertCmsPageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsPageStoragePublishListener(): void
    {
        SpyCmsPageStorageQuery::create()->filterByFkCmsPage(1)->delete();
        $beforeCount = SpyCmsPageStorageQuery::create()->count();

        $cmsPageStoragePublishListener = new CmsPageStoragePublishListener();
        $cmsPageStoragePublishListener->setFacade($this->getCmsStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $cmsPageStoragePublishListener->handleBulk($eventTransfers, CmsEvents::CMS_VERSION_PUBLISH);

        // Assert
        $this->assertCmsPageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsPageStorageUnpublishListener(): void
    {
        $cmsPageStorageUnpublishListener = new CmsPageStorageUnpublishListener();
        $cmsPageStorageUnpublishListener->setFacade($this->getCmsStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $cmsPageStorageUnpublishListener->handleBulk($eventTransfers, CmsEvents::CMS_VERSION_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyCmsPageStorageQuery::create()->filterByFkCmsPage(1)->count());
    }

    /**
     * @return void
     */
    public function testCmsPageUrlStorageListenerStoreData(): void
    {
        SpyCmsPageStorageQuery::create()->filterByFkCmsPage(1)->delete();
        $beforeCount = SpyCmsPageStorageQuery::create()->count();

        $cmsPageUrlStorageListener = new CmsPageUrlStorageListener();
        $cmsPageUrlStorageListener->setFacade($this->getCmsStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PAGE => 1,
            ]),
        ];
        $cmsPageUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_UPDATE);

        // Assert
        $this->assertCmsPageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsPageUrlStoragePublishListener(): void
    {
        SpyCmsPageStorageQuery::create()->filterByFkCmsPage(1)->delete();
        $beforeCount = SpyCmsPageStorageQuery::create()->count();

        $cmsPageUrlStoragePublishListener = new CmsPageUrlStoragePublishListener();
        $cmsPageUrlStoragePublishListener->setFacade($this->getCmsStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PAGE => 1,
            ]),
        ];
        $cmsPageUrlStoragePublishListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_UPDATE);

        // Assert
        $this->assertCmsPageStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsPageUrlStorageUnpublishListener(): void
    {
        $cmsPageUrlStorageUnpublishListener = new CmsPageUrlStorageUnpublishListener();
        $cmsPageUrlStorageUnpublishListener->setFacade($this->getCmsStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PAGE => 1,
            ]),
        ];
        $cmsPageUrlStorageUnpublishListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_DELETE);

        // Assert
        $this->assertSame(0, SpyCmsPageStorageQuery::create()->filterByFkCmsPage(1)->count());
    }

    /**
     * @return \Spryker\Zed\CmsStorage\Business\CmsStorageFacade
     */
    protected function getCmsStorageFacade()
    {
        $factory = new CmsStorageBusinessFactory();
        $factory->setConfig(new CmsStorageConfigMock());

        $facade = new CmsStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertCmsPageStorage(int $beforeCount): void
    {
        $count = SpyCmsPageStorageQuery::create()->count();

        $this->assertGreaterThan($beforeCount, $count);

        $cmsPage = SpyCmsPageStorageQuery::create()->filterByLocale('en_US')
            ->orderByIdCmsPageStorage()
            ->findOneByFkCmsPage(1);
        $this->assertNotNull($cmsPage);

        $data = $cmsPage->getData();
        $this->assertSame('Imprint', $data['name']);
    }
}
