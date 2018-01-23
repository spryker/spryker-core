<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery;
use Propel\Runtime\Propel;
use Silex\Application;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\CategoryStorage\Business\CategoryStorageBusinessFactory;
use Spryker\Zed\CategoryStorage\Business\CategoryStorageFacade;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryTreeStorageListener;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use SprykerTest\Zed\CategoryStorage\CategoryStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CategoryStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CategoryStorageListenerTest
 * Add your own group annotations below this line
 */
class CategoryStorageListenerTest extends Unit
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
        SpyCategoryNodeStorageQuery::create()->deleteall();
        SpyCategoryTreeStorageQuery::create()->deleteall();
    }

    /**
     * @return void
     */
    public function testCategoryNodeStorageListenerStoreData()
    {
        $categoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertSame(0, $categoryStorageCount);

        $categoryNodeStorageListener = new CategoryNodeStorageListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::CATEGORY_NODE_PUBLISH);

        // Assert
        $this->assertCategoryNodeStorage();
    }

    /**
     * @return void
     */
    public function testCategoryStorageListenerStoreData()
    {
        $categoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertSame(0, $categoryStorageCount);

        $categoryNodeStorageListener = new CategoryNodeCategoryStorageListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_CREATE);

        // Assert
        $this->assertCategoryNodeStorage();
    }

    /**
     * @return void
     */
    public function testCategoryTemplateStorageListenerStoreData()
    {
        $categoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertSame(0, $categoryStorageCount);

        $categoryNodeStorageListener = new CategoryNodeCategoryTemplateStorageListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_CREATE);

        // Assert
        $CategoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertEquals(18, $CategoryStorageCount);
    }

    /**
     * @return void
     */
    public function testCategoryAttributeStorageListenerStoreData()
    {
        $categoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertSame(0, $categoryStorageCount);

        $categoryNodeStorageListener = new CategoryNodeCategoryAttributeStorageListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 1,
            ]),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE);

        // Assert
        $this->assertCategoryNodeStorage();
    }

    /**
     * @return void
     */
    public function testCategoryTreeStorageListenerStoreData()
    {
        $categoryStorageCount = SpyCategoryTreeStorageQuery::create()->count();
        $this->assertSame(0, $categoryStorageCount);

        $categoryTreeStorageListener = new CategoryTreeStorageListener();
        $categoryTreeStorageListener->setFacade($this->getCategoryStorageFacade());
        $categoryTreeStorageListener->handleBulk([new EventEntityTransfer()], CategoryEvents::CATEGORY_TREE_PUBLISH);

        // Assert
        $this->assertCategoryTreeStorage();
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacade
     */
    protected function getCategoryStorageFacade()
    {
        $factory = new CategoryStorageBusinessFactory();
        $factory->setConfig(new CategoryStorageConfigMock());

        $facade = new CategoryStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return void
     */
    protected function assertCategoryNodeStorage()
    {
        $CategoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertEquals(2, $CategoryStorageCount);
        $spyCategoryNodeStorage = SpyCategoryNodeStorageQuery::create()->findOne();
        $this->assertEquals(1, $spyCategoryNodeStorage->getFkCategoryNode());
        $data = $spyCategoryNodeStorage->getData();
        $this->assertEquals('Demoshop', $data['name']);
        $this->assertEquals('Demoshop', $data['meta_title']);
        $this->assertEquals(6, count($data['children']));
    }

    /**
     * @return void
     */
    protected function assertCategoryTreeStorage()
    {
        $CategoryStorageCount = SpyCategoryTreeStorageQuery::create()->count();
        $this->assertEquals(2, $CategoryStorageCount);
        $spyCategoryNodeStorage = SpyCategoryTreeStorageQuery::create()->findOne();
        $data = $spyCategoryNodeStorage->getData();
        $this->assertEquals(4, count($data['category_nodes_storage']));
    }
}
