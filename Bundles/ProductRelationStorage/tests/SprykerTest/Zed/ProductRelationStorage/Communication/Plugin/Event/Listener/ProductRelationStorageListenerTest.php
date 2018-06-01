<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductRelation\Dependency\ProductRelationEvents;
use Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageBusinessFactory;
use Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationProductAbstractStorageListener;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationPublishStorageListener;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationStorageListener;
use SprykerTest\Zed\ProductRelationStorage\ProductRelationStorageConfigMock;

/**
 * Auto-generated group annotations
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
    public function testProductRelationPublishStorageListenerStoreData()
    {
        SpyProductAbstractRelationStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()->count();

        $productRelationPublishStorageListener = new ProductRelationPublishStorageListener();
        $productRelationPublishStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
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
        SpyProductAbstractRelationStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()->count();

        $productRelationStorageListener = new ProductRelationStorageListener();
        $productRelationStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
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
        SpyProductAbstractRelationStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()->count();

        $productRelationProductAbstractStorageListener = new ProductRelationProductAbstractStorageListener();
        $productRelationProductAbstractStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
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
        $this->assertSame($beforeCount + 1, $productRelationStorageCount);
        $productAbstractRelationStorage = SpyProductAbstractRelationStorageQuery::create()->orderByIdProductAbstractRelationStorage()->findOneByFkProductAbstract(1);
        $this->assertNotNull($productAbstractRelationStorage);
        $data = $productAbstractRelationStorage->getData();
        $this->assertSame(2, count($data['product_relations']));
    }
}
