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
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacade;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractPublishStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductStoreAbstractStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductAbstractStorageListener;
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
 * @group PriceProductAbstractStorageListenerTest
 * Add your own group annotations below this line
 */
class PriceProductAbstractStorageListenerTest extends Unit
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
    public function testPriceProductAbstractPublishStorageListenerStoreData()
    {
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->count();

        $priceProductAbstractPublishStorageListener = new PriceProductAbstractPublishStorageListener();
        $priceProductAbstractPublishStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $priceProductAbstractPublishStorageListener->handleBulk($eventTransfers, PriceProductEvents::PRICE_ABSTRACT_PUBLISH);

        // Assert
        $this->assertPriceProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductAbstractStorageListenerStoreData()
    {
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->count();

        $priceProductAbstractStorageListener = new PriceProductAbstractStorageListener();
        $priceProductAbstractStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $priceProductAbstractStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE);

        // Assert
        $this->assertPriceProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceProductStoreAbstractStorageListenerStoreData()
    {
        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productAbstractIds = $priceProductQueryContainer->queryAllProductAbstractIdsByPriceProductIds([1])->find()->getData();
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->count();

        $priceProductStoreAbstractStorageListener = new PriceProductStoreAbstractStorageListener();
        $priceProductStoreAbstractStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT => 1,
            ]),
        ];
        $priceProductStoreAbstractStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE);

        // Assert
        $this->assertPriceProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testPriceTypeProductAbstractStorageListenerStoreData()
    {
        $priceProductQueryContainer = new PriceProductStorageQueryContainer();
        $productAbstractIds = $priceProductQueryContainer->queryAllProductAbstractIdsByPriceTypeIds([1])->find()->getData();
        SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->count();

        $priceTypeProductAbstractStorageListener = new PriceTypeProductAbstractStorageListener();
        $priceTypeProductAbstractStorageListener->setFacade($this->getPriceProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $priceTypeProductAbstractStorageListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_TYPE_CREATE);

        // Assert
        $priceProductAbstractStorageCount = SpyPriceProductAbstractStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->count();
        $this->assertSame($beforeCount + count($productAbstractIds), $priceProductAbstractStorageCount);
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
    protected function assertPriceProductAbstractStorage($beforeCount)
    {
        $priceProductAbstractStorageCount = SpyPriceProductAbstractStorageQuery::create()->count();
        $this->assertSame($beforeCount + 1, $priceProductAbstractStorageCount);
        $spyPriceProductAbstractStorage = SpyPriceProductAbstractStorageQuery::create()->orderByIdPriceProductAbstractStorage()->findOneByFkProductAbstract(1);
        $this->assertNotNull($spyPriceProductAbstractStorage);
        $data = $spyPriceProductAbstractStorage->getData();
        $this->assertSame(2, count($data['prices']));
    }
}
