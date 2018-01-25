<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImageStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorageQuery;
use Propel\Runtime\Propel;
use Silex\Application;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductImageStorage\Business\ProductImageStorageBusinessFactory;
use Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacade;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetProductImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetProductImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageAbstractPublishStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageConcretePublishStorageListener;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use SprykerTest\Zed\ProductImageStorage\ProductImageStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductImageStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductImageStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductImageStorageListenerTest extends Unit
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
        SpyProductAbstractImageStorageQuery::create()->deleteAll();
        SpyProductConcreteImageStorageQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function testProductImageAbstractPublishStorageListenerStoreData()
    {
        $productImageStorageCount = SpyProductAbstractImageStorageQuery::create()->count();
        $this->assertSame(0, $productImageStorageCount);

        $productImageAbstractPublishStorageListener = new ProductImageAbstractPublishStorageListener();
        $productImageAbstractPublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productImageAbstractPublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $this->assertProductAbstractImageStorage();
    }

    /**
     * @return void
     */
    public function testProductAbstractImageStorageListenerStoreData()
    {
        $productImageStorageCount = SpyProductAbstractImageStorageQuery::create()->count();
        $this->assertSame(0, $productImageStorageCount);

        $productImageAbstractPublishStorageListener = new ProductAbstractImageStorageListener();
        $productImageAbstractPublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productImageAbstractPublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductAbstractImageStorage();
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetStorageListenerStoreData()
    {
        $productImageStorageCount = SpyProductAbstractImageStorageQuery::create()->count();
        $this->assertSame(0, $productImageStorageCount);

        $productAbstractImageSetStorageListener = new ProductAbstractImageSetStorageListener();
        $productAbstractImageSetStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productAbstractImageSetStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $this->assertProductAbstractImageStorage();
    }

    /**
     * @return void
     */
    public function testProductAbstractImageSetProductImageStorageListenerStoreData()
    {
        $productImageStorageCount = SpyProductAbstractImageStorageQuery::create()->count();
        $this->assertSame(0, $productImageStorageCount);

        $productAbstractImageSetProductImageStorageListener = new ProductAbstractImageSetProductImageStorageListener();
        $productAbstractImageSetProductImageStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productAbstractImageSetProductImageStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductAbstractImageStorage();
    }

    /**
     * @return void
     */
    public function testProductImageConcretePublishStorageListenerStoreData()
    {
        $productImageStorageCount = SpyProductConcreteImageStorageQuery::create()->count();
        $this->assertSame(0, $productImageStorageCount);

        $productImageConcretePublishStorageListener = new ProductImageConcretePublishStorageListener();
        $productImageConcretePublishStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productImageConcretePublishStorageListener->handleBulk($eventTransfers, ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH);

        // Assert
        $this->assertProductConcreteImageStorage();
    }

    /**
     * @return void
     */
    public function testProductConcreteImageStorageListenerStoreData()
    {
        $productImageStorageCount = SpyProductConcreteImageStorageQuery::create()->count();
        $this->assertSame(0, $productImageStorageCount);

        $productConcreteImageStorageListener = new ProductConcreteImageStorageListener();
        $productConcreteImageStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productConcreteImageStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductConcreteImageStorage();
    }

    /**
     * @return void
     */
    public function testProductConcreteImageSetStorageListenerStoreData()
    {
        $productImageStorageCount = SpyProductConcreteImageStorageQuery::create()->count();
        $this->assertSame(0, $productImageStorageCount);

        $productConcreteImageSetStorageListener = new ProductConcreteImageSetStorageListener();
        $productConcreteImageSetStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT => 1,
            ]),
        ];
        $productConcreteImageSetStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $this->assertProductConcreteImageStorage();
    }

    /**
     * @return void
     */
    public function testProductConcreteImageSetProductImageStorageListenerStoreData()
    {
        $productImageStorageCount = SpyProductConcreteImageStorageQuery::create()->count();
        $this->assertSame(0, $productImageStorageCount);

        $productConcreteImageSetProductImageStorageListener = new ProductConcreteImageSetProductImageStorageListener();
        $productConcreteImageSetProductImageStorageListener->setFacade($this->getProductImageStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(429),
        ];
        $productConcreteImageSetProductImageStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $this->assertProductConcreteImageStorage();
    }

    /**
     * @return \Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacade
     */
    protected function getProductImageStorageFacade()
    {
        $factory = new ProductImageStorageBusinessFactory();
        $factory->setConfig(new ProductImageStorageConfigMock());

        $facade = new ProductImageStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return void
     */
    protected function assertProductAbstractImageStorage()
    {
        $productImageStorageCount = SpyProductAbstractImageStorageQuery::create()->count();
        $this->assertEquals(2, $productImageStorageCount);
        $spyProductAbstractImageStorage = SpyProductAbstractImageStorageQuery::create()->findOne();
        $this->assertEquals(1, $spyProductAbstractImageStorage->getFkProductAbstract());
        $data = $spyProductAbstractImageStorage->getData();
        $this->assertEquals('default', $data['image_sets'][0]['name']);
    }

    /**
     * @return void
     */
    protected function assertProductConcreteImageStorage()
    {
        $productImageStorageCount = SpyProductConcreteImageStorageQuery::create()->count();
        $this->assertEquals(2, $productImageStorageCount);
        $productConcreteImageStorage = SpyProductConcreteImageStorageQuery::create()->findOne();
        $this->assertEquals(1, $productConcreteImageStorage->getFkProduct());
        $data = $productConcreteImageStorage->getData();
        $this->assertEquals('default', $data['image_sets'][0]['name']);
    }
}
