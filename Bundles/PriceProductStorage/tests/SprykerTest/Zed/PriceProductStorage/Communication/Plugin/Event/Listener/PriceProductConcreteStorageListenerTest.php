<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacade;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcretePublishStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductStoreConcreteStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductConcreteStorageListener;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainer;
use SprykerTest\Zed\PriceProductStorage\PriceProductStorageConfigMock;

/**
 * Auto-generated group annotations
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
    public function testPriceProductConcretePublishStorageListenerStoreData()
    {
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct(1)->delete();
        $beforeCount = SpyPriceProductConcreteStorageQuery::create()->count();

        $priceProductConcretePublishStorageListener = new PriceProductConcretePublishStorageListener();
        $priceProductConcretePublishStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $priceProductConcretePublishStorageListener->handleBulk($eventTransfers, PriceProductEvents::PRICE_CONCRETE_PUBLISH);

        // Assert
        $this->assertPriceProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductConcreteStorageListenerStoreData()
    {
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct(1)->delete();
        $beforeCount = SpyPriceProductConcreteStorageQuery::create()->count();

        $priceProductConcreteStorageListener = new PriceProductConcreteStorageListener();
        $priceProductConcreteStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductTableMap::COL_FK_PRODUCT => 1,
            ]),
        ];
        $priceProductConcreteStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE);

        // Assert
        $this->assertPriceProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductStoreConcreteStorageListenerStoreData()
    {
        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productConcreteIds = $priceProductQueryContainer->queryAllProductIdsByPriceProductIds([52])->find()->getData();
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct_In($productConcreteIds)->delete();
        $beforeCount = SpyPriceProductConcreteStorageQuery::create()->count();

        $priceProductStoreConcreteStorageListener = new PriceProductStoreConcreteStorageListener();
        $priceProductStoreConcreteStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT => 52,
            ]),
        ];
        $priceProductStoreConcreteStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE);

        // Assert
        $priceProductConcreteStorageCount = SpyPriceProductConcreteStorageQuery::create()->count();
        $this->assertSame($beforeCount + 1, $priceProductConcreteStorageCount);
    }

    /**
     * @return void
     */
    public function testPriceTypeProductConcreteStorageListenerStoreData()
    {
        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productConcreteIds = $priceProductQueryContainer->queryAllProductIdsByPriceTypeIds([1])->find()->getData();
        SpyPriceProductConcreteStorageQuery::create()->filterByFkProduct_In($productConcreteIds)->delete();

        $priceTypeProductConcreteStorageListener = new PriceTypeProductConcreteStorageListener();
        $priceTypeProductConcreteStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $priceTypeProductConcreteStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_TYPE_CREATE);

        // Assert
        $priceProductConcreteStorageCount = SpyPriceProductConcreteStorageQuery::create()->count();
        $this->assertSame(74, $priceProductConcreteStorageCount);
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacade
     */
    protected function getPriceProductStorageFacade()
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
    protected function assertPriceProductConcreteStorage($beforeCount)
    {
        $priceProductConcreteStorageCount = SpyPriceProductConcreteStorageQuery::create()->count();
        $this->assertSame($beforeCount + 1, $priceProductConcreteStorageCount);
        $spyPriceProductConcreteStorage = SpyPriceProductConcreteStorageQuery::create()->findOneByFkProduct(1);
        $this->assertNotNull($spyPriceProductConcreteStorage);
        $data = $spyPriceProductConcreteStorage->getData();
        $this->assertSame(2, count($data['prices']));
    }
}
