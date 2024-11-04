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
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacade;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractEntityStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractPublishStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractStorageUnpublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductStoreAbstractStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductAbstractStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductAbstractStoragePublishListener;
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
 * @group PriceProductAbstractStorageListenerTest
 * Add your own group annotations below this line
 */
class PriceProductAbstractStorageListenerTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_PRICES_COUNT = 1;

    /**
     * @var \SprykerTest\Zed\PriceProductStorage\PriceProductStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected $priceProductTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->tester->isSuiteProject()) {
            $this->markTestSkipped('Warning: not in suite environment');
        }

        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $priceProductOverride = [
            PriceProductTransfer::ID_PRICE_PRODUCT => $productAbstractTransfer->getIdProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productAbstractTransfer->getSku(),
        ];

        $this->priceProductTransfer = $this->tester->havePriceProduct($priceProductOverride);
    }

    /**
     * @return void
     */
    public function testPriceProductAbstractPublishStorageListenerStoreData(): void
    {
        // Prepare
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->priceProductTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->count();

        $priceProductAbstractPublishStorageListener = new PriceProductAbstractPublishStorageListener();
        $priceProductAbstractPublishStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getIdProductAbstract()),
        ];

        // Action
        $priceProductAbstractPublishStorageListener->handleBulk($eventTransfers, PriceProductEvents::PRICE_ABSTRACT_PUBLISH);

        // Assert
        $this->assertPriceProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductAbstractStoragePublishListener(): void
    {
        // Prepare
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->priceProductTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->count();

        $priceProductAbstractStoragePublishListener = new PriceProductAbstractStoragePublishListener();
        $priceProductAbstractStoragePublishListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getIdProductAbstract()),
        ];

        // Action
        $priceProductAbstractStoragePublishListener->handleBulk($eventTransfers, PriceProductEvents::PRICE_ABSTRACT_PUBLISH);

        // Assert
        $this->assertPriceProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductAbstractStorageUnpublishListener(): void
    {
        // Prepare
        $priceProductAbstractStorageUnpublishListener = new PriceProductAbstractStorageUnpublishListener();
        $priceProductAbstractStorageUnpublishListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getIdProductAbstract()),
        ];

        // Action
        $priceProductAbstractStorageUnpublishListener->handleBulk($eventTransfers, PriceProductEvents::PRICE_ABSTRACT_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->priceProductTransfer->getIdProductAbstract())->count());
    }

    /**
     * @return void
     */
    public function testPriceProductAbstractStorageListenerStoreData(): void
    {
        // Prepare
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->priceProductTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->count();

        $priceProductAbstractStorageListener = new PriceProductAbstractStorageListener();
        $priceProductAbstractStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT => $this->priceProductTransfer->getIdProductAbstract(),
            ]),
        ];

        // Action
        $priceProductAbstractStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE);

        // Assert
        $this->assertPriceProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductAbstractEntityStoragePublishListener(): void
    {
        // Prepare
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract($this->priceProductTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->count();

        $priceProductAbstractEntityStoragePublishListener = new PriceProductAbstractEntityStoragePublishListener();
        $priceProductAbstractEntityStoragePublishListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT => $this->priceProductTransfer->getIdProductAbstract(),
            ]),
        ];

        // Action
        $priceProductAbstractEntityStoragePublishListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE);

        // Assert
        $this->assertPriceProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductStoreAbstractStorageListenerStoreData(): void
    {
        // Prepare
        $priceProductIds = [
            $this->priceProductTransfer->getIdPriceProduct(),
        ];

        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productAbstractIds = $priceProductQueryContainer->queryAllProductAbstractIdsByPriceProductIds($priceProductIds)->find()->getData();
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->count();

        $priceProductStoreAbstractStorageListener = new PriceProductStoreAbstractStorageListener();
        $priceProductStoreAbstractStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT => $this->priceProductTransfer->getIdPriceProduct(),
            ]),
        ];

        // Action
        $priceProductStoreAbstractStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE);

        // Assert
        $this->assertPriceProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceTypeProductAbstractStorageListenerStoreData(): void
    {
        // Prepare
        $priceTypeIds = [
            $this->priceProductTransfer->getFkPriceType(),
        ];

        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productAbstractIds = $priceProductQueryContainer->queryAllProductAbstractIdsByPriceTypeIds($priceTypeIds)->find()->getData();
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->count();

        $priceTypeProductAbstractStorageListener = new PriceTypeProductAbstractStorageListener();
        $priceTypeProductAbstractStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getFkPriceType()),
        ];

        // Action
        $priceTypeProductAbstractStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_TYPE_CREATE);

        // Assert
        $priceProductAbstractStorageCount = SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->count();
        $this->assertGreaterThanOrEqual($beforeCount + count($productAbstractIds), $priceProductAbstractStorageCount);
    }

    /**
     * @return void
     */
    public function testPriceTypeProductAbstractEntityStoragePublishListener(): void
    {
        // Prepare
        $priceTypeIds = [
            $this->priceProductTransfer->getFkPriceType(),
        ];

        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productAbstractIds = $priceProductQueryContainer->queryAllProductAbstractIdsByPriceTypeIds($priceTypeIds)->find()->getData();
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->count();

        $priceTypeProductAbstractStoragePublishListener = new PriceTypeProductAbstractStoragePublishListener();
        $priceTypeProductAbstractStoragePublishListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getFkPriceType()),
        ];

        // Action
        $priceTypeProductAbstractStoragePublishListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE);

        // Assert
        $priceProductAbstractStorageCount = SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->count();
        $this->assertGreaterThanOrEqual($beforeCount + count($productAbstractIds), $priceProductAbstractStorageCount);
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
    protected function assertPriceProductAbstractStorage(int $beforeCount): void
    {
        $priceProductAbstractStorageCount = SpyPriceProductAbstractStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $priceProductAbstractStorageCount);
        $spyPriceProductAbstractStorage = SpyPriceProductAbstractStorageQuery::create()->orderByIdPriceProductAbstractStorage()->findOneByFkProductAbstract($this->priceProductTransfer->getIdProductAbstract());
        $this->assertNotNull($spyPriceProductAbstractStorage);
        $data = $spyPriceProductAbstractStorage->getData();
        $this->assertSame(static::EXPECTED_PRICES_COUNT, count($data['prices']));
    }
}
