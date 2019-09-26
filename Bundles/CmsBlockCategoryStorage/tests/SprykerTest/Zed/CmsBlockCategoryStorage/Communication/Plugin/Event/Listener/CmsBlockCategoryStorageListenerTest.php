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
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\CmsBlockCategoryConnectorEvents;
use Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageBusinessFactory;
use Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacade;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorEntityStoragePublishListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorEntityStorageUnpublishListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorPublishStorageListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorStorageListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorStoragePublishListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorStorageUnpublishListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryPositionStorageListener;
use Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainer;
use SprykerTest\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfigMock;

/**
 * Auto-generated group annotations
 *
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
    protected const FK_CATEGORY = 5;

    /**
     * @return void
     */
    public function testCmsBlockCategoryConnectorPublishStorageListenerStoreData(): void
    {
        SpyCmsBlockCategoryStorageQuery::create()->filterByFkCategory(static::FK_CATEGORY)->delete();
        $beforeCount = SpyCmsBlockCategoryStorageQuery::create()->count();

        $cmsBlockCategoryConnectorPublishStorageListener = new CmsBlockCategoryConnectorPublishStorageListener();
        $cmsBlockCategoryConnectorPublishStorageListener->setFacade($this->getCmsBlockCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(static::FK_CATEGORY),
        ];
        $cmsBlockCategoryConnectorPublishStorageListener->handleBulk($eventTransfers, CmsBlockCategoryConnectorEvents::CMS_BLOCK_CATEGORY_CONNECTOR_PUBLISH);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockCategoryConnectorStoragePublishListener(): void
    {
        SpyCmsBlockCategoryStorageQuery::create()->filterByFkCategory(static::FK_CATEGORY)->delete();
        $beforeCount = SpyCmsBlockCategoryStorageQuery::create()->count();

        $cmsBlockCategoryConnectorPublishStorageListener = new CmsBlockCategoryConnectorStoragePublishListener();
        $cmsBlockCategoryConnectorPublishStorageListener->setFacade($this->getCmsBlockCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(static::FK_CATEGORY),
        ];

        $cmsBlockCategoryConnectorPublishStorageListener->handleBulk($eventTransfers, CmsBlockCategoryConnectorEvents::CMS_BLOCK_CATEGORY_CONNECTOR_PUBLISH);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockCategoryConnectorStorageUnpublishListener(): void
    {
        $cmsBlockCategoryConnectorStorageUnpublishListener = new CmsBlockCategoryConnectorStorageUnpublishListener();
        $cmsBlockCategoryConnectorStorageUnpublishListener->setFacade($this->getCmsBlockCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(static::FK_CATEGORY),
        ];

        $cmsBlockCategoryConnectorStorageUnpublishListener->handleBulk($eventTransfers, CmsBlockCategoryConnectorEvents::CMS_BLOCK_CATEGORY_CONNECTOR_UNPUBLISH);

        // Assert
        $this->assertSame(1, SpyCmsBlockCategoryStorageQuery::create()->filterByFkCategory(static::FK_CATEGORY)->count());
    }

    /**
     * @return void
     */
    public function testCmsBlockCategoryConnectorStorageListenerStoreData(): void
    {
        SpyCmsBlockCategoryStorageQuery::create()->filterByFkCategory(static::FK_CATEGORY)->delete();
        $beforeCount = SpyCmsBlockCategoryStorageQuery::create()->count();

        $cmsBlockCategoryConnectorStorageListener = new CmsBlockCategoryConnectorStorageListener();
        $cmsBlockCategoryConnectorStorageListener->setFacade($this->getCmsBlockCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY => static::FK_CATEGORY,
            ]),
        ];
        $cmsBlockCategoryConnectorStorageListener->handleBulk($eventTransfers, CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_CREATE);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockCategoryConnectorEntityStoragePublishListener(): void
    {
        SpyCmsBlockCategoryStorageQuery::create()->filterByFkCategory(static::FK_CATEGORY)->delete();
        $beforeCount = SpyCmsBlockCategoryStorageQuery::create()->count();

        $cmsBlockCategoryConnectorEntityStoragePublishListener = new CmsBlockCategoryConnectorEntityStoragePublishListener();
        $cmsBlockCategoryConnectorEntityStoragePublishListener->setFacade($this->getCmsBlockCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY => static::FK_CATEGORY,
            ]),
        ];
        $cmsBlockCategoryConnectorEntityStoragePublishListener->handleBulk($eventTransfers, CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_CREATE);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockCategoryConnectorEntityStorageUnpublishListener(): void
    {
        $cmsBlockCategoryConnectorEntityStorageUnpublishListener = new CmsBlockCategoryConnectorEntityStorageUnpublishListener();
        $cmsBlockCategoryConnectorEntityStorageUnpublishListener->setFacade($this->getCmsBlockCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY => static::FK_CATEGORY,
            ]),
        ];
        $cmsBlockCategoryConnectorEntityStorageUnpublishListener->handleBulk($eventTransfers, CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_DELETE);

        // Assert
        $this->assertSame(1, SpyCmsBlockCategoryStorageQuery::create()->filterByFkCategory(static::FK_CATEGORY)->count());
    }

    /**
     * @return void
     */
    public function testCmsBlockCategoryPositionStorageListenerStoreData(): void
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
        $this->assertGreaterThan($beforeCount, $count);
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
    protected function assertCmsBlockCategoryStorage(int $beforeCount): void
    {
        $count = SpyCmsBlockCategoryStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $count);

        $cmsBlockCategoryStorage = SpyCmsBlockCategoryStorageQuery::create()
            ->orderByIdCmsBlockCategoryStorage()
            ->findOneByFkCategory(static::FK_CATEGORY);
        $this->assertNotNull($cmsBlockCategoryStorage);

        $data = $cmsBlockCategoryStorage->getData();
        $this->assertSame(1, count($data['cms_block_categories']));
    }
}
