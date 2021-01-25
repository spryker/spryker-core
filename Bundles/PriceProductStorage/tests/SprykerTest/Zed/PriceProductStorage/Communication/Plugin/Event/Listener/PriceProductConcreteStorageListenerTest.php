<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacade;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteEntityStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteEntityStorageUnpublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcretePublishStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteStorageUnpublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductStoreConcreteStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductConcreteStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductConcreteStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductConcreteStorageUnpublishListener;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainer;
use SprykerTest\Zed\PriceProductStorage\PriceProductStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group PriceProductConcreteStorageListenerTest
 * Add your own group annotations below this line
 */
class PriceProductConcreteStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductStorage\PriceProductStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected $priceProductTransfer;

    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->tester->isSuiteProject()) {
            throw new SkippedTestError('Warning: not in suite environment');
        }

        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductOverride = [
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRICE_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
        ];

        $this->priceProductTransfer = $this->tester->havePriceProduct($priceProductOverride);
    }

    /**
     * @return void
     */
    public function testPriceProductConcretePublishStorageListenerStoreData(): void
    {
        // Prepare
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct($this->priceProductTransfer->getIdProduct())->delete();
        $beforeCount = SpyPriceProductConcreteStorageQuery::create()->count();

        $priceProductConcretePublishStorageListener = new PriceProductConcretePublishStorageListener();
        $priceProductConcretePublishStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getIdProduct()),
        ];

        // Action
        $priceProductConcretePublishStorageListener->handleBulk($eventTransfers, PriceProductEvents::PRICE_CONCRETE_PUBLISH);

        // Assert
        $this->assertPriceProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductConcretePublishStoragePublishListener(): void
    {
        // Prepare
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct($this->priceProductTransfer->getIdProduct())->delete();
        $beforeCount = SpyPriceProductConcreteStorageQuery::create()->count();

        $priceProductConcreteStoragePublishListener = new PriceProductConcreteStoragePublishListener();
        $priceProductConcreteStoragePublishListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getIdProduct()),
        ];

        // Action
        $priceProductConcreteStoragePublishListener->handleBulk($eventTransfers, PriceProductEvents::PRICE_CONCRETE_PUBLISH);

        // Assert
        $this->assertPriceProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductConcretePublishStorageUnPublishListener(): void
    {
        // Prepare
        $priceProductConcreteStorageUnpublishListener = new PriceProductConcreteStorageUnpublishListener();
        $priceProductConcreteStorageUnpublishListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getIdProduct()),
        ];

        // Action
        $priceProductConcreteStorageUnpublishListener->handleBulk($eventTransfers, PriceProductEvents::PRICE_CONCRETE_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct($this->priceProductTransfer->getIdProduct())->count());
    }

    /**
     * @return void
     */
    public function testPriceProductConcreteStorageListenerStoreData(): void
    {
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct($this->priceProductTransfer->getIdProduct())->delete();
        $beforeCount = SpyPriceProductConcreteStorageQuery::create()->count();

        $priceProductConcreteStorageListener = new PriceProductConcreteStorageListener();
        $priceProductConcreteStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductTableMap::COL_FK_PRODUCT => $this->priceProductTransfer->getIdProduct(),
            ]),
        ];
        $priceProductConcreteStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE);

        // Assert
        $this->assertPriceProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductConcreteEntityStoragePublishListener(): void
    {
        // Prepare
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct($this->priceProductTransfer->getIdProduct())->delete();
        $beforeCount = SpyPriceProductConcreteStorageQuery::create()->count();

        $priceProductConcreteEntityStoragePublishListener = new PriceProductConcreteEntityStoragePublishListener();
        $priceProductConcreteEntityStoragePublishListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductTableMap::COL_FK_PRODUCT => $this->priceProductTransfer->getIdProduct(),
            ]),
        ];

        // Action
        $priceProductConcreteEntityStoragePublishListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE);

        // Assert
        $this->assertPriceProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductConcreteEntityStorageUnpublishListener(): void
    {
        // Prepare
        $priceProductConcreteStorageListener = new PriceProductConcreteEntityStorageUnpublishListener();
        $priceProductConcreteStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductTableMap::COL_FK_PRODUCT => $this->priceProductTransfer->getIdProduct(),
            ]),
        ];

        // Action
        $priceProductConcreteStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE);

        // Assert
        $this->assertSame(0, SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct($this->priceProductTransfer->getIdProduct())->count());
    }

    /**
     * @return void
     */
    public function testPriceProductStoreConcreteStorageListenerStoreData(): void
    {
        // Prepare
        $priceProductIds = [
            $this->priceProductTransfer->getIdPriceProduct(),
        ];

        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productConcreteIds = $priceProductQueryContainer->queryAllProductIdsByPriceProductIds($priceProductIds)->find()->getData();

        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct_In($productConcreteIds)->delete();
        $beforeCount = SpyPriceProductConcreteStorageQuery::create()->count();

        $priceProductStoreConcreteStorageListener = new PriceProductStoreConcreteStorageListener();
        $priceProductStoreConcreteStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT => $this->priceProductTransfer->getIdPriceProduct(),
            ]),
        ];

        // Action
        $priceProductStoreConcreteStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE);

        // Assert
        $priceProductConcreteStorageCount = SpyPriceProductConcreteStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $priceProductConcreteStorageCount);
    }

    /**
     * @return void
     */
    public function testPriceTypeProductConcreteStorageListenerStoreData(): void
    {
        // Prepare
        $priceTypeIds = [
            $this->priceProductTransfer->getFkPriceType(),
        ];

        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productConcreteIds = $priceProductQueryContainer->queryAllProductIdsByPriceTypeIds($priceTypeIds)->find()->getData();
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct_In($productConcreteIds)->delete();
        $beforeCount = SpyPriceProductConcreteStorageQuery::create()->count();

        $priceTypeProductConcreteStorageListener = new PriceTypeProductConcreteStorageListener();
        $priceTypeProductConcreteStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getFkPriceType()),
        ];

        // Action
        $priceTypeProductConcreteStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_TYPE_CREATE);

        // Assert
        $priceProductConcreteStorageCount = SpyPriceProductConcreteStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $priceProductConcreteStorageCount);
    }

    /**
     * @return void
     */
    public function testPriceTypeProductConcreteStoragePublishListener(): void
    {
        // Prepare
        $priceTypeIds = [
            $this->priceProductTransfer->getFkPriceType(),
        ];

        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productConcreteIds = $priceProductQueryContainer->queryAllProductIdsByPriceTypeIds($priceTypeIds)->find()->getData();
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct_In($productConcreteIds)->delete();
        $beforeCount = SpyPriceProductConcreteStorageQuery::create()->count();

        $priceTypeProductConcreteStoragePublishListener = new PriceTypeProductConcreteStoragePublishListener();
        $priceTypeProductConcreteStoragePublishListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getFkPriceType()),
        ];

        // Action
        $priceTypeProductConcreteStoragePublishListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_TYPE_CREATE);

        // Assert
        $priceProductConcreteStorageCount = SpyPriceProductConcreteStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $priceProductConcreteStorageCount);
    }

    /**
     * @return void
     */
    public function testPriceTypeProductConcreteStorageUnpublishListener(): void
    {
        // Prepare
        $priceTypeProductConcreteStorageListener = new PriceTypeProductConcreteStorageUnpublishListener();
        $priceTypeProductConcreteStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getFkPriceType()),
        ];

        // Action
        $priceTypeProductConcreteStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_TYPE_DELETE);

        // Assert
        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productConcreteIds = $priceProductQueryContainer->queryAllProductIdsByPriceTypeIds([$this->priceProductTransfer->getFkPriceType()])
            ->find()
            ->getData();
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct_In($productConcreteIds);

        $this->assertSame(0, SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct_In($productConcreteIds)->count());
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacade
     */
    protected function getPriceProductStorageFacade(): PriceProductStorageFacade
    {
        $factory = new PriceProductStorageBusinessFactory();
        $factory->setConfig(new PriceProductStorageConfigMock());

        $facade = new PriceProductStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertPriceProductConcreteStorage(int $beforeCount): void
    {
        $priceProductConcreteStorageCount = SpyPriceProductConcreteStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $priceProductConcreteStorageCount);
        $spyPriceProductConcreteStorage = SpyPriceProductConcreteStorageQuery::create()->orderByIdPriceProductConcreteStorage()->findOneByFkProduct($this->priceProductTransfer->getIdProduct());
        $this->assertNotNull($spyPriceProductConcreteStorage);
        $data = $spyPriceProductConcreteStorage->getData();
        $this->assertSame(1, count($data['prices']));
    }
}
