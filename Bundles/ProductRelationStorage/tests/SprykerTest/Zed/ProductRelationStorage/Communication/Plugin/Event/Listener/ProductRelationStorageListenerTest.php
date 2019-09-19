<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorageQuery;
use Spryker\Zed\ProductRelation\Business\ProductRelationFacade;
use Spryker\Zed\ProductRelation\Dependency\ProductRelationEvents;
use Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageBusinessFactory;
use Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationProductAbstractStorageListener;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationPublishStorageListener;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationStorageListener;
use SprykerTest\Zed\ProductRelationStorage\ProductRelationStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductRelationStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductRelationStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductRelationStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductRelationStorage\ProductRelationStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransferRelated;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->productAbstractTransferRelated = $this->tester->haveProductAbstract();

        $localizedAttributes = $this->tester->generateLocalizedAttributes();
        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransfer, $localizedAttributes);
        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransferRelated, $localizedAttributes);

        $this->tester->haveProductRelation(
            $this->productAbstractTransfer->getSku(),
            $this->productAbstractTransferRelated->getIdProductAbstract()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\ProductRelationFacade
     */
    protected function createProductRelationFacade()
    {
        return new ProductRelationFacade();
    }

    /**
     * @return void
     */
    public function testProductRelationPublishStorageListenerStoreData()
    {
        SpyProductAbstractRelationStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()->count();

        $productRelationPublishStorageListener = new ProductRelationPublishStorageListener();
        $productRelationPublishStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransferRelated->getIdProductAbstract()),
        ];
        $productRelationPublishStorageListener->handleBulk($eventTransfers, ProductRelationEvents::PRODUCT_ABSTRACT_RELATION_PUBLISH);

        // Assert
        $this->assertProductAbstractRelationStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductRelationStorageListenerStoreData()
    {
        SpyProductAbstractRelationStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()->count();

        $productRelationStorageListener = new ProductRelationStorageListener();
        $productRelationStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransferRelated->getIdProductAbstract(),
            ]),
        ];
        $productRelationStorageListener->handleBulk($eventTransfers, ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_CREATE);

        // Assert
        $this->assertProductAbstractRelationStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductRelationProductAbstractStorageListenerStoreData()
    {
        SpyProductAbstractRelationStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()->count();

        $productRelationProductAbstractStorageListener = new ProductRelationProductAbstractStorageListener();
        $productRelationProductAbstractStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransferRelated->getIdProductAbstract(),
            ]),
        ];
        $productRelationProductAbstractStorageListener->handleBulk($eventTransfers, ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_CREATE);

        // Assert
        $this->assertProductAbstractRelationStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade
     */
    protected function getProductRelationStorageFacade()
    {
        $factory = new ProductRelationStorageBusinessFactory();
        $factory->setConfig(new ProductRelationStorageConfigMock());

        $facade = new ProductRelationStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractRelationStorage($beforeCount)
    {
        $productRelationStorageCount = SpyProductAbstractRelationStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $productRelationStorageCount);
        $productAbstractRelationStorage = SpyProductAbstractRelationStorageQuery::create()->orderByIdProductAbstractRelationStorage()->findOneByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract());
        $this->assertNotNull($productAbstractRelationStorage);
        $data = $productAbstractRelationStorage->getData();
        $this->assertSame(1, count($data['product_relations']));
    }
}
