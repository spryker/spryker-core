<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\ProductStorage\Persistence\SpyCategoryNodeStorageQuery;
use Orm\Zed\ProductStorage\Persistence\SpyCategoryTreeStorageQuery;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery;
use Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorageQuery;
use Propel\Runtime\Propel;
use Silex\Application;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacade;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\CategoryNodeProductStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\CategoryTreeStorageListener;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
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
    public function testCategoryNodeStorageListenerStoreData()
    {
        return;
        $productStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertSame(0, $productStorageCount);

        $categoryNodeStorageListener = new CategoryNodeStorageListener();
        $categoryNodeStorageListener->setFacade($this->getProductStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::CATEGORY_NODE_PUBLISH);

        // Assert
        $this->assertCategoryNodeStorage();
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
    protected function assertCategoryNodeStorage()
    {
        $ProductStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertEquals(2, $ProductStorageCount);
        $spyCategoryNodeStorage = SpyCategoryNodeStorageQuery::create()->findOne();
        $this->assertEquals(1, $spyCategoryNodeStorage->getFkCategoryNode());
        $data = $spyCategoryNodeStorage->getData();
        $this->assertEquals('Demoshop', $data['name']);
        $this->assertEquals('Demoshop', $data['meta_title']);
        $this->assertEquals(6, count($data['children']));
    }
}
