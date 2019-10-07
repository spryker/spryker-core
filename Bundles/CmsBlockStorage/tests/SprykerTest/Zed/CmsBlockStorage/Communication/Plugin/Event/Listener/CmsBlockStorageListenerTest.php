<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorageQuery;
use Spryker\Zed\CmsBlock\Dependency\CmsBlockEvents;
use Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageBusinessFactory;
use Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacade;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockGlossaryKeyMappingBlockStorageListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockGlossaryKeyMappingBlockStoragePublishListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockStorageListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockStoragePublishListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockStorageUnpublishListener;
use SprykerTest\Zed\CmsBlockStorage\CmsBlockStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CmsBlockStorageListenerTest
 * Add your own group annotations below this line
 */
class CmsBlockStorageListenerTest extends Unit
{
    /**
     * @return void
     */
    public function testCmsBlockStorageListenerStoreData(): void
    {
        SpyCmsBlockStorageQuery::create()->filterByFkCmsBlock(1)->delete();
        $beforeCount = SpyCmsBlockStorageQuery::create()->count();

        $cmsBlockStorageListener = new CmsBlockStorageListener();
        $cmsBlockStorageListener->setFacade($this->getCmsBlockStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $cmsBlockStorageListener->handleBulk($eventTransfers, CmsBlockEvents::CMS_BLOCK_PUBLISH);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockStoragePublishListener(): void
    {
        SpyCmsBlockStorageQuery::create()->filterByFkCmsBlock(1)->delete();
        $beforeCount = SpyCmsBlockStorageQuery::create()->count();

        $cmsBlockStoragePublishListener = new CmsBlockStoragePublishListener();
        $cmsBlockStoragePublishListener->setFacade($this->getCmsBlockStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $cmsBlockStoragePublishListener->handleBulk($eventTransfers, CmsBlockEvents::CMS_BLOCK_PUBLISH);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockStorageUnpublishListener(): void
    {
        $cmsBlockStorageUnpublishListener = new CmsBlockStorageUnpublishListener();
        $cmsBlockStorageUnpublishListener->setFacade($this->getCmsBlockStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $cmsBlockStorageUnpublishListener->handleBulk($eventTransfers, CmsBlockEvents::CMS_BLOCK_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyCmsBlockStorageQuery::create()->filterByFkCmsBlock(1)->count());
    }

    /**
     * @return void
     */
    public function testCmsBlockGlossaryKeyMappingBlockStorageListenerStoreData(): void
    {
        SpyCmsBlockStorageQuery::create()->filterByFkCmsBlock(1)->delete();
        $beforeCount = SpyCmsBlockStorageQuery::create()->count();

        $cmsBlockGlossaryKeyMappingBlockStorageListener = new CmsBlockGlossaryKeyMappingBlockStorageListener();
        $cmsBlockGlossaryKeyMappingBlockStorageListener->setFacade($this->getCmsBlockStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK => 1,
            ]),
        ];
        $cmsBlockGlossaryKeyMappingBlockStorageListener->handleBulk($eventTransfers, CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_CREATE);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockGlossaryKeyMappingBlockStoragePublishListener(): void
    {
        SpyCmsBlockStorageQuery::create()->filterByFkCmsBlock(1)->delete();
        $beforeCount = SpyCmsBlockStorageQuery::create()->count();

        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener = new CmsBlockGlossaryKeyMappingBlockStoragePublishListener();
        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener->setFacade($this->getCmsBlockStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK => 1,
            ]),
        ];
        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener->handleBulk($eventTransfers, CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_CREATE);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockGlossaryKeyMappingBlockStorageUnpublishListener(): void
    {
        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener = new CmsBlockGlossaryKeyMappingBlockStoragePublishListener();
        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener->setFacade($this->getCmsBlockStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK => 1,
            ]),
        ];
        $cmsBlockGlossaryKeyMappingBlockStoragePublishListener->handleBulk($eventTransfers, CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_DELETE);

        // Assert
        $this->assertGreaterThan(1, SpyCmsBlockStorageQuery::create()->filterByFkCmsBlock(1)->count());
    }

    /**
     * @return \Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacade
     */
    protected function getCmsBlockStorageFacade()
    {
        $factory = new CmsBlockStorageBusinessFactory();
        $factory->setConfig(new CmsBlockStorageConfigMock());

        $facade = new CmsBlockStorageFacade();
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
        $count = SpyCmsBlockStorageQuery::create()->count();

        $cmsBlockStorage = SpyCmsBlockStorageQuery::create()
            ->filterByLocale('en_US')
            ->orderByIdCmsBlockStorage()
            ->findOneByFkCmsBlock(1);
        $this->assertNotNull($cmsBlockStorage);

        $data = $cmsBlockStorage->getData();
        $this->assertSame('Teaser for home page', $data['name']);
    }
}
