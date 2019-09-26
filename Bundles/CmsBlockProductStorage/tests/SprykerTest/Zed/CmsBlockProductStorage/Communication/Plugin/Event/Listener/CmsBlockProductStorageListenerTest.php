<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\CmsBlockProductConnector\Persistence\Map\SpyCmsBlockProductConnectorTableMap;
use Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorageQuery;
use Spryker\Zed\CmsBlockProductConnector\Dependency\CmsBlockProductConnectorEvents;
use Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageBusinessFactory;
use Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageFacade;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener\CmsBlockProductConnectorEntityStoragePublishListener;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener\CmsBlockProductConnectorEntityStorageUnpublishListener;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener\CmsBlockProductConnectorPublishStorageListener;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener\CmsBlockProductConnectorStorageListener;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener\CmsBlockProductConnectorStoragePublishListener;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener\CmsBlockProductConnectorStorageUnpublishListener;
use SprykerTest\Zed\CmsBlockProductStorage\CmsBlockProductStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockProductStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CmsBlockProductStorageListenerTest
 * Add your own group annotations below this line
 */
class CmsBlockProductStorageListenerTest extends Unit
{
    protected const EXPECTED_BLOCK_NAME_COUNT = 1;

    /**
     * @var \SprykerTest\Zed\CmsBlockProductStorage\CmsBlockProductStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->productAbstractTransfer = $this->tester->haveProductAbstract();

        $idProductAbstracts = [$this->productAbstractTransfer->getIdProductAbstract()];

        $cmsBlockTransfer = $this->tester->haveCmsBlock();
        $cmsBlockTransfer->setIdProductAbstracts($idProductAbstracts);

        $this->tester->getCmsBlockProductConnectorFacade()->updateCmsBlockProductAbstractRelations($cmsBlockTransfer);
    }

    /**
     * @return void
     */
    public function testCmsBlockProductConnectorPublishStorageListenerStoreData(): void
    {
        SpyCmsBlockProductStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyCmsBlockProductStorageQuery::create()->count();

        $cmsBlockProductConnectorPublishStorageListener = new CmsBlockProductConnectorPublishStorageListener();
        $cmsBlockProductConnectorPublishStorageListener->setFacade($this->getCmsBlockProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];
        $cmsBlockProductConnectorPublishStorageListener->handleBulk($eventTransfers, CmsBlockProductConnectorEvents::CMS_BLOCK_PRODUCT_CONNECTOR_PUBLISH);

        // Assert
        $this->assertCmsBlockProductStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockProductConnectorStoragePublishListener(): void
    {
        SpyCmsBlockProductStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyCmsBlockProductStorageQuery::create()->count();

        $cmsBlockProductConnectorStoragePublishListener = new CmsBlockProductConnectorStoragePublishListener();
        $cmsBlockProductConnectorStoragePublishListener->setFacade($this->getCmsBlockProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];
        $cmsBlockProductConnectorStoragePublishListener->handleBulk($eventTransfers, CmsBlockProductConnectorEvents::CMS_BLOCK_PRODUCT_CONNECTOR_PUBLISH);

        // Assert
        $this->assertCmsBlockProductStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockProductConnectorStorageUnpublishListener(): void
    {
        $cmsBlockProductConnectorStorageUnpublishListener = new CmsBlockProductConnectorStorageUnpublishListener();
        $cmsBlockProductConnectorStorageUnpublishListener->setFacade($this->getCmsBlockProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];

        $cmsBlockProductConnectorStorageUnpublishListener->handleBulk($eventTransfers, CmsBlockProductConnectorEvents::CMS_BLOCK_PRODUCT_CONNECTOR_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyCmsBlockProductStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->count());
    }

    /**
     * @return void
     */
    public function testCmsBlockProductConnectorStorageListenerStoreData(): void
    {
        SpyCmsBlockProductStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyCmsBlockProductStorageQuery::create()->count();

        $cmsBlockProductConnectorStorageListener = new CmsBlockProductConnectorStorageListener();
        $cmsBlockProductConnectorStorageListener->setFacade($this->getCmsBlockProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockProductConnectorTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];
        $cmsBlockProductConnectorStorageListener->handleBulk($eventTransfers, CmsBlockProductConnectorEvents::ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_CREATE);

        // Assert
        $this->assertCmsBlockProductStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockProductConnectorEntityStoragePublishListener(): void
    {
        SpyCmsBlockProductStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyCmsBlockProductStorageQuery::create()->count();

        $cmsBlockProductConnectorEntityStoragePublishListener = new CmsBlockProductConnectorEntityStoragePublishListener();
        $cmsBlockProductConnectorEntityStoragePublishListener->setFacade($this->getCmsBlockProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockProductConnectorTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];
        $cmsBlockProductConnectorEntityStoragePublishListener->handleBulk($eventTransfers, CmsBlockProductConnectorEvents::ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_CREATE);

        // Assert
        $this->assertCmsBlockProductStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockProductConnectorEntityStorageUnpublishListener(): void
    {
        $cmsBlockProductConnectorStorageListener = new CmsBlockProductConnectorEntityStorageUnpublishListener();
        $cmsBlockProductConnectorStorageListener->setFacade($this->getCmsBlockProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockProductConnectorTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];
        $cmsBlockProductConnectorStorageListener->handleBulk($eventTransfers, CmsBlockProductConnectorEvents::ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_DELETE);

        // Assert
        $this->assertSame(0, SpyCmsBlockProductStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->count());
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageFacade
     */
    protected function getCmsBlockProductStorageFacade()
    {
        $factory = new CmsBlockProductStorageBusinessFactory();
        $factory->setConfig(new CmsBlockProductStorageConfigMock());

        $facade = new CmsBlockProductStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertCmsBlockProductStorage(int $beforeCount): void
    {
        $count = SpyCmsBlockProductStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $count);
        $cmsBlockProductStorage = SpyCmsBlockProductStorageQuery::create()->orderByIdCmsBlockProductStorage()->findOneByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract());
        $this->assertNotNull($cmsBlockProductStorage);
        $data = $cmsBlockProductStorage->getData();
        $this->assertSame(static::EXPECTED_BLOCK_NAME_COUNT, count($data['block_names']));
    }
}
