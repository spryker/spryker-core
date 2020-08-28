<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorageQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
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
    protected const STORE_DE = 'DE';

    protected const STORE_AT = 'AT';

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
    protected $productAbstractTransferAnotherStore;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransferRelated;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransferRelatedForAnotherStore;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->ensureProductRelationTableIsEmpty();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $storeDe = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_DE,
        ]);
        $storeAt = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_AT,
        ]);
        $storeRelationDeTransfer = (new StoreRelationTransfer())
            ->addStores($storeDe)
            ->addIdStores($storeDe->getIdStore());
        $storeRelationAtTransfer = (new StoreRelationTransfer())
            ->addIdStores($storeAt->getIdStore())
            ->addStores($storeAt);
        $this->productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::STORE_RELATION => $storeRelationDeTransfer,
        ]);
        $this->productAbstractTransferAnotherStore = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::STORE_RELATION => $storeRelationAtTransfer,
        ]);
        $this->productAbstractTransferRelated = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::STORE_RELATION => $storeRelationDeTransfer,
        ]);

        $this->productAbstractTransferRelatedForAnotherStore = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::STORE_RELATION => $storeRelationAtTransfer,
        ]);

        $localizedAttributes = $this->tester->generateLocalizedAttributes();
        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransfer, $localizedAttributes);
        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransferAnotherStore, $localizedAttributes);
        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransferRelated, $localizedAttributes);
        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransferRelatedForAnotherStore, $localizedAttributes);

        $this->tester->haveProductRelation(
            $this->productAbstractTransfer->getSku(),
            $this->productAbstractTransferRelated->getIdProductAbstract(),
            'test',
            'up-selling',
            $storeRelationDeTransfer
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\ProductRelationFacade
     */
    protected function createProductRelationFacade(): ProductRelationFacade
    {
        return new ProductRelationFacade();
    }

    /**
     * @return void
     */
    public function testProductRelationPublishStorageListenerStoreData(): void
    {
        SpyProductAbstractRelationStorageQuery::create()
            ->filterByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract())
            ->filterByStore(static::STORE_DE)
            ->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()
            ->filterByStore(static::STORE_DE)
            ->count();

        $productRelationPublishStorageListener = new ProductRelationPublishStorageListener();
        $productRelationPublishStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransferRelated->getIdProductAbstract()),
            (new EventEntityTransfer())->setId($this->productAbstractTransferRelatedForAnotherStore->getIdProductAbstract()),
        ];
        $productRelationPublishStorageListener->handleBulk($eventTransfers, ProductRelationEvents::PRODUCT_ABSTRACT_RELATION_PUBLISH);

        // Assert
        $this->assertProductAbstractRelationStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductRelationStorageListenerStoreData(): void
    {
        SpyProductAbstractRelationStorageQuery::create()
            ->filterByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract())
            ->filterByStore(static::STORE_DE)
            ->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()
            ->filterByStore(static::STORE_DE)
            ->count();

        $productRelationStorageListener = new ProductRelationStorageListener();
        $productRelationStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransferRelated->getIdProductAbstract(),
            ]),
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransferRelatedForAnotherStore->getIdProductAbstract(),
            ]),
        ];
        $productRelationStorageListener->handleBulk($eventTransfers, ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_CREATE);

        // Assert
        $this->assertProductAbstractRelationStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductRelationProductAbstractStorageListenerStoreData(): void
    {
        SpyProductAbstractRelationStorageQuery::create()
            ->filterByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract())
            ->filterByStore(static::STORE_DE)
            ->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()
            ->filterByStore(static::STORE_DE)
            ->count();

        $productRelationProductAbstractStorageListener = new ProductRelationProductAbstractStorageListener();
        $productRelationProductAbstractStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransferRelated->getIdProductAbstract(),
            ]),
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransferRelatedForAnotherStore->getIdProductAbstract(),
            ]),
        ];
        $productRelationProductAbstractStorageListener->handleBulk($eventTransfers, ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_CREATE);

        // Assert
        $this->assertProductAbstractRelationStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade
     */
    protected function getProductRelationStorageFacade(): ProductRelationStorageFacade
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
    protected function assertProductAbstractRelationStorage(int $beforeCount): void
    {
        $productRelationStorageCount = SpyProductAbstractRelationStorageQuery::create()
            ->filterByStore(static::STORE_DE)
            ->count();
        $this->assertGreaterThan($beforeCount, $productRelationStorageCount);
        $productAbstractRelationStorage = SpyProductAbstractRelationStorageQuery::create()
            ->orderByIdProductAbstractRelationStorage()
            ->filterByStore(static::STORE_DE)
            ->filterByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract())
            ->findOne();
        $this->assertNotNull($productAbstractRelationStorage);
        $data = $productAbstractRelationStorage->getData();
        $this->assertSame(1, count($data['product_relations']));
    }
}
