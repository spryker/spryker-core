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
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\CategoryStorage\Business\CategoryStorageBusinessFactory;
use Spryker\Zed\CategoryStorage\Business\CategoryStorageFacade;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageListener;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener\CategoryTreeStorageListener;
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
    public function testCategoryNodeStorageListenerStoreData()
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
    public function testCategoryStorageListenerStoreData()
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
    public function testCategoryTemplateStorageListenerStoreData()
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
    public function testCategoryAttributeStorageListenerStoreData()
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
    public function testCategoryTreeStorageListenerStoreData()
    {
        SpyCategoryTreeStorageQuery::create()->deleteall();

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
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertCategoryNodeStorage($beforeCount)
    {
        $CategoryStorageCount = SpyCategoryNodeStorageQuery::create()->count();
        $this->assertEquals($beforeCount + 2, $CategoryStorageCount);
        $spyCategoryNodeStorage = SpyCategoryNodeStorageQuery::create()->orderByIdCategoryNodeStorage()->findOneByFkCategoryNode(1);
        $this->assertNotNull($spyCategoryNodeStorage);
        $data = $spyCategoryNodeStorage->getData();
        $this->assertEquals('Demoshop', $data['name']);
        $this->assertEquals('Demoshop', $data['meta_title']);
        $this->assertGreaterThanOrEqual(6, count($data['children']));
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
        $this->assertGreaterThanOrEqual(4, count($data['category_nodes_storage']));
    }
}
