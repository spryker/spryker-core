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
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\CmsBlockProductConnector\Dependency\CmsBlockProductConnectorEvents;
use Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageBusinessFactory;
use Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageFacade;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener\CmsBlockProductConnectorPublishStorageListener;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener\CmsBlockProductConnectorStorageListener;
use SprykerTest\Zed\CmsBlockProductStorage\CmsBlockProductStorageConfigMock;

/**
 * Auto-generated group annotations
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
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }

        $this->productAbstractTransfer = $this->tester->haveProductAbstract();

        $idProductAbstracts = [$this->productAbstractTransfer->getIdProductAbstract()];

        $cmsBlockTransfer = $this->tester->haveCmsBlock();
        $cmsBlockTransfer->setIdProductAbstracts($idProductAbstracts);

        $this->tester->getCmsBlockProductConnectorFacade()->updateCmsBlockProductAbstractRelations($cmsBlockTransfer);
    }

    /**
     * @return void
     */
    public function testCmsBlockProductConnectorPublishStorageListenerStoreData()
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
    public function testCmsBlockProductConnectorStorageListenerStoreData()
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
    protected function assertCmsBlockProductStorage($beforeCount)
    {
        $count = SpyCmsBlockProductStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $count);
        $cmsBlockProductStorage = SpyCmsBlockProductStorageQuery::create()->orderByIdCmsBlockProductStorage()->findOneByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract());
        $this->assertNotNull($cmsBlockProductStorage);
        $data = $cmsBlockProductStorage->getData();
        $this->assertSame(static::EXPECTED_BLOCK_NAME_COUNT, count($data['block_names']));
    }
}
