<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductGroupStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductGroupTransfer;
use Orm\Zed\ProductGroup\Persistence\Map\SpyProductAbstractGroupTableMap;
use Orm\Zed\ProductGroupStorage\Persistence\SpyProductAbstractGroupStorageQuery;
use Spryker\Zed\ProductGroup\Dependency\ProductGroupEvents;
use Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageBusinessFactory;
use Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageFacade;
use Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event\Listener\ProductAbstractGroupPublishStorageListener;
use Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event\Listener\ProductAbstractGroupStorageListener;
use SprykerTest\Zed\ProductGroupStorage\ProductGroupStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductGroupStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductGroupStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductGroupStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductGroupStorage\ProductGroupStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductGroupTransfer
     */
    protected $productGroupTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $localizedAttributes = $this->tester->generateLocalizedAttributes();

        $this->tester->addLocalizedAttributesToProductAbstract($productAbstractTransfer, $localizedAttributes);

        $productGroupOverride = [
            ProductGroupTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
            ],
        ];

        $this->productGroupTransfer = $this->tester->haveProductGroup($productGroupOverride);
    }

    /**
     * @return void
     */
    public function testProductAbstractGroupStorageListenerStoreData()
    {
        $fkProductAbstracts = $this->productGroupTransfer->getIdProductAbstracts();

        SpyProductAbstractGroupStorageQuery::create()->filterByFkProductAbstract_in($fkProductAbstracts)->delete();

        $beforeCount = SpyProductAbstractGroupStorageQuery::create()->count();

        $productAbstractGroupStorageListener = new ProductAbstractGroupStorageListener();
        $productAbstractGroupStorageListener->setFacade($this->getProductGroupStorageFacade());

        $eventTransfers = [];

        foreach ($fkProductAbstracts as $fkProductAbstract) {
            $eventTransfers[] = (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_ABSTRACT => $fkProductAbstract,
            ]);
        }

        $productAbstractGroupStorageListener->handleBulk($eventTransfers, ProductGroupEvents::ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_CREATE);

        // Assert
        $this->assertProductAbstractGroupStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractGroupPublishStorageListenerStoreData()
    {
        $fkProductAbstracts = $this->productGroupTransfer->getIdProductAbstracts();

        SpyProductAbstractGroupStorageQuery::create()->filterByFkProductAbstract_in($fkProductAbstracts)->delete();
        $beforeCount = SpyProductAbstractGroupStorageQuery::create()->count();

        $productAbstractGroupPublishStorageListener = new ProductAbstractGroupPublishStorageListener();
        $productAbstractGroupPublishStorageListener->setFacade($this->getProductGroupStorageFacade());

        $eventTransfers = [];

        foreach ($fkProductAbstracts as $fkProductAbstract) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($fkProductAbstract);
        }

        $productAbstractGroupPublishStorageListener->handleBulk($eventTransfers, ProductGroupEvents::PRODUCT_GROUP_PUBLISH);

        // Assert
        $this->assertProductAbstractGroupStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageFacade
     */
    protected function getProductGroupStorageFacade()
    {
        $factory = new ProductGroupStorageBusinessFactory();
        $factory->setConfig(new ProductGroupStorageConfigMock());

        $facade = new ProductGroupStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractGroupStorage($beforeCount)
    {
        $productGroupStorageCount = SpyProductAbstractGroupStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $productGroupStorageCount);

        $fkProductAbstracts = $this->productGroupTransfer->getIdProductAbstracts();
        $fkProductAbstract = current($fkProductAbstracts);

        $spyProductAbstractGroupStorage = SpyProductAbstractGroupStorageQuery::create()->orderByIdProductAbstractGroupStorage()->findOneByFkProductAbstract($fkProductAbstract);
        $this->assertNotNull($spyProductAbstractGroupStorage);
        $data = $spyProductAbstractGroupStorage->getData();
        $this->assertSame(2, count($data['group_product_abstract_ids']));
    }
}
