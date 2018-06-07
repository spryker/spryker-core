<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery;
use Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorageQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacade;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractUrlStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractRelationStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractUrlStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteStorageListener;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\ProductStorage\ProductStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductStorageListenerTest extends Unit
{
    const NUMBER_OF_STORES = 3;
    const NUMBER_OF_LOCALES = 2;

    /**
     * @var \SprykerTest\Zed\ProductStorage\ProductStorageCommunicationTester
     */
    protected $tester;

    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        if (!$this->tester->isSuiteProject()) {
            throw new SkippedTestError('Warning: not in suite environment');
        }

        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }
    }

    /**
     * @return void
     */
    public function testProductAbstractStorageListenerStoreData()
    {
        SpyProductAbstractStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractStorageQuery::create()->count();

        $productAbstractStorageListener = new ProductAbstractStorageListener();
        $productAbstractStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productAbstractStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractUrlStorageListenerStoreData()
    {
        SpyProductAbstractStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractStorageQuery::create()->count();

        $productAbstractUrlStorageListener = new ProductAbstractUrlStorageListener();
        $productAbstractUrlStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => 1,
            ])
            ->setModifiedColumns([SpyUrlTableMap::COL_URL]),
        ];
        $productAbstractUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_CREATE);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductAbstractLocalizedAttributesStorageListenerStoreData()
    {
        SpyProductAbstractStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractStorageQuery::create()->count();

        $productAbstractLocalizedAttributesStorageListener = new ProductAbstractLocalizedAttributesStorageListener();
        $productAbstractLocalizedAttributesStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productAbstractLocalizedAttributesStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteProductAbstractRelationStorageListenerStoreData()
    {
        SpyProductAbstractStorageQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractStorageQuery::create()->count();

        $productConcreteProductAbstractRelationStorageListener = new ProductConcreteProductAbstractRelationStorageListener();
        $productConcreteProductAbstractRelationStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productConcreteProductAbstractRelationStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_CREATE);

        // Assert
        $this->assertProductAbstractStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteStorageListenerStoreData()
    {
        SpyProductConcreteStorageQuery::create()->filterByFkProduct(1)->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteStorageListener = new ProductConcreteStorageListener();
        $productConcreteStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productConcreteStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteRelationUrlStorageListenerStoreData()
    {
        SpyProductConcreteStorageQuery::create()->filterByFkProduct(1)->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteProductAbstractUrlStorageListener = new ProductConcreteProductAbstractUrlStorageListener();
        $productConcreteProductAbstractUrlStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => 1,
            ])
                ->setModifiedColumns([SpyUrlTableMap::COL_URL]),
        ];
        $productConcreteProductAbstractUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_CREATE);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteProductAbstractStorageListenerStoreData()
    {
        SpyProductConcreteStorageQuery::create()->filterByFkProduct(1)->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteProductAbstractStorageListener = new ProductConcreteProductAbstractStorageListener();
        $productConcreteProductAbstractStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productConcreteProductAbstractStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteProductAbstractLocalizedAttributesStorageListenerStoreData()
    {
        SpyProductConcreteStorageQuery::create()->filterByFkProduct(1)->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteProductAbstractLocalizedAttributesStorageListener = new ProductConcreteProductAbstractLocalizedAttributesStorageListener();
        $productConcreteProductAbstractLocalizedAttributesStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productConcreteProductAbstractLocalizedAttributesStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesStorageListenerStoreData()
    {
        SpyProductConcreteStorageQuery::create()->filterByFkProduct(1)->delete();
        $beforeCount = SpyProductConcreteStorageQuery::create()->count();

        $productConcreteLocalizedAttributesStorageListener = new ProductConcreteLocalizedAttributesStorageListener();
        $productConcreteLocalizedAttributesStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => 1,
            ]),
        ];
        $productConcreteLocalizedAttributesStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE);

        // Assert
        $this->assertProductConcreteStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Business\ProductStorageFacade
     */
    protected function getProductStorageFacade()
    {
        $factory = new ProductStorageBusinessFactory();
        $factory->setConfig(new ProductStorageConfigMock());

        $facade = new ProductStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractStorage($beforeCount)
    {
        $afterCount = SpyProductAbstractStorageQuery::create()->count();
        $this->assertSame($beforeCount + static::NUMBER_OF_LOCALES * static::NUMBER_OF_STORES, $afterCount);
        $spyProductAbstractStorage = SpyProductAbstractStorageQuery::create()
            ->orderByIdProductAbstractStorage()
            ->findOneByFkProductAbstract(1);
        $this->assertNotNull($spyProductAbstractStorage);
        $data = $spyProductAbstractStorage->getData();
        $this->assertSame('001', $data['sku']);
        $this->assertSame(6, count($data['attributes']));
        $this->assertSame('/de/canon-ixus-160-001', $data['url']);
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductConcreteStorage($beforeCount)
    {
        $afterCount = SpyProductConcreteStorageQuery::create()->count();
        $this->assertSame($beforeCount + static::NUMBER_OF_LOCALES, $afterCount);
        $spyProductConcreteStorage = SpyProductConcreteStorageQuery::create()
            ->orderByIdProductConcreteStorage()
            ->findOneByFkProduct(1);
        $this->assertNotNull($spyProductConcreteStorage);
        $data = $spyProductConcreteStorage->getData();
        $this->assertSame('001_25904006', $data['sku']);
        $this->assertSame(6, count($data['attributes']));
        $this->assertSame('/de/canon-ixus-160-001', $data['url']);
    }
}
