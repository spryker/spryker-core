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
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\CmsStorage\Business\CmsStorageBusinessFactory;
use Spryker\Zed\CmsStorage\Business\CmsStorageFacade;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageStorageListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageUrlStorageListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageVersionStorageListener;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\CmsStorage\CmsStorageConfigMock;

/**
 * Auto-generated group annotations
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
    public function testCmsPageVersionStorageListenerStoreData()
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
    public function testCmsPageStorageListenerStoreData()
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
    public function testCmsPageUrlStorageListenerStoreData()
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
    protected function assertCmsPageStorage($beforeCount)
    {
        $count = SpyCmsPageStorageQuery::create()->count();
        $this->assertSame($beforeCount + 2, $count);
        $cmsPage = SpyCmsPageStorageQuery::create()->filterByLocale('en_US')->orderByIdCmsPageStorage()->findOneByFkCmsPage(1);
        $this->assertNotNull($cmsPage);
        $data = $cmsPage->getData();
        $this->assertSame('Imprint', $data['name']);
    }
}
