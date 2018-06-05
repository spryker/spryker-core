<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearchQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchFacade;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryAttributeSearchListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryPageSearchListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeCategoryTemplateSearchListener;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener\CategoryNodeSearchListener;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToSearchBridge;
use SprykerTest\Zed\CategoryPageSearch\Business\CategoryPageSearchBusinessFactoryMock;
use SprykerTest\Zed\CategoryPageSearch\CategoryPageSearchConfigMock;

/**
 * Auto-generated group annotations
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
    public function testCategoryPageSearchListenerStoreData()
    {
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
        $this->assertEquals($beforeCount + 2, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryTemplateSearchListenerStoreData()
    {
        SpyCategoryNodePageSearchQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        // Act
        $categoryPageSearchListener = new CategoryNodeCategoryTemplateSearchListener();
        $categoryPageSearchListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $categoryPageSearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_TEMPLATE_CREATE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryPageSearchListenerStoreData()
    {
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
        $this->assertEquals($beforeCount + 2, $afterCount);
        $this->assertCategoryPageSearch();
    }

    /**
     * @return void
     */
    public function testCategoryNodeCategoryAttributeSearchListenerStoreData()
    {
        SpyCategoryNodePageSearchQuery::create()->filterByFkCategoryNode(1)->delete();
        $beforeCount = SpyCategoryNodePageSearchQuery::create()->count();

        // Act
        $categoryPageSearchListener = new CategoryNodeCategoryAttributeSearchListener();
        $categoryPageSearchListener->setFacade($this->getCategoryPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 1,
            ]),
        ];
        $categoryPageSearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE);

        // Assert
        $afterCount = SpyCategoryNodePageSearchQuery::create()->count();
        $this->assertEquals($beforeCount + 2, $afterCount);
        $this->assertCategoryPageSearch();
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
    protected function assertCategoryPageSearch()
    {
        $categoryPageSearchEntity = SpyCategoryNodePageSearchQuery::create()->orderByIdCategoryNodePageSearch()->findOneByFkCategoryNode(1);
        $this->assertNotNull($categoryPageSearchEntity);
        $data = $categoryPageSearchEntity->getStructuredData();
        $encodedData = json_decode($data, true);
        $this->assertEquals('demoshop', $encodedData['spy_category']['category_key']);
    }
}
