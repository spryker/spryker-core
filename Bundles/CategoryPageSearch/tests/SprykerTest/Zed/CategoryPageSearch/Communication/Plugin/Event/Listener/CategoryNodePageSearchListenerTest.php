<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearchQuery;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchFacade;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeSearchListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeSearchPublishListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeSearchUnpublishListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryPageSearchListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryPageSearchPublishListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryPageSearchUnpublishListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateSearchListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateSearchPublishListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateSearchUnpublishListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeSearchListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeSearchPublishListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeSearchUnpublishListener;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToSearchBridge;
use SprykerTest\Zed\CategoryPageSearch\Business\CategoryPageSearchBusinessFactoryMock;
use SprykerTest\Zed\CategoryPageSearch\CategoryPageSearchConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryPageSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CategoryNodePageSearchListenerTest
 * Add your own group annotations below this line
 */
class CategoryNodePageSearchListenerTest extends Unit
{
    /**
     * @return void
     */
    public function testCategoryPageSearchListenerStoreData(): void
    {
        // Prepare
        SpyCategoryNodePageSearchQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        // Act
        $categoryPageSearchListener = new CategoryNodeSearchListener();
        $categoryPageSearchListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $categoryPageSearchListener->handleBulk($eventTransfers, CategoryEvents::CATEGORY_NODE_PUBLISH);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryPageSearchPublishListener(): void
    {
        // Prepare
        SpyCategoryNodePageSearchQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        // Act
        $categoryNodeSearchPublishListener = new CategoryNodeSearchPublishListener();
        $categoryNodeSearchPublishListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        $categoryNodeSearchPublishListener->handleBulk($eventTransfers, CategoryEvents::CATEGORY_NODE_PUBLISH);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryPageSearchUnpublishListener(): void
    {
        // Prepare
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();
        $categoryNodeSearchUnpublishListener = new CategoryNodeSearchUnpublishListener();
        $categoryNodeSearchUnpublishListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        // Act
        $categoryNodeSearchUnpublishListener->handleBulk($eventTransfers, CategoryEvents::CATEGORY_NODE_UNPUBLISH);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertLessThan($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryTemplateSearchListenerStoreData(): void
    {
        // Prepare
        SpyCategoryNodePageSearchQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        $categoryPageSearchListener = new CategoryNodeCategoryTemplateSearchListener();
        $categoryPageSearchListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        // Act
        $categoryPageSearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_CREATE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryTemplateSearchPublishListener(): void
    {
        // Prepare
        SpyCategoryNodePageSearchQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        $categoryNodeCategoryTemplateSearchPublishListener = new CategoryNodeCategoryTemplateSearchPublishListener();
        $categoryNodeCategoryTemplateSearchPublishListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        // Act
        $categoryNodeCategoryTemplateSearchPublishListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_CREATE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryTemplateSearchUnpublishListener(): void
    {
        // Prepare
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        $categoryPageSearchListener = new CategoryNodeCategoryTemplateSearchUnpublishListener();
        $categoryPageSearchListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        // Act
        $categoryPageSearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_DELETE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertLessThan($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryPageSearchListenerStoreData(): void
    {
        // Prepare
        SpyCategoryNodePageSearchQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        // Act
        $categoryPageSearchListener = new CategoryNodeCategoryPageSearchListener();
        $categoryPageSearchListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $categoryPageSearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_CREATE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryPageSearchPublishListener(): void
    {
        // Prepare
        SpyCategoryNodePageSearchQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        $categoryNodeCategoryPageSearchPublishListener = new CategoryNodeCategoryPageSearchPublishListener();
        $categoryNodeCategoryPageSearchPublishListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        // Act
        $categoryNodeCategoryPageSearchPublishListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_CREATE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryPageSearchUnpublishListener(): void
    {
        // Prepare
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        $categoryNodeCategoryPageSearchUnpublishListener = new CategoryNodeCategoryPageSearchUnpublishListener();
        $categoryNodeCategoryPageSearchUnpublishListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        // Act
        $categoryNodeCategoryPageSearchUnpublishListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_DELETE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertLessThan($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryAttributeSearchListenerStoreData(): void
    {
        // Prepare
        SpyCategoryNodePageSearchQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        $categoryPageSearchListener = new CategoryNodeCategoryAttributeSearchListener();
        $categoryPageSearchListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 1,
            ]),
        ];

        // Act
        $categoryPageSearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryAttributeSearchPublishListener(): void
    {
        // Prepare
        SpyCategoryNodePageSearchQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        $categoryNodeCategoryAttributeSearchPublishListener = new CategoryNodeCategoryAttributeSearchPublishListener();
        $categoryNodeCategoryAttributeSearchPublishListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 1,
            ]),
        ];

        // Act
        $categoryNodeCategoryAttributeSearchPublishListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryAttributeSearchUnpublishListener(): void
    {
        // Prepare
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        $categoryPageSearchListener = new CategoryNodeCategoryAttributeSearchUnpublishListener();
        $categoryPageSearchListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 1,
            ]),
        ];

        // Act
        $categoryPageSearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_DELETE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertLessThan($beforeCount, $afterCount);
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchFacade
     */
    protected function getCategoryPageSearchFacade()
    {
        $categoryPageSearchToSearchBridgeMock = $this->getMockBuilder(CategoryPageSearchToSearchBridge::class)->disableOriginalConstructor()->getMock();
        $categoryPageSearchToSearchBridgeMock->method('transformPageMapToDocumentByMapperName')->willReturn([]);
        $factory = new CategoryPageSearchBusinessFactoryMock($categoryPageSearchToSearchBridgeMock);
        $factory->setConfig(new CategoryPageSearchConfigMock());

        $facade = new CategoryPageSearchFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return void
     */
    protected function assertCategoryPageSearch(): void
    {
        $categoryPageSearchEntity = SpyCategoryNodePageSearchQuery::create()->orderByIdCategoryNodePageSearch()->findOneByFkCategoryNode(1);
        $this->assertNotNull($categoryPageSearchEntity);
        $data = $categoryPageSearchEntity->getStructuredData();
        $encodedData = json_decode($data, true);
        $this->assertEquals('demoshop', $encodedData['spy_category']['category_key']);
    }
}
