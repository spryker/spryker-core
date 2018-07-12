<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\CmsBlockCategoryConnectorEvents;
use Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageBusinessFactory;
use Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacade;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorPublishStorageListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorStorageListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryPositionStorageListener;
use Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainer;
use SprykerTest\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockCategoryStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CmsBlockCategoryStorageListenerTest
 * Add your own group annotations below this line
 */
class CmsBlockCategoryStorageListenerTest extends Unit
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
    public function testCmsBlockCategoryConnectorPublishStorageListenerStoreData()
    {
        SpyCmsBlockCategoryStorageQuery::create()->filterByFkCategory(5)->delete();
        $beforeCount = SpyCmsBlockCategoryStorageQuery::create()->count();

        $cmsBlockCategoryConnectorPublishStorageListener = new CmsBlockCategoryConnectorPublishStorageListener();
        $cmsBlockCategoryConnectorPublishStorageListener->setFacade($this->getCmsBlockCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(5),
        ];
        $cmsBlockCategoryConnectorPublishStorageListener->handleBulk($eventTransfers, CmsBlockCategoryConnectorEvents::CMS_BLOCK_CATEGORY_CONNECTOR_PUBLISH);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockCategoryConnectorStorageListenerStoreData()
    {
        SpyCmsBlockCategoryStorageQuery::create()->filterByFkCategory(5)->delete();
        $beforeCount = SpyCmsBlockCategoryStorageQuery::create()->count();

        $cmsBlockCategoryConnectorStorageListener = new CmsBlockCategoryConnectorStorageListener();
        $cmsBlockCategoryConnectorStorageListener->setFacade($this->getCmsBlockCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY => 5,
            ]),
        ];
        $cmsBlockCategoryConnectorStorageListener->handleBulk($eventTransfers, CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_CREATE);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockCategoryPositionStorageListenerStoreData()
    {
        $queryContainer = new CmsBlockCategoryStorageQueryContainer();
        $categoryIds = $queryContainer->queryCategoryIdsByPositionIds([1])->find()->getData();
        SpyCmsBlockCategoryStorageQuery::create()->filterByFkCategory_In($categoryIds)->delete();

        $beforeCount = SpyCmsBlockCategoryStorageQuery::create()->count();

        $cmsBlockCategoryPositionStorageListener = new CmsBlockCategoryPositionStorageListener();
        $cmsBlockCategoryPositionStorageListener->setFacade($this->getCmsBlockCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $cmsBlockCategoryPositionStorageListener->handleBulk($eventTransfers, CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_POSITION_CREATE);

        // Assert
        $count = SpyCmsBlockCategoryStorageQuery::create()->count();
        $this->assertSame($beforeCount + 5, $count);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacade
     */
    protected function getCmsBlockCategoryStorageFacade()
    {
        $factory = new CmsBlockCategoryStorageBusinessFactory();
        $factory->setConfig(new CmsBlockCategoryStorageConfigMock());

        $facade = new CmsBlockCategoryStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertCmsBlockCategoryStorage($beforeCount)
    {
        $count = SpyCmsBlockCategoryStorageQuery::create()->count();
        $this->assertSame($beforeCount + 1, $count);
        $cmsBlockCategoryStorage = SpyCmsBlockCategoryStorageQuery::create()->orderByIdCmsBlockCategoryStorage()->findOneByFkCategory(5);
        $this->assertNotNull($cmsBlockCategoryStorage);
        $data = $cmsBlockCategoryStorage->getData();
        $this->assertSame(1, count($data['cms_block_categories']));
    }
}
