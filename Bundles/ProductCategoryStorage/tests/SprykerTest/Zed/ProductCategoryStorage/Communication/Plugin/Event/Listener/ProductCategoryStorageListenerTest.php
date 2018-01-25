<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\Propel;
use Silex\Application;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageBusinessFactory;
use Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageFacade;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryAttributeStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryUrlStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\ProductCategoryPublishStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\ProductCategoryStorageListener;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\ProductCategoryStorage\ProductCategoryStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductCategoryStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductCategoryStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductCategoryStorageListenerTest extends Unit
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
        SpyProductAbstractCategoryStorageQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function testProductCategoryPublishStorageListenerStoreData()
    {
        $productCategoryStorageCount = SpyProductAbstractCategoryStorageQuery::create()->count();
        $this->assertSame(0, $productCategoryStorageCount);

        $productCategoryPublishStorageListener = new ProductCategoryPublishStorageListener();
        $productCategoryPublishStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productCategoryPublishStorageListener->handleBulk($eventTransfers, ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH);

        // Assert
        $this->assertProductAbstractCategoryStorage();
    }

    /**
     * @return void
     */
    public function testProductCategoryStorageListenerStoreData()
    {
        $productCategoryStorageCount = SpyProductAbstractCategoryStorageQuery::create()->count();
        $this->assertSame(0, $productCategoryStorageCount);

        $productCategoryStorageListener = new ProductCategoryStorageListener();
        $productCategoryStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productCategoryStorageListener->handleBulk($eventTransfers, ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE);

        // Assert
        $this->assertProductAbstractCategoryStorage();
    }

    /**
     * @return void
     */
    public function testCategoryNodeStorageListenerStoreData()
    {
        $productCategoryStorageCount = SpyProductAbstractCategoryStorageQuery::create()->count();
        $this->assertSame(0, $productCategoryStorageCount);

        $categoryNodeStorageListener = new CategoryNodeStorageListener();
        $categoryNodeStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryNodeTableMap::COL_FK_CATEGORY => 7,
            ]),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE);

        // Assert
        $this->assertProductAbstractStorage();
    }

    /**
     * @return void
     */
    public function testCategoryUrlStorageListenerStoreData()
    {
        $productCategoryStorageCount = SpyProductAbstractCategoryStorageQuery::create()->count();
        $this->assertSame(0, $productCategoryStorageCount);

        $categoryUrlStorageListener = new CategoryUrlStorageListener();
        $categoryUrlStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE => 7,
            ])
                ->setModifiedColumns([
                    SpyUrlTableMap::COL_URL,
                ]),
        ];
        $categoryUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_CREATE);

        // Assert
        $this->assertProductAbstractStorage();
    }

    /**
     * @return void
     */
    public function testCategoryAttributeStorageListenerStoreData()
    {
        $productCategoryStorageCount = SpyProductAbstractCategoryStorageQuery::create()->count();
        $this->assertSame(0, $productCategoryStorageCount);

        $categoryAttributeStorageListener = new CategoryAttributeStorageListener();
        $categoryAttributeStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 7,
            ])
                ->setModifiedColumns([
                    SpyCategoryAttributeTableMap::COL_NAME,
                ]),
        ];
        $categoryAttributeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE);

        // Assert
        $this->assertProductAbstractStorage();
    }

    /**
     * @return void
     */
    public function testCategoryStorageListenerStoreData()
    {
        $productCategoryStorageCount = SpyProductAbstractCategoryStorageQuery::create()->count();
        $this->assertSame(0, $productCategoryStorageCount);

        $categoryStorageListener = new CategoryStorageListener();
        $categoryStorageListener->setFacade($this->getProductCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(7)
                ->setModifiedColumns([
                    SpyCategoryTableMap::COL_IS_ACTIVE,
                ]),
        ];
        $categoryStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_CREATE);

        // Assert
        $this->assertProductAbstractStorage();
    }

    /**
     * @return \Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageFacade
     */
    protected function getProductCategoryStorageFacade()
    {
        $factory = new ProductCategoryStorageBusinessFactory();
        $factory->setConfig(new ProductCategoryStorageConfigMock());

        $facade = new ProductCategoryStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return void
     */
    protected function assertProductAbstractStorage()
    {
        $productCategoryStorageCount = SpyProductAbstractCategoryStorageQuery::create()->count();
        $this->assertEquals(40, $productCategoryStorageCount);
    }

    /**
     * @return void
     */
    protected function assertProductAbstractCategoryStorage()
    {
        $productCategoryStorageCount = SpyProductAbstractCategoryStorageQuery::create()->count();
        $this->assertEquals(2, $productCategoryStorageCount);
        $spyProductAbstractCategoryStorage = SpyProductAbstractCategoryStorageQuery::create()->findOne();
        $this->assertEquals(1, $spyProductAbstractCategoryStorage->getFkProductAbstract());
        $data = $spyProductAbstractCategoryStorage->getData();
        $this->assertEquals(2, count($data['categories']));
    }
}
