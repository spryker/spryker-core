<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\CategoryStorage\Business\CategoryStorageBusinessFactory;
use Spryker\Zed\CategoryStorage\Business\CategoryStorageFacade;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeStoragePublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeStorageUnpublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryStoragePublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryStorageUnpublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateStoragePublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStoragePublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageUnpublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryTreeStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryTreeStoragePublishListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryTreeStorageUnpublishListener;
use SprykerTest\Zed\CategoryStorage\CategoryStorageConfigMock;

/**
 * Auto-generated group annotations
 *
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
    public function testCategoryNodeStorageListenerStoreData(): void
    {
        SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->delete();
        $categoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();

        $categoryNodeStorageListener = new CategoryNodeStorageListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::CATEGORY_NODE_PUBLISH);

        // Assert
        $this->assertCategoryNodeStorage($categoryStorageCount);
    }

    /**
     * @return void
     */
    public function testCategoryNodeStoragePublishListener(): void
    {
        SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->delete();
        $categoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();

        $categoryNodeStorageListener = new CategoryNodeStoragePublishListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::CATEGORY_NODE_PUBLISH);

        // Assert
        $this->assertCategoryNodeStorage($categoryStorageCount);
    }

    /**
     * @return void
     */
    public function testCategoryNodeStorageUnpublishListener(): void
    {
        $categoryNodeStorageListener = new CategoryNodeStorageUnpublishListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::CATEGORY_NODE_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->count());
    }

    /**
     * @return void
     */
    public function testCategoryStorageListenerStoreData(): void
    {
        SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->delete();
        $categoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();

        $categoryNodeStorageListener = new CategoryNodeCategoryStorageListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_CREATE);

        // Assert
        $this->assertCategoryNodeStorage($categoryStorageCount);
    }

    /**
     * @return void
     */
    public function testCategoryStorageListenerPublish(): void
    {
        SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->delete();
        $categoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();

        $categoryNodeStorageListener = new CategoryNodeCategoryStoragePublishListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_CREATE);

        // Assert
        $this->assertCategoryNodeStorage($categoryStorageCount);
    }

    /**
     * @return void
     */
    public function testCategoryStorageListenerUnpublish(): void
    {
        $categoryNodeStorageListener = new CategoryNodeCategoryStorageUnpublishListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_DELETE);

        $this->assertSame(0, SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->count());
    }

    /**
     * @return void
     */
    public function testCategoryTemplateStorageListenerStoreData(): void
    {
        SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodeStorageQuery::create()->count();

        $categoryNodeStorageListener = new CategoryNodeCategoryTemplateStorageListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_CREATE);

        // Assert
        $CategoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $CategoryStorageCount);
    }

    /**
     * @return void
     */
    public function testCategoryTemplateStoragePublishListener(): void
    {
        SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodeStorageQuery::create()->count();

        $categoryNodeStorageListener = new CategoryNodeCategoryTemplateStoragePublishListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_CREATE);

        // Assert
        $categoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $categoryStorageCount);
    }

    /**
     * @return void
     */
    public function testCategoryTemplateStorageUnpublishListener(): void
    {
        $categoryNodeStorageListener = new CategoryNodeCategoryTemplateStoragePublishListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_DELETE);

        // Assert
        $this->assertGreaterThan(0, SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->count());
    }

    /**
     * @return void
     */
    public function testCategoryAttributeStorageListenerStoreData(): void
    {
        SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodeStorageQuery::create()->count();

        $categoryNodeStorageListener = new CategoryNodeCategoryAttributeStorageListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 1,
            ]),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE);

        // Assert
        $this->assertCategoryNodeStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCategoryAttributeStoragePublishListener(): void
    {
        SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodeStorageQuery::create()->count();

        $categoryNodeStorageListener = new CategoryNodeCategoryAttributeStoragePublishListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 1,
            ]),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE);

        // Assert
        $this->assertCategoryNodeStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCategoryAttributeStorageUnpublishListener(): void
    {
        $categoryNodeStorageListener = new CategoryNodeCategoryAttributeStorageUnpublishListener();
        $categoryNodeStorageListener->setFacade($this->getCategoryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 1,
            ]),
        ];
        $categoryNodeStorageListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_DELETE);

        // Assert
        $this->assertSame(0, SpyCategoryNodeStorageQuery::create()->filterByFkCategoryNode(1)->count());
    }

    /**
     * @return void
     */
    public function testCategoryTreeStorageListenerStoreData(): void
    {
        SpyCategoryTreeStorageQuery::create()->deleteall();

        $categoryTreeStorageListener = new CategoryTreeStorageListener();
        $categoryTreeStorageListener->setFacade($this->getCategoryStorageFacade());
        $categoryTreeStorageListener->handleBulk([new EventEntityTransfer()], CategoryEvents::CATEGORY_TREE_PUBLISH);

        // Assert
        $this->assertCategoryTreeStorage();
    }

    /**
     * @return void
     */
    public function testCategoryTreeStoragePublishListener(): void
    {
        SpyCategoryTreeStorageQuery::create()->deleteall();

        $categoryTreeStorageListener = new CategoryTreeStoragePublishListener();
        $categoryTreeStorageListener->setFacade($this->getCategoryStorageFacade());
        $categoryTreeStorageListener->handleBulk([new EventEntityTransfer()], CategoryEvents::CATEGORY_TREE_PUBLISH);

        // Assert
        $this->assertCategoryTreeStorage();
    }

    /**
     * @return void
     */
    public function testCategoryTreeStorageUnpublishListener(): void
    {
        $categoryTreeStorageListener = new CategoryTreeStorageUnpublishListener();
        $categoryTreeStorageListener->setFacade($this->getCategoryStorageFacade());
        $categoryTreeStorageListener->handleBulk([new EventEntityTransfer()], CategoryEvents::CATEGORY_TREE_UNPUBLISH);

        // Assert
        $categoryStorageCount = SpyCategoryTreeStorageQuery::create()->count();
        $this->assertEquals(0, $categoryStorageCount);
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacade
     */
    protected function getCategoryStorageFacade(): CategoryStorageFacade
    {
        $factory = new CategoryStorageBusinessFactory();
        $factory->setConfig(new CategoryStorageConfigMock());

        $facade = new CategoryStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertCategoryNodeStorage(int $beforeCount): void
    {
        $CategoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $CategoryStorageCount);

        $spyCategoryNodeStorage = SpyCategoryNodeStorageQuery::create()
            ->orderByIdCategoryNodeStorage()
            ->findOneByFkCategoryNode(1);

        $this->assertNotNull($spyCategoryNodeStorage);

        $data = $spyCategoryNodeStorage->getData();
        $this->assertEquals('Demoshop', $data['name']);
        $this->assertEquals('Demoshop', $data['meta_title']);
        $this->assertGreaterThanOrEqual(6, count($data['children']));
    }

    /**
     * @return void
     */
    protected function assertCategoryTreeStorage(): void
    {
        $categoryStorageCount = SpyCategoryTreeStorageQuery::create()->count();
        $this->assertGreaterThanOrEqual(2, $categoryStorageCount);

        $spyCategoryNodeStorage = SpyCategoryTreeStorageQuery::create()->findOne();
        $data = $spyCategoryNodeStorage->getData();
        $this->assertGreaterThanOrEqual(4, count($data['category_nodes_storage']));
    }
}
