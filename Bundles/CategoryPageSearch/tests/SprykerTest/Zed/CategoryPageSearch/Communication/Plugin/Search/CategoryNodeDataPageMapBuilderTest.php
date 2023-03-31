<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\Map\TableMap;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Search\CategoryNodeDataPageMapBuilder;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilder;

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
 * @group CategoryNodeDataPageMapBuilderTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\CategoryPageSearch\CategoryPageSearchCommunicationTester $tester
 */
class CategoryNodeDataPageMapBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildPageMapWillReturnCorrectTransfer(): void
    {
        if ($this->isDynamicStoreEnabled()) {
            $this->markTestSkipped('Test is valid for Dynamic Store mode OFF only.');
        }

        // Arrange
        $categoryNodeDataPageMapBuilder = new CategoryNodeDataPageMapBuilder();
        $categoryNode = $this->getCategoryNodeTreeByIdCategoryTreeForLocaleAndStore(1, 46, Store::getInstance()->getStoreName());

        // Act
        $pageMapTransfer = $categoryNodeDataPageMapBuilder->buildPageMap(new PageMapBuilder(), $categoryNode->toArray(TableMap::TYPE_FIELDNAME, true, [], true), (new LocaleTransfer())->setIdLocale(46));

        // Assert
        $this->assertSame(3, count($pageMapTransfer->getFullText()));
        $this->assertSame('Demoshop', $pageMapTransfer->getFullTextBoosted()[0]);
    }

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     * @param string $storeName
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode
     */
    protected function getCategoryNodeTreeByIdCategoryTreeForLocaleAndStore(int $idCategoryNode, int $idLocale, string $storeName): SpyCategoryNode
    {
        return SpyCategoryNodeQuery::create()
            ->filterByIdCategoryNode($idCategoryNode)
            ->joinWithSpyUrl()
            ->joinWithCategory()
            ->useCategoryQuery()
                ->joinWithAttribute()
                ->joinWithCategoryTemplate()
                ->joinWithSpyCategoryStore()
                    ->useSpyCategoryStoreQuery()
                        ->joinWithSpyStore()
                    ->endUse()
            ->endUse()
            ->where(SpyCategoryAttributeTableMap::COL_FK_LOCALE . ' = ?', $idLocale)
            ->where(SpyUrlTableMap::COL_FK_LOCALE . ' = ?', $idLocale)
            ->where(SpyStoreTableMap::COL_NAME . ' = ?', $storeName)
            ->where(SpyCategoryTableMap::COL_IS_ACTIVE . ' = ?', true)
            ->where(SpyCategoryTableMap::COL_IS_IN_MENU . ' = ?', true)
            ->orderByIdCategoryNode()
            ->find()
            ->getFirst();
    }

    /**
     * @return bool
     */
    protected function isDynamicStoreEnabled(): bool
    {
        return (bool)getenv('SPRYKER_DYNAMIC_STORE_MODE');
    }
}
