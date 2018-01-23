<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
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
use Propel\Runtime\Propel;
use Silex\Application;
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
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
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
    /**
     * @return void
     */
    protected function setUp()
    {
        Propel::disableInstancePooling();
        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        SpyProductAbstractStorageQuery::create()->deleteAll();
        SpyProductConcreteStorageQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function testProductAbstractStorageListenerStoreData()
    {
        $productStorageCount = SpyProductAbstractStorageQuery::create()->count();
        $this->assertSame(0, $productStorageCount);

        $productAbstractStorageListener = new ProductAbstractStorageListener();
        $productAbstractStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productAbstractStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $this->assertProductAbstractStorage();
    }

    /**
     * @return void
     */
    public function testProductAbstractUrlStorageListenerStoreData()
    {
        $productStorageCount = SpyProductAbstractStorageQuery::create()->count();
        $this->assertSame(0, $productStorageCount);

        $productAbstractUrlStorageListener = new ProductAbstractUrlStorageListener();
        $productAbstractUrlStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => 1
            ])
            ->setModifiedColumns([SpyUrlTableMap::COL_URL])
        ];
        $productAbstractUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_CREATE);

        // Assert
        $this->assertProductAbstractStorage();
    }

    /**
     * @return void
     */
    public function testProductAbstractLocalizedAttributesStorageListenerStoreData()
    {
        $productStorageCount = SpyProductAbstractStorageQuery::create()->count();
        $this->assertSame(0, $productStorageCount);

        $productAbstractLocalizedAttributesStorageListener = new ProductAbstractLocalizedAttributesStorageListener();
        $productAbstractLocalizedAttributesStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => 1
            ])
        ];
        $productAbstractLocalizedAttributesStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE);

        // Assert
        $this->assertProductAbstractStorage();
    }

    /**
     * @return void
     */
    public function testProductConcreteProductAbstractRelationStorageListenerStoreData()
    {
        $productStorageCount = SpyProductAbstractStorageQuery::create()->count();
        $this->assertSame(0, $productStorageCount);

        $productConcreteProductAbstractRelationStorageListener = new ProductConcreteProductAbstractRelationStorageListener();
        $productConcreteProductAbstractRelationStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => 1
            ])
        ];
        $productConcreteProductAbstractRelationStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_CREATE);

        // Assert
        $this->assertProductAbstractStorage();
    }

    /**
     * @return void
     */
    public function testProductConcreteStorageListenerStoreData()
    {
        $productStorageCount = SpyProductConcreteStorageQuery::create()->count();
        $this->assertSame(0, $productStorageCount);

        $productConcreteStorageListener = new ProductConcreteStorageListener();
        $productConcreteStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productConcreteStorageListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $this->assertProductConcreteStorage();
    }

    /**
     * @return void
     */
    public function testProductConcreteRelationUrlStorageListenerStoreData()
    {
        $productStorageCount = SpyProductConcreteStorageQuery::create()->count();
        $this->assertSame(0, $productStorageCount);

        $productConcreteProductAbstractUrlStorageListener = new ProductConcreteProductAbstractUrlStorageListener();
        $productConcreteProductAbstractUrlStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => 1
            ])
                ->setModifiedColumns([SpyUrlTableMap::COL_URL])
        ];
        $productConcreteProductAbstractUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_CREATE);

        // Assert
        $this->assertProductConcreteStorage();
    }

    /**
     * @return void
     */
    public function testProductConcreteProductAbstractStorageListenerStoreData()
    {
        $productStorageCount = SpyProductConcreteStorageQuery::create()->count();
        $this->assertSame(0, $productStorageCount);

        $productConcreteProductAbstractStorageListener = new ProductConcreteProductAbstractStorageListener();
        $productConcreteProductAbstractStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productConcreteProductAbstractStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE);

        // Assert
        $this->assertProductConcreteStorage();
    }

    /**
     * @return void
     */
    public function testProductConcreteProductAbstractLocalizedAttributesStorageListenerStoreData()
    {
        $productStorageCount = SpyProductConcreteStorageQuery::create()->count();
        $this->assertSame(0, $productStorageCount);

        $productConcreteProductAbstractLocalizedAttributesStorageListener = new ProductConcreteProductAbstractLocalizedAttributesStorageListener();
        $productConcreteProductAbstractLocalizedAttributesStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => 1
            ])
        ];
        $productConcreteProductAbstractLocalizedAttributesStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE);

        // Assert
        $this->assertProductConcreteStorage();
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesStorageListenerStoreData()
    {
        $productStorageCount = SpyProductConcreteStorageQuery::create()->count();
        $this->assertSame(0, $productStorageCount);

        $productConcreteLocalizedAttributesStorageListener = new ProductConcreteLocalizedAttributesStorageListener();
        $productConcreteLocalizedAttributesStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => 1
            ])
        ];
        $productConcreteLocalizedAttributesStorageListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE);

        // Assert
        $this->assertProductConcreteStorage();
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
     * @return void
     */
    protected function assertProductAbstractStorage()
    {
        $productStorageCount = SpyProductAbstractStorageQuery::create()->count();
        $this->assertEquals(2, $productStorageCount);
        $spyProductAbstractStorage = SpyProductAbstractStorageQuery::create()->findOne();
        $this->assertEquals(1, $spyProductAbstractStorage->getFkProductAbstract());
        $data = $spyProductAbstractStorage->getData();
        $this->assertEquals('001', $data['sku']);
        $this->assertEquals(6, count($data['attributes']));
        $this->assertEquals('/de/canon-ixus-160-001', $data['url']);
    }

    /**
     * @return void
     */
    protected function assertProductConcreteStorage()
    {
        $productStorageCount = SpyProductConcreteStorageQuery::create()->count();
        $this->assertEquals(2, $productStorageCount);
        $spyProductConcreteStorage = SpyProductConcreteStorageQuery::create()->findOne();
        $this->assertEquals(1, $spyProductConcreteStorage->getFkProduct());
        $data = $spyProductConcreteStorage->getData();
        $this->assertEquals('001_25904006', $data['sku']);
        $this->assertEquals(6, count($data['attributes']));
        $this->assertEquals('/de/canon-ixus-160-001', $data['url']);
    }
}
