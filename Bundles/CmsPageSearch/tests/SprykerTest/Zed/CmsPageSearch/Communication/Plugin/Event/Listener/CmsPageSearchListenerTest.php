<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsVersionTableMap;
use Orm\Zed\CmsPageSearch\Persistence\SpyCmsPageSearchQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacade;
use Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Listener\CmsPageSearchListener;
use Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Listener\CmsPageUrlSearchListener;
use Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Listener\CmsPageVersionSearchListener;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchBridge;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\CmsPageSearch\Business\CmsPageSearchBusinessFactoryMock;
use SprykerTest\Zed\CmsPageSearch\CmsPageSearchConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsPageSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CmsPageSearchListenerTest
 * Add your own group annotations below this line
 */
class CmsPageSearchListenerTest extends Unit
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
    public function testCmsPageVersionSearchListenerStoreData()
    {
        SpyCmsPageSearchQuery::create()->filterByFkCmsPage(1)->delete();
        $beforeCount = SpyCmsPageSearchQuery::create()->count();

        // Act
        $cmsPageVersionSearchListener = new CmsPageVersionSearchListener();
        $cmsPageVersionSearchListener->setFacade($this->getCmsPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsVersionTableMap::COL_FK_CMS_PAGE => 1,
            ]),
        ];
        $cmsPageVersionSearchListener->handleBulk($eventTransfers, CmsEvents::ENTITY_SPY_CMS_VERSION_CREATE);

        // Assert
        $afterCount = SpyCmsPageSearchQuery::create()->count();
        $this->assertSame($beforeCount + 2, $afterCount);
        $this->assertCmsPageSearch();
    }

    /**
     * @return void
     */
    public function testCmsPageUrlSearchListenerStoreData()
    {
        SpyCmsPageSearchQuery::create()->filterByFkCmsPage(1)->delete();
        $beforeCount = SpyCmsPageSearchQuery::create()->count();

        // Act
        $cmsPageUrlSearchListener = new CmsPageUrlSearchListener();
        $cmsPageUrlSearchListener->setFacade($this->getCmsPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PAGE => 1,
            ]),
        ];
        $cmsPageUrlSearchListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_CREATE);

        // Assert
        $afterCount = SpyCmsPageSearchQuery::create()->count();
        $this->assertSame($beforeCount + 2, $afterCount);
        $this->assertCmsPageSearch();
    }

    /**
     * @return void
     */
    public function testCmsPageSearchListenerStoreData()
    {
        SpyCmsPageSearchQuery::create()->filterByFkCmsPage(1)->delete();
        $beforeCount = SpyCmsPageSearchQuery::create()->count();

        // Act
        $cmsPageSearchListener = new CmsPageSearchListener();
        $cmsPageSearchListener->setFacade($this->getCmsPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $cmsPageSearchListener->handleBulk($eventTransfers, CmsEvents::CMS_VERSION_PUBLISH);

        // Assert
        $afterCount = SpyCmsPageSearchQuery::create()->count();
        $this->assertSame($beforeCount + 2, $afterCount);
        $this->assertCmsPageSearch();
    }

    /**
     * @return \Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacade
     */
    protected function getCmsPageSearchFacade()
    {
        $searchFacadeMock = $this->getMockBuilder(CmsPageSearchToSearchBridge::class)->disableOriginalConstructor()->getMock();
        $searchFacadeMock->method('transformPageMapToDocumentByMapperName')->willReturn([]);
        $factory = new CmsPageSearchBusinessFactoryMock($searchFacadeMock);
        $factory->setConfig(new CmsPageSearchConfigMock());

        $facade = new CmsPageSearchFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return void
     */
    protected function assertCmsPageSearch()
    {
        $cmsPage = SpyCmsPageSearchQuery::create()->filterByLocale('en_US')->orderByIdCmsPageSearch()->findOneByFkCmsPage(1);
        $this->assertNotNull($cmsPage);
        $data = $cmsPage->getStructuredData();
        $encodedData = json_decode($data, true);
        $this->assertSame('Imprint', $encodedData['name']);
    }
}
