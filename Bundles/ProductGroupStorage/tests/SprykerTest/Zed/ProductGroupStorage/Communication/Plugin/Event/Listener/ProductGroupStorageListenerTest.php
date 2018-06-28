<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductGroupStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductGroup\Persistence\Map\SpyProductAbstractGroupTableMap;
use Orm\Zed\ProductGroupStorage\Persistence\SpyProductAbstractGroupStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductGroup\Dependency\ProductGroupEvents;
use Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageBusinessFactory;
use Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageFacade;
use Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event\Listener\ProductAbstractGroupPublishStorageListener;
use Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event\Listener\ProductAbstractGroupStorageListener;
use SprykerTest\Zed\ProductGroupStorage\ProductGroupStorageConfigMock;

/**
 * Auto-generated group annotations
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
    public function testProductAbstractGroupStorageListenerStoreData()
    {
        SpyProductAbstractGroupStorageQuery::create()->filterByFkProductAbstract_in([1, 2, 3])->delete();
        $beforeCount = SpyProductAbstractGroupStorageQuery::create()->count();

        $productAbstractGroupStorageListener = new ProductAbstractGroupStorageListener();
        $productAbstractGroupStorageListener->setFacade($this->getProductGroupStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productAbstractGroupStorageListener->handleBulk($eventTransfers, ProductGroupEvents::ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_CREATE);

        // Assert
        $this->assertProductAbstractGroupStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractGroupPublishStorageListenerStoreData()
    {
        SpyProductAbstractGroupStorageQuery::create()->filterByFkProductAbstract_in([1, 2, 3])->delete();
        $beforeCount = SpyProductAbstractGroupStorageQuery::create()->count();

        $productAbstractGroupPublishStorageListener = new ProductAbstractGroupPublishStorageListener();
        $productAbstractGroupPublishStorageListener->setFacade($this->getProductGroupStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
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
        $this->assertSame($beforeCount + 3, $productGroupStorageCount);
        $spyProductAbstractGroupStorage = SpyProductAbstractGroupStorageQuery::create()->orderByIdProductAbstractGroupStorage()->findOneByFkProductAbstract(1);
        $this->assertNotNull($spyProductAbstractGroupStorage);
        $data = $spyProductAbstractGroupStorage->getData();
        $this->assertSame(3, count($data['group_product_abstract_ids']));
    }
}
